<?php
namespace core;

class Functions
{
    // サニタイズ関数
    public function h($data)
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    // デバッグ関数
    public function debug($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }

    // 未ログイン時のみ実行
    public function requireUnloginedSession($db)
    {
        // セッション開始
        @session_start();
        // ログインしていればindex.phpに遷移
        if ($this->isLogin($db)) {
            header('Location: ./index.php');
            exit;
        }
    }

    // ログイン時のみ実行
    public function requireLoginedSession($db)
    {
        // セッション開始
        @session_start();
        // ログインしていなければlogin.phpに遷移
        if (!$this->isLogin($db)) {
            header('Location: ./login.php');
            exit;
        }
    }

    // ログイン済みかどうか
    public function isLogin($db)
    {
        if (isset($_SESSION["EMAIL"])) {
            $user = $db->getUser($_SESSION["EMAIL"]);
            return isset($user) && (hash('sha256', $_SESSION["TOKEN"]) === $user["token"]);
        } else {
            return false;
        }
    }

    // ランダムでログイントークンを生成
    public function generateLoginToken()
    {
        return uniqid(rand(0, 1000000000000));
    }

    // CSRFトークンの生成
    public function generateToken()
    {
        // セッションIDからハッシュを生成
        return hash('sha256', session_id());
    }

    // CSRFトークン
    public function validateToken($token)
    {
        return $token === $this->generateToken();
    }


    // キャメルケースに変換
    public function toCamelCase($string)
    {
        $string = str_replace('-', ' ', $string);
        $string = str_replace('_', ' ', $string);
        $string = ucwords(strtolower($string));
        $string = str_replace(' ', '', $string);
        return $string;
    }
}
global $F;
$F = new Functions();
