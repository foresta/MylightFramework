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

    /* password hash */
    protected function hash($str, $salt = ""){
        if($salt = "") $salt = "aslkfdhlaks";
        $password = hash("sha256", $str);
        $pass_salt = $salt . $password . $salt;
        return hash("sha256", $pass_salt);
    }

    protected function isPost(){
        return Request::getServer('REQUEST_METHOD') == "POST";
    }

    protected function redirectTo($url){
        header("Location: " . HOST . $url);
        exit();
    }


    protected function redirectTop(){
        $this->redirectTo("");
        exit();
    }

    /*
     *  引数で指定した桁数のランダム文字列を生成する
     */
    protected function generateToken($max = 10){
        $rand_str = "";
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        for($i=0; $i<$max; $i++){
            $rand_str .= $str[mt_rand(0, strlen($str)-1)];
        }
        return $rand_str;
    }
}
