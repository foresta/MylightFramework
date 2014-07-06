<?php

class Mailer {

    private $_message = ""; 
    const EXT = ".txt";

    // テンプレートをセット
    public function setTemplate($fileName){
        $this->_message = file_get_contents(MAIL_TEMPLATE . $fileName . self::EXT);
        return $this;
    }

    public function setVar($name, $value){
        $this->_message = preg_replace("/{" . $name . "}/" , $value, $this->_message);
        return $this;
    }

    // メール送信
    public function sendMail($to, $subject, $message = ""){
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        if($message=="") $message = $this->_message;

        return mb_send_mail($to, $subject, $message);
    }

}