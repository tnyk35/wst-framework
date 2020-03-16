<?php
namespace controller;

use model\DefaultModel;

class DefaultController extends Controller
{
    public function index()
    {
        $model = new DefaultModel();
        $tests = $model->select();

        // Viewファイル
        $this->view('index', compact('tests'));
    }

    public function show()
    {
        echo "show";

        $this->view('show');
    }
}