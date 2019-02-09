<?php

namespace console\models;

use common\models\History;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use yii\base\Model;
use yii\helpers\Json;

class Ratchet extends Model implements MessageComponentInterface {

    private $users;
    private $history = [];

    public function __construct() {
        $this -> users = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "New connection! ({$conn -> resourceId})\n";
        $this -> users -> attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this -> users as $user) {
            if ($from !== $user) {
                // The sender is not the receiver, send to each client connected
                $user -> send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        echo "Connection {$conn -> resourceId} has disconnected\n";
        $this -> users -> detach($conn);
        $conn -> close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e -> getMessage()}\n";
        $conn -> close();
    }
}