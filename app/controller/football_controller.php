<?php
class football_controller extends controller
{
    public function __construct()
    {
        if(empty($_SESSION["loggedin"])) header("location: /");
    }

    public function index()
    {
        $this->view("football" . DIRECTORY_SEPARATOR . "index", []);
        $this->view->page_title = $this->sites[0];
        $this->view->render();
    }

    public function overview()
    {
        $this->model('overview');
        $this->view("football" . DIRECTORY_SEPARATOR . "overview");
        $this->view->page_title = $this->sites[0];
        $this->view->model = $this->model;
        $this->view->render();
    }

    public function fixtures()
    {
        $this->model('fixtures');
        $this->view("football" . DIRECTORY_SEPARATOR . "fixtures");
        $this->view->page_title = $this->sites[1];
        $this->view->model = $this->model;
        $this->view->render();
    }

    public function standings()
    {

    }

    public function leaderboard()
    {
        $this->model('leaderboard');
        $this->view("football" . DIRECTORY_SEPARATOR . "leaderboard");
        $this->view->page_title = $this->sites[2];
        $this->view->model = $this->model;
        $this->view->render();
    }

    public function ajax($a='')
    {
        $this->model('ajax',$a);
    }

    public $sites = array
    (
        0 => array
        (
            'name' => 'overview',
            'display_name' => 'Overview'
        ),
        1 => array
        (
            'name' => 'fixtures',
            'display_name' => 'Fixtures'
        ),
        2 => array
        (
            'name' => 'leaderboard',
            'display_name' => 'Leaderboard'
        )
    );

}