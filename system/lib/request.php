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
        }
        else{
            if(Request::issetQuery()){
                return $_GET;
            }
        }
        return "";
    }

    public static function getPost($name){
        if($name){
            if(Request::issetPost($name)){
                return htmlspecialchars($_POST[$name], ENT_QUOTES);
            }
        }
        else{
            if(Request::issetPost()){
                return $_POST;
            }
        }
        return "";
    }

    public static function getServer($name){
        if(isset($_SERVER[$name])){
            return htmlspecialchars($_SERVER[$name],ENT_QUOTES);
        }
    }

    public static function issetServer($name){
        return isset($_SERVER[$name]);
    }

    public static function issetQuery($name=""){
        if($name){
            return isset($_GET[$name]);   
        }
        else{
            return isset($_GET);
        }
    }

   
    public static function issetPost($name=""){
        if($name){
            return isset($_POST[$name]);   
        }
        else{
            return isset($_POST);
        }
    }
} 
