<?php
namespace app\daemons;

use consik\yii2websocket\events\WSClientMessageEvent;
use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\WebSocketServer;
use Ratchet\ConnectionInterface;
use SplQueue;

class ChatServer extends WebSocketServer
{
    public $arr;

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_CLIENT_CONNECTED, function (WSClientEvent $e) {
            $e->client->name = null;
            if (!$this->arr) {
                $this -> arr = new SplQueue();
                $this -> arr -> setIteratorMode(SplQueue::IT_MODE_DELETE);
            }
        });
    }


    protected function getCommand(ConnectionInterface $from, $msg)
    {
        $request = json_decode($msg, true);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
    }

    public function commandChat(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);
        $result = ['message' => ''];

        if (!$client->name) {
            $result['message'] = 'Set your name';
        } elseif (!empty($request['message']) && $message = trim($request['message']) ) {
            foreach ($this->clients as $chatClient) {
                $this->arr->enqueue($request['message']);
                $chatClient->send( json_encode([
                    'type' => 'chat',
                    'from' => $client->name,
                    'message' => $message
                ]) );
            }
        } else {
            $result['message'] = 'Enter message';
        }

        $client->send( json_encode($result) );
    }

    public function commandSetName(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);
        $result = ['message' => 'Ваше имя успешно сохранено'];

        if (!empty($request['name']) && $name = trim($request['name'])) {
            $usernameFree = true;
            foreach ($this->clients as $chatClient) {
                if ($chatClient != $client && $chatClient->name == $name) {
                    $result['message'] = 'Это имя уже занято другим пользователем';
                    $usernameFree = false;
                    break;
                }
            }

            if ($usernameFree) {
                $client->name = $name;
            }
        } else {
            $result['message'] = 'Недопустимое имя';
        }

        $client->send( json_encode($result) );
    }

    public function commandGetLast(ConnectionInterface $client, $msg) {
        foreach ($this->clients as $chatClient) {
            $message = 'Очередь пуста. Удалять нечего!';
            $type = 'empty';

            if (!$this->arr->isEmpty()) {
                $message = $this->arr->dequeue();
                $type = 'get';
            }

            $chatClient->send( json_encode([
                'type' => $type,
                'message' => $message
            ]) );
        }
    }

}