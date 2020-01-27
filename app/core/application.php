<?php
class application
{
    protected $controller = 'login_controller';
    protected $action = 'index';
    protected $prams = [];

    public function __construct()
    {
        $this->prepare_URL();
        if(file_exists(CONTROLLER . $this->controller . ".php"))
        {
            $this->controller = new $this->controller;
            if(method_exists($this->controller,$this->action))
                call_user_func_array([$this->controller, $this->action], $this->prams);

        }
    }

    protected function prepare_URL()
    {
        if (isset($_SERVER['REQUEST_URI']))
        {
            $request = trim($_SERVER['REQUEST_URI'], '/');
            if (!empty($request))
            {
                $url = explode('/', $request);
                $this->controller = isset($url[0]) ? $url[0] . '_controller' : 'login_controller';
                $this->action = isset($url[1]) ? $url[1] : 'index';
                unset($url[0], $url[1]);
                $this->prams = !empty($url) ? array_values($url) : [];
            }
        }
    }
}