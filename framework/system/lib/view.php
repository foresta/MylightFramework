<?php

/*
  テンプレートを表示するクラス 
*/
class View {

    private $templateName;
    private $vars;

    public function __construct(){
        $this->vars = array();
    }

    public function display(){
        if($this->vars) extract($this->vars);
        require_once($this->templateName);
    }

    public function setVar($name, $var){
        $this->vars[$name] = $var;
        return $this;
    }

    public function setTemplate($name){
        $this->templateName = VIEW_DIR . $name . '.html';
        return $this;
    }

    public function setPhpTemplate($name){
        $this->templateName = VIEW_DIR . $name . '.php';
        return $this;
    }

    public function outputJson(){

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->vars, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
