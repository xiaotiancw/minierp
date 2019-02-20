<?php
namespace app\admin\controller;

class Login extends \think\Controller
{
    public function index() {
        if($this->request->post()){
            $username = trim(input('post.username/s')); //强制转换为字符串类型
            $password = md5(trim(input('post.password/s')));

            if (!$username || !$password) {
                $data['Success'] = false;
                $data['msg'] = '用户名或密码为空';
                return json($data);
            }
            $user = db('user')->where('accounts', $username)->find();
            //密码校验
            if ($user) {
                if ($password != $user['password']) {
                    //返回错误信息
                    $data['Success'] = false;
                    $data['msg'] = '用户密码错误';
                    return json($data);
                } else {
                    $user['password'] = null;//去掉密码
                    session('user', $user);
                    //返回成功信息
                    $data['Success'] = true;
                    $data['msg'] = '登录成功！';
                    $data['url'] = url('admin/index/index');
                    return json($data);
//                    $this->success('登录成功', url('admin/index/index'));
                }
            } else {
                //返回错误信息
                $data['Success'] = false;
                $data['msg'] = '用户不存在';
                return json($data);
                //$this->error('用户不存在')
            }
        }else{
            return view();
        }
    }
    
    public function logout() {
        session('user',NULL);
        $this->redirect('admin/login/index');
    }

}