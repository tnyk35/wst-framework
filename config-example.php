<?php
// 1. 本番環境は以下コメントアウトを削除
// error_reporting(0);
// ini_set("display_errors", 0);

// 2. 本番環境では以下の値をコメントアウト
error_reporting(E_ALL);
ini_set("display_errors", 1);

// データベース周りの値を設定する
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'db_name');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

// ソフトデリートON/OFF
define('IS_SOFT_DELETE', true);

// 1ページあたりの最大表示数
define('MAX_COUNT_PER_PAGE', 3);

// デフォルトのControllerとActionを設定
define('DEFAULT_CONTROLLER', 'Default');
define('DEFAULT_ACTION', 'index');

// クラスファイルのベースディレクトリ
define('INCLUDE_APP_BASE_DIR', __DIR__ . '/');

// クラスを読み込ませるディレクトリを追加
const INCLUDE_CLASS_DIR = array(
    INCLUDE_APP_BASE_DIR . '/controller',
    INCLUDE_APP_BASE_DIR . '/model'
);