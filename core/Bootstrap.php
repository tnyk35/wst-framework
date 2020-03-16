<?php
namespace core;

// コントローラーを読み込む
class Bootstrap
{
    protected $controllerName;
    protected $actionName;
    protected $request;

    public function __construct()
    {
        $this->controllerName = DEFAULT_CONTROLLER;
        $this->actionName = DEFAULT_ACTION;
        $this->request = array();
    }

    // ディスパッチ
    public function dispatch()
    {
        if (isset($_GET['url'])) {
            $params = explode('/', $_GET['url']);
            $pagedKey = array_search('paged', $params);

            // ページングあり
            $params = $this->_checkPaging($params, $pagedKey);

            // アクション処理
            $params = $this->_setAction($params);
        }

        // POST時はrequestに値を入れる
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->request['POST'] = $_POST;
        }

        $this->_checkControllerFile($this->controllerName);

        $this->_setInstance($this->actionName);
    }

    // コントローラーとアクションを指定
    private function _setAction($params) {
        if (isset($params[0]) && $params[0] !== '') {
            $this->controllerName = $params[0];

            // アクションの設定
            if (isset($params[1]) && $params[1] !== '') {
                $this->actionName = $params[1];
            }

            // パラメーターの設定
            for ($i = 2; $i < count($params); $i++) {
                if (isset($params[$i]) && $params[$i] !== '') {
                    $this->request['params'][] = $params[$i];
                }
            }
        }
    }

    // ページング有無
    private function _checkPaging($params, $key) {
        if ($key !== false) {
            if (isset($params[$key + 1]) && $params[$key + 1] !== '') {
                $this->request['paged'] = (int)$params[$key + 1];
            } else {
                $this->request['paged'] = 1;
            }
            unset($params[$key],$params[$key + 1]);
        }
        return $params;
    }

    // コントローラファイルがあるか確認
    private function _checkControllerFile($name) {
        global $F;

        $controllerFile = '/controller/' . $F->toCamelCase($name) . 'Controller.php';

        if (file_exists(INCLUDE_APP_BASE_DIR . $controllerFile)) {
            require_once INCLUDE_APP_BASE_DIR . $controllerFile;
        } else {
            throw new \Exception($F->toCamelCase($name) . 'Controller not Found');
            exit;
        }
    }

    // コントローラーをインスタンス化
    private function _setInstance($action) {
        global $F;

        $controllerClassName = "controller\\" . $F->toCamelCase($this->controllerName) . 'Controller';
        $controller = new $controllerClassName($this->request); // ここでrequestを設定

        if (method_exists($controller, $action)) {
            // アクション実行
            $controller->$action();
        } else {
            throw new \Exception($action . ' action not Found in ' . $F->toCamelCase($this->controllerName) . 'Controller');
            exit;
        }
    }
}