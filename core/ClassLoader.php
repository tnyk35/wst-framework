<?php

/**
 * Classが定義されていない場合にファイルを探すクラス
 * 参考：https://qiita.com/misogi@github/items/8d02f2eac9a91b4e6215
 */
class ClassLoader
{
    private static $dirs;

    /**
     * クラスがない場合に呼び出す
     * @param  string $class 名前空間など含んだクラス名
     * @return bool 成功すればtrue
     */
    public static function loadClass($class)
    {
        foreach (self::directories() as $directory) {
            $search = '/(controller|model)\\\/i';
            $class = preg_replace($search, "", $class);
            $fileName = "{$directory}/{$class}.php";

            if (is_file($fileName)) {
                require $fileName;

                return true;
            }
        }
    }

    /**
     * ディレクトリリスト
     * @return array フルパスのリスト
     */
    private static function directories()
    {
        if (empty(self::$dirs)) {
            self::$dirs = INCLUDE_CLASS_DIR;
        }

        return self::$dirs;
    }
}

// これを実行しないとオートローダーとして動かない
spl_autoload_register(array('ClassLoader', 'loadClass'));