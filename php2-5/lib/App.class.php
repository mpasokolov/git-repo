<?php

class App 
{
    public static function Init() 
    {
        date_default_timezone_set('Europe/Moscow');
        db::getInstance()->Connect(Config::get('db_user'), Config::get('db_password'), Config::get('db_base'));

        if (php_sapi_name() !== 'cli' && isset($_SERVER) && isset($_GET)) {
            self::web(isset($_GET['path']) ? $_GET['path'] : '');
        }
    }

    protected static function web($url)
    {
        $url = explode("/", $url);
        if (!empty($url[0])) {
            $_GET['page'] = $url[0];
            if (!empty($url[1])) {
                if (is_numeric($url[1])) {
                    $_GET['id'] = $url[1];
                } else {
                    $_GET['action'] = $url[1];
                }
                if (!empty($url[2])) {
                    $_GET['id'] = $url[2];
                }
            }
        }
        else{
            $_GET['page'] = 'Index';
        }
        
        if (isset($_GET['page'])) {
            $controllerName = ucfirst($_GET['page']) . 'Controller';
            $methodName = isset($_GET['action']) ? $_GET['action'] : 'index';
            
            if ($methodName == '/' || empty($methodName)) {
                $methodName = 'index';
            }

            $controller = new $controllerName();

            $view = $controller->view . '/' . $methodName . '.html';

            $data = [
                'content_data' => $controller->$methodName($_GET),
                'title' => $controller->title,
                'history' => self::userHistory($controller -> title),
            ];

            if (!isset($_GET['asAjax'])) {
                $loader = new Twig_Loader_Filesystem(Config::get('path_templates'));
                $twig = new Twig_Environment($loader);
                $template = $twig->loadTemplate($view);

                echo $template->render($data);
            } else {
                echo json_encode($data);
            }
        }
    }

    private static function userHistory($page) {
      if (!Lk::alreadyLoggedId() && !Lk::checkAuthWithCookie()){
        return false;
      }

      $history = $_SESSION['user']['history'] ?? [];

      if ($history[count($history) - 1] === $page) { return array_reverse($history); }

      if (count($history) > 4) {
          array_shift($history);
          array_push($history, $page);
      } else {
          array_push($history, $page);
      }

      $_SESSION['user']['history'] = $history;

      return array_reverse($history);
    }
}
