<?php
/*
namespace app\commands;

use app\daemons\EchoServer;
use yii\console\Controller;

class ServerController extends Controller
{
    public function actionStart($port = null)
    {
        $server = new EchoServer();
        if ($port) {
            $server->port = $port;
        }
        $server->start();
    }
}

*/
namespace app\commands;

use app\daemons\ChatServer;
use consik\yii2websocket\WebSocketServer;
use yii\console\Controller;

class ServerController extends Controller
{
    public function actionStart()
    {
        $server = new ChatServer();
        $server->port = 8080; //This port must be busy by WebServer and we handle an error

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN_ERROR, function($e) use($server) {
            echo "Error opening port " . $server->port . "\n";
            $server->port += 1; //Try next port to open
            $server->start();
        });

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN, function($e) use($server) {
            echo "Server started at port " . $server->port;
        });

        $server->start();
    }
}