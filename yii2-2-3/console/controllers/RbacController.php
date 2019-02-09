<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2019-01-08
 * Time: 23:29
 */

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;

class RbacController extends Controller {
    /**
     * @throws \Exception
     */
    public function actionIndex() {
        $auth = \Yii::$app -> authManager;

        $auth -> removeAll();

        $admin = $auth -> createRole('admin');
        $teamLead = $auth -> createRole('teamLead');
        $user = $auth -> createRole('user');
        $guest = $auth -> createRole('guest');

        try {
            $auth -> add($admin);
            $auth -> add($teamLead);
            $auth -> add($user);
            $auth -> add($guest);
        } catch (\Exception $exception) {
            echo 'Произошла ошибка при добавлении роли' . PHP_EOL;
        }

        //Создаем разрешения
        $userPermission = $auth -> createPermission('userPermission');
        $userPermission -> description = 'Простор задачи. Просмотр команды. Выполнение задач';

        $teamLeadPermission = $auth -> createPermission('teamLeadPermission');
        $teamLeadPermission -> description = 'Редактирование';

        $guestPermission = $auth -> createPermission('guestPermission');
        $guestPermission -> description = 'Никаких прав';

        $adminPermission = $auth -> createPermission('adminPermission');
        $adminPermission -> description = 'Максимальные права';

        try {
            $auth -> add($userPermission);
            $auth -> add($teamLeadPermission);
            $auth -> add($guestPermission);
            $auth -> add($adminPermission);

        } catch (\Exception $exception) {
            echo 'Произошла ошибка при добавлении разрешений' . PHP_EOL;
        }

        //Работаем с правилами наследования
        try {
            $auth -> addChild($guest, $guestPermission);
            $auth -> addChild($user, $guest);
            $auth -> addChild($user, $userPermission);
            $auth -> addChild($teamLead, $user);
            $auth -> addChild($teamLead, $teamLeadPermission);
            $auth -> addChild($admin, $teamLead);
            $auth -> addChild($admin, $adminPermission);
        } catch (\Exception $exception) {
            echo 'Произошла ошибки при добавлении разрешений ролям' . PHP_EOL;
        }

        //Добавляем роли пользователям
        try {
            $auth -> assign($admin, 1);
            $auth -> assign($teamLead, 2);
            $auth -> assign($user, 3);
            $auth -> assign($guest, 4);
        } catch (\Exception $exception) {
            echo 'Роли для конкретных пользователей назначены неверно' . PHP_EOL;
        }

        ExitCode::OK;
    }
}