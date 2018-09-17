<?php

namespace system\components;

use Twig_Loader_Filesystem;
use Twig_Environment;

class Debug {

    const LOG_INFO = 'INFO';
    const LOG_ERROR = 'ERROR';
    const LOG_WARNING = 'WARNING';

    /**
     * Shows error to a web page
     * @param \Exception $error
     */
    public static function trace(\Exception $error) {
        echo $error->getMessage();
        die();
    }

    /**
     * Shows single error page
     * @param string $message
     */
    public static function error($message) {
        // create new template loader
        $loader = new Twig_Loader_Filesystem(
            App::$current->config['components']['twig']['templates']
        );

        // get renderer object
        $render = new Twig_Environment($loader);

        try {
            // show template to a web browser
            echo $render->render(
                "layouts/error.twig",
                ['error' => $message]
            );

            // stop working
            die();
        } catch (\Exception $e) {
            Debug::trace($e);
        }
    }

    /**
     * Write log messages to files
     * @param string $message
     * @param string $logType
     */
    public static function Log(string $message, $logType = Debug::LOG_INFO) {
        $today = date('Y-m-d');
        $time = date('h:i:s');
        $logFile = ROOT."/system/logs/{$today}.log";

        $data = "[{$logType} | {$time}] - {$message}\n";

        if (!file_exists($logFile)) {
            touch($logFile);
        }

        file_put_contents($logFile, $data, FILE_APPEND);
    }

}