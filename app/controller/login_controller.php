<?php
class login_controller extends controller
{
    public function index()
    {
        $this->login();
    }

    public function login()
    {
        $this->view("login" . DIRECTORY_SEPARATOR . "index", []);
        $this->view->page_title = "Login";
        $this->view->render();
    }

    public function ajax($a='')
    {
        $this->model('ajax',$a);
    }
}