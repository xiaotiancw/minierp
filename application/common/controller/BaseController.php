<?php
namespace app\common\controller;

class BaseController extends \think\Controller
{
    public function index() {
        return view();
    }
    
    protected function initialize() {
        //先假设存在session
        if (session('user') == null) {
            session(NULL);
            $this->redirect('login/index');
        }
    }
}