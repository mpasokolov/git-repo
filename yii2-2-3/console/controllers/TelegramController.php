<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2019-02-16
 * Time: 19:06
 */

namespace console\controllers;


use common\models\Tasks;
use common\models\Teams;
use common\models\TelegramAuth;
use common\models\TelegramOffset;
use common\models\TelegramSubscribe;
use common\models\User;
use SonkoDmitry\Yii\TelegramBot\Component;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;
use yii\console\Controller;

class TelegramController extends Controller {
    /** @var Component $bot */
    private $bot;
    private $offset = 0;

    public function init() {
        parent::init();
        /** @var Component $bot */
        $bot = \Yii::$app->bot;
        $bot -> setCurlOption(CURLOPT_TIMEOUT, 20);
        $bot -> setCurlOption(CURLOPT_CONNECTTIMEOUT, 10);
        $bot -> setCurlOption(CURLOPT_HTTPHEADER, ['Expect:']);
        $this -> bot = $bot;
    }

    public function actionIndex() {
        $updates = $this -> bot -> getUpdates($this -> getOffset() + 1);
        $updCount = count($updates);

        if ($updCount > 0) {
            foreach ($updates as $update) {
                $this -> updateOffset($update);
                if ($message = $update -> getMessage()) {
                    $this -> processCommand($message);
                }
            }
            echo 'Новых сообщений: ' . $updCount . PHP_EOL;
        } else {
            echo 'Новых сообщений нет!' . PHP_EOL;
        }
    }

    private function getOffset() {
        $max = TelegramOffset::find()
            -> select('id')
            -> max('id');

        if ($max > 0) {
            $this -> offset = $max;
        }

        return $this -> offset;
    }

    private function updateOffset(Update $update) {
        $model = new TelegramOffset([
            'id' => $update -> getUpdateId(),
            'timestamp_offset' => date("Y-m-d H:i:s"),
        ]);
        $model -> save();
    }

    private function processCommand(Message $message) {
        $params = explode(' ', $message -> getText());
        $command = $params[0];
        $response = 'unknown command';

        switch ($command) {
            case '/help':
                $response = "Доступные команды \n";
                $response .= "/help - список команд\n";
                $response .= "/sp_create - подписка на создание проектов\n";
                $response .= "/login ##username ##password  подписка на создание проектов\n";
                $response .= "/task_create ##name ##team ##user ##deadline ##description - создание задачи\n";
                break;
            case '/sp_create':
                $model = new TelegramSubscribe([
                    'channel' => 'project_create',
                    'telegram_chat_id' => $message -> getFrom() -> getId(),
                ]);

                if ($model -> save()) {
                    $response = 'Вы подписаны на уведомления по созданию проектов';
                } else {
                    $response = 'При подписке произошла ошибка';
                }
                break;
            case '/login':
                if (!isset($params[1]) || !isset($params[2])) {
                    $response = 'Команда введена неверно';
                    break;
                } else {
                    $user = User::findByUsername($params[1]);
                    if (!$user) {
                        $response = 'Пользователя с данным логином не существует';
                        break;
                    }

                    if (!$user -> validatePassword($params[2])) {
                        $response = 'Пароль не верен';
                        break;
                    }

                    $model = new TelegramAuth([
                        'user_id' => $user -> id,
                        'chat_id' => $message -> getFrom() -> getId()
                    ]);

                    if ($model -> save()) {
                        $response = 'Вы успешно авторизовались';
                    } else {
                        $response = 'Ошибка авторизации, обратитесь к администратору';
                    }
                }
                break;
            case '/task_create':
                $model = TelegramAuth::find() -> where(['chat_id' => $message -> getFrom() -> getId()]) -> one();

                if (!$model) {
                    $response = 'Для создания задач вы должны авторизоваться. Введите команду /login';
                    break;
                }

                if (!isset($params[1]) || !isset($params[2]) || !isset($params[3]) || !isset($params[4]) || !isset($params[5])) {
                    $response = 'Команда введена неверно';
                    break;
                }

                $team = Teams::find() -> where(['name' => $params[2]]) -> one();

                if (!$team) {
                    $response = 'Команды c таким именем не существует';
                    break;
                }

                $user = User::findByUsername($params[3]);

                if (!$user) {
                    $response = 'Пользователя c таким именем не существует';
                    break;
                }

                $regexp = '/^\d{4}-\d{2}-\d{2}$/';

                if (!preg_match($regexp, $params[4])) {
                    $response = 'Дата должна быть в формате Y-m-d';
                    break;
                }

                $task = new Tasks([
                    'name' =>  $params[1],
                    'id_admin' => $model -> user_id,
                    'id_user' => $user -> id,
                    'deadline' => $params[4],
                    'description' => $params[5],
                    'id_team' => $team -> id
                ]);

                if ($task -> save()) {
                    $response = 'Задача успешно создана';
                } else {
                    $response = 'Ошибка при создании задачи';
                }
                break;

        }

        $this -> bot -> sendMessage($message -> getFrom() -> getId(), $response);
    }
}