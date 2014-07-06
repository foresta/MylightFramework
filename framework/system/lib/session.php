<?php

class Session {

  private static $_instance = null;

  private function __construct(){
      session_start();
      session_regenerate_id();
  }

  public static function getInstance(){
      if(is_null(self::$_instance)){
            self::$_instance = new self;
      }
      return self::$_instance;
  }

  public function get($name){
      if($this->isExist($name)){
        return $_SESSION[$name];
      }
      else {
        return null;
      }
  }

  public function set($name, $value){
      $_SESSION[$name] = $value;
  }

  public function remove($name = ""){
      if($name == ""){
          $_SESSION = array();
      }
      else {
          if($this->isExist($name))
              unset($_SESSION[$name]);
      }
  }

  public function destroy(){
      if (!empty($_SESSION))
        $this->remove();
      @session_destroy();
      $this->_instance = null;
  }

  public function isExist($name){
      return isset($_SESSION[$name]);
  }

  public function removeSessIdFromCookie(){
    if (isset($_COOKIE["PHPSESSID"])) {
        setcookie("PHPSESSID", '', time() - 1800, '/');
    }
  }

}