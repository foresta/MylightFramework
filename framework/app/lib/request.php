<?php
/*
    HTTPrequestを処理するクラス
*/

class Request {

    public static function getQuery($name = ""){
        if($name){
            if(Request::issetQuery($name)){
                return htmlspecialchars($_GET[$name], ENT_QUOTES);
            }
            return null;
        }
        else{
            if(Request::issetQuery()){
                return $_GET;
            }
            return array();
        }
    }

    public static function getPost($name){
        if($name){
            if(Request::issetPost($name)){
                return htmlspecialchars($_POST[$name], ENT_QUOTES);
            }
            return null;
        }
        else{
            if(Request::issetPost()){
                return $_POST;
            }
            return array();
        }
    }

    public static function getServer($name){
        if(isset($_SERVER[$name])){
            return htmlspecialchars($_SERVER[$name], ENT_QUOTES);
        }
        return null;
    }

    public static function issetServer($name){
        return isset($_SERVER[$name]);
    }

    public static function issetQuery($name = ""){
        if($name){
            return isset($_GET[$name]);
        }
        else{
            return isset($_GET);
        }
    }

    public static function issetPost($name = ""){
        if($name){
            return isset($_POST[$name]);
        }
        else{
            return isset($_POST);
        }
    }
}
