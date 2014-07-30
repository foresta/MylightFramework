<?php

/*
  テンプレートを表示するクラス 
*/
class View {

    private $templateName;
    private $vars;
    private $sanitizedVars;

    public function __construct(){
        $this->vars = array();
        $this->sanitizedVars = array();
    }

    public function display(){
        $this->sanitizeHtml();
        if($this->sanitizedVars) extract($this->sanitizedVars);
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

    protected function sanitizeHtml(){
        foreach($var as $k => $v){
            $this->sanitizedVars[$k] = $this->h($v);
        }
    }

    protected function h($str){
        return htmlspecialchars($str, ENT_QUOTES);
    }

    public function outputJson(){

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->vars, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
