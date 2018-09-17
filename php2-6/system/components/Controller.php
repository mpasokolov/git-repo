<?php

namespace system\components;

abstract class Controller extends BaseObject {

    /**
     * @var string Controller name
     */
    public $name;

    /**
     * @var string Controller default layout name
     */
    public $layout = 'main';

    /**
     * @var string Controller default action
     */
    public $defaultAction = 'index';

    /**
     * @var string Controller user role
     */
    public $isAdmin = false;

    /**
     * Controller constructor.
     */
    public function __construct() {
        // get controller class name
        $class = explode('\\', static::class);

        // remove 'Controller' word from class name
        $this->name = strtolower(str_replace(
            'Controller',
            '',
            array_pop($class)
        ));
    }

    /**
     * @param string $view View name to render
     * @param array $params Array with key-value data
     */
    public function render(string $view, array $params = []) {
        if (App::$current->request->isAjax()) {
            $this->renderJson($params);
        } else {
            $view = new View(
                $this->name, //controller name
                $this->layout, //main layout
                $view //view name
            );

            $view->render($params);
        }
    }

    /**
     * @param $data Key-value data array
     */
    public function renderJson($data) {
        // return rendered response
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * @param string $actionName Name of the action method
     * @param array $params Key-value data array
     * @return mixed
     */
    public function executeAction($actionName, $params) {
        // if requested method exists
        if (method_exists($this, $actionName)) {
            // return method call result
            return $this->$actionName(...array_values($params)); // extract array values
        } else {
            Debug::error("No routes for action [{$actionName}]");
        }
    }
}
