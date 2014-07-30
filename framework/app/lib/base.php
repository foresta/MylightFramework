<?php

require_once(CONF_DIR . 'db_conf.php');

abstract class Base {
   
    protected $view;
    protected $dbConf;

    public function __construct(){
        $this->view = new View;
        $this->dbConf = new DB_Conf;
        $this->init();
    }


    abstract protected function init();

    protected function redirectTop(){
        WebUtil::redirectTo("");
        exit();
    }


}
