<?php

namespace system\components;

use Twig_Loader_Filesystem;
use Twig_Environment;

class View {

    /**
     * @var string Current main layout
     */
    public $layout;
    /**
     * @var string Requested view of controller
     */
    public $view;

    /**
     * @var Twig_Environment Twig renderer object
     */
    private $_render;

    /**
     * View constructor
     * @param string $controllerName Requested controller name
     * @param string $layout Main layout to render views
     * @param string $view Requested view of controller
     */
    public function __construct(string $controllerName, string $layout, string $view) {
        $this->layout = $layout;
        $this->view = "{$controllerName}/{$view}";
    }

    public function render(array $params) {

        // create new Twig loader with default configurations
        $loader = new Twig_Loader_Filesystem(
            App::$current->config['components']['twig']['templates']
        );

        // initialize new renderer object
        $this->_render = new Twig_Environment($loader, array(
            'cache' => App::$current->config['components']['twig']['cache'],
        ));

        try {
            // cache view rendered content
            $viewFile = $this->_render->render("{$this->view}.twig", $params);

            // if global layout isn't false
            if ($this->layout) {
                // load layout rendered content
                $layoutFile = $this->_render->render(
                    "layouts/{$this->layout}.twig",
                    [
                        'app' => App::$current->config['app'], // global app section (config)
                        'content' => $viewFile, // pass cached rendered view content
                        'params' => $params,
                    ]
                );
            } else {
                // load single view content
                $layoutFile = $viewFile;
            }
        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }

        // print to a web page
        echo $layoutFile;
    }

}
