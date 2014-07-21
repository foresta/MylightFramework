<?php

static class WebUtil {

  public static function redirectTo($url){
      header("Location: " . HOST . $url);
      exit();
  }

  public static function encodeUrl($url){
      return urlencode($url);
  }

  public static function decodeUrl($url){
      return urldecode($url);
  }

  public static function currentUrl(){
      return HOST . Request::getServer("REQUEST_URI");
  }

  public static function beforeUrl(){
      return Request::getServer("HTTP_REFERER");
  }

  public static function requestType(){
      return Request::getServer('REQUEST_METHOD');
  }

  public static function isPostRequest(){
      return self::requestType() == 'POST';
  }

  public static function isGetRequest(){
      return self::requestType() == 'GET';
  }

  public static function isCorrectUrl($url){
    $header = @get_headers($url);
    return preg_match('/^HTTP¥/.*¥s+200¥s/i',$header[0]);
  }
  

}