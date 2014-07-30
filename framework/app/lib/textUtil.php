<?php

static class TextUtil {

  // 大小変換

  // 文字コード変換

  // trim

  // スネイクケース、キャメルケース

  // 全角・半角変換

    /* 
     * hash
     */
    protected function hash($str, $salt = ""){
        if($salt = "") $salt = "aslkfdhlaks";
        $password = hash("sha256", $str);
        $pass_salt = $salt . $password . $salt;
        return hash("sha256", $pass_salt);
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