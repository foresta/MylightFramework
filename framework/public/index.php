<?php

// エラー表示の設定  
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('date.timezone', 'Asia/Tokyo');

// system DIR define
define('APP_DIR',  __DIR__ . '/../app/');
define('SYS_DIR',  APP_DIR . 'system/');
define('LIB_DIR',  APP_DIR . 'lib/');
define('CONF_DIR', APP_DIR . 'conf/');
define('VIEW_DIR', APP_DIR . 'view/');

// library file
require_once(LIB_DIR . 'request.php');
require_once(LIB_DIR . 'base.php');
require_once(LIB_DIR . 'view.php');
require_once(LIB_DIR . 'database.php');
require_once(LIB_DIR . 'mailer.php');
require_once(LIB_DIR . 'session.php');
require_once(LIB_DIR . 'OHM.php');

// UserAgentに応じて処理を切り分ける
$ua = Request::getServer('HTTP_USER_AGENT');
$carr = '';
if (strpos($ua, 'iPhone') !== false || 
    (strpos($ua, 'Android') !== false && strpos($ua, 'Mobile') !== false) ||
    strpos($ua, 'Windows Phone') !== false ||
    strpos($ua, 'BlackBerry') !== false
    ) {
    $carr = 'sp';
}
else {
    $carr = 'pc';
}

$http = "";
if ( Request::issetServer('HTTPS') && Request::getServer('HTTPS') == 'on')
    $http = "https://";
else
    $http = "http://";

// public DIR define
define('PUBLIC_DIR' , 'public/' . $carr . '/');
define('IMG_DIR', PUBLIC_DIR . 'image/');
define('JS_DIR', PUBLIC_DIR . 'js/');
define('CSS_DIR', PUBLIC_DIR . 'css/');

// システム側から使用される定数（ドキュメントルートがサーバーのルート）
define('VIEW_DIR', __DIR__ . '/public/' . $carr . '/');
define('TEMPLATE_DIR', VIEW_DIR . 'template/');
define('HOST',  $http . Request::getServer('HTTP_HOST'));
define('MAIL_TEMPLATE', __DIR__ . '/public/mail/');



// リクエストされたURIに応じてコントローラを呼び出す
$uri = Request::getServer('REQUEST_URI');

$uri = preg_replace("/\/$/", "", $uri);

// QueryStringと分ける
$uris = explode('?', $uri);
$uri = $uris[0];

if(empty($uri)){
    $uri = 'index';
}

$uri = preg_replace('/^\//', '', $uri);
$className = str_replace(' ', '_', ucwords(str_replace('/', ' ', $uri)));
$filepath = __DIR__ . '/system/app/' . $uri . '.php';
if(file_exists($filepath)){
    require_once($filepath);
    new $className;
}
else{
    header('HTTP/1.1 404 Not Found');
    require_once(__DIR__ . '/public/' . $carr . '/error.html');
}



