<?php
class view
{
    public function __construct($view_file, $view_data)
    {
        $this->view_file = $view_file;
        $this->view_data = $view_data;
    }

    public function render()
    {
        if(file_exists(VIEW . $this->view_file . ".php"))
            include VIEW . $this->view_file . ".php";
    }

    public function get_action()
    {
        return explode('/',$this->view_file[1]);
    }

    public $page_title;
    protected $view_file;
    protected $view_data;
}