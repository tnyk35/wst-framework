<?php
namespace controller;

class Controller
{

    protected $controller;
    protected $request;

    public function __construct($request)
    {
        $this->controller = $this->_getControllerName();
        $this->request = $request;
    }

    // Viewがcontroller内でセット
    // もし読み込めなかった場合はエラーになる
    protected function view($action, $X = NULL)
    {
        global $F;

        $viewFile = '/view/' . $this->controller . '/';
        if ($action !== '') {
            $viewFile .= $action . '.php';
        }

        // Viewがあるかどうか
        if (file_exists(INCLUDE_APP_BASE_DIR . $viewFile)) {
            require_once INCLUDE_APP_BASE_DIR . $viewFile;
        } else {
            throw new \Exception($viewFile . ' view files not Found');
            exit;
        }
    }

    // コントローラー名取得
    private function _getControllerName()
    {
        global $F;
        return $F->toCamelCase(preg_replace("/controller\\\(.+)Controller/", '$1', get_class($this)));
    }
}