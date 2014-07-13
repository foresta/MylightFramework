<?php

// スーパーグローバル変数の参照

// $v = $_SERVER['key'];
$v = ohm()->server('key')->map();

// $v = $_GET['key'];
$v = ohm()->get('key')->map();

// $v = $_POST['key'];
$v = ohm()->post('key')->map();

// $v = $_FILES['key'];
$v = ohm()->files('key')->map();

// $v = $_COOKIE['key'];
$v = ohm()->cookie('key')->map();

// $v = $_SESSION['key'];
$v = ohm()->session('key')->map();

// $v = $_ENV['key'];
$v = ohm()->env('key')->map();


// デフォルト値

// $v = isset($_GET['key']) ? $_GET['key'] : 9;
$v = ohm()->get('key')->defval(9)->map();


// キャスト

// 例えば $_GET['id'] は整数文字列が期待されるとき
// $v = is_numeric($_GET['id']) ? intval($_GET['id'] : null;
$v = ohm()->get('id')->integer()->map();

// $v = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 9;
$v = ohm()->get('id')->defval(9)->integer()->map();
