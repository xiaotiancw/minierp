<?php
namespace app\common\controller;
use think\Request;
use util\Auth;
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
        $user = session('user');
        if ($user['id'] != 1 && $user['accounts'] != 'admin') {

            //$authName = Request::module().'/'.Request::controller().'/'.Request::action;
            $authName = request()->controller() . '/' . request()->action();
            if (!$authName == 'index/index') {
                //权限认证控制
                $Auth = new Auth();
                if (!$Auth->check($authName, session('user')['id'])) {
                    exit('<p style="color:red;padding:10px;font-size:14px;">对不起，您没有权限访问此模块！</p>');
                }
            }
        }
    }
}