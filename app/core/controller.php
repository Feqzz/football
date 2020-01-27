<?php
class controller
{
    protected $view;
    protected $model;

    public function view($view_name, $data=[])
    {
        $this->view = new view($view_name, $data);
        return $this->view;
    }

    public function model($model_name, $data=[])
    {
        if(file_exists(MODEL . DIRECTORY_SEPARATOR . $model_name . '.php'));
        require_once(MODEL . DIRECTORY_SEPARATOR . $model_name . '.php');
        $this->model = isset($data) ? new $model_name($data) : new $model_name();
    }
}