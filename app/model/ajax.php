<?php
require_once(MODEL . "fixtures.php");
require_once(MODEL . "login.php");
require_once(MODEL . "choose_league.php");

class ajax
{
    public function __construct($function='')
    {
        if (isset($function))
        {
            switch ($function)
            {
                case "bet":
                    $this->model = new fixtures();
                    self::bet();
                    break;
                case "login":
                    $this->model = new login();
                    self::login();
                    break;
                case "choose_league":
                    $this->model = new choose_league();
                    self::choose_league();
                    break;
                case "register":
                    $this->model = new login();
                    self::register();
                    break;
                case "logout":
                    self::logout();
                    break;
            }
        }
    }

    public function bet()
    {
        $user_id = $_POST['user_id'];
        $match_id = $_POST['match_id'];
        $bet = $_POST['bet'];
        $bet_id = isset($_POST['bet_id']) ? $_POST['bet_id'] : null;
        $this->model->set_user_bet($user_id, $match_id, $bet, $bet_id);
    }

    public function login()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $this->model->valid_login($username, $password);
    }

    public function choose_league()
    {
        $_SESSION['league'] = $this->model->get_league_info($_POST['league_id']);
        $response['status'] = "success";
        echo json_encode($response);
    }

    public function register()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $this->model->register($username, $password);
    }

    public function logout()
    {
        session_start();
        $_SESSION = array();
        session_destroy();
        header("location: /");
    }
    private $model;
};