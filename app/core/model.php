<?php
require_once(CORE . "database.php");
require_once(CORE . "data_handler.php");
class model
{
    public function __construct()
    {
        $this->db = new database;
        $this->dh = new data_handler;
    }
    protected $db;
    protected $dh;
}