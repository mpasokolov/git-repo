<?php

namespace system\components;

/**
 * Class Router
 * @package system\components
 *
 */
class Router extends BaseObject {

    /**
     * @var Controller Current controller
     */
    private $_controller = false;

    /**
     * @var string Current controller action
     */
    private $_action = false;

    /**
     * Router constructor
     * @param string $route
     */
    public function __construct(string $route) {
        // split current route from request
        $route = explode('/', $route);
        // get default directory to load classes
        $namespace = App::$current->config['controllerNamespace'];

        // app\controllers\NameController
        $controllerName = $namespace . '\\' . Formatter::fromRoute($route[0]) . "Controller";

        try {
            // try to create a new instance of controller class
            $this->_controller = new $controllerName();

            // set controller action name
            $this->_action = 'action'; //actionIndex, actionSite
            $this->_action .= (isset($route[1])) ? Formatter::fromRoute($route[1]) : $this->_controller->defaultAction;

        } catch (\Exception $e) {
            Debug::trace($e);
        }
    }

    public function route() {
        try {
            // call requested action method and pass $_GET data
            $this->_controller->executeAction($this->_action, App::$current->request->get());
        } catch (\Exception $e) {
            Debug::trace($e);
        }
    }

    /**
     * @return Controller Controller object via magic method
     */
    public function getController() {
        return $this->_controller;
    }

    /**
     * @return string Action name via magic method
     */
    public function getAction() {
        return $this->_action;
    }

}
