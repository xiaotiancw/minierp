<?php
namespace app\admin\controller;
use util\Auth;
class Index extends \app\common\controller\BaseController
{
    public function index()
    {
        return view();
    }
    
    public function kanban() {
        echo '<h1>Modlia 生产管理系统</h1>';
    }
    
    //加载菜单导航
    public function get_tree() {
        $user = session('user');
        $pid = input('pid/d',-1);
        if($user['id'] == 1 && $user['accounts'] == 'admin'){
            //是超级管理员，得到所有的节点
            $list = db('auth_rule')->field('id,name AS url,title AS text,iconCls,pid')->where('type',1)->select();
            $tree = list_to_tree($list, 'id', 'pid', 'children');
            return json($tree);
        }else {
            
            $auth = new Auth();
            $groups = $auth->getGroups($user['id']);
            //需要加入顶级菜单pid=0
            //$list = db('auth_rule')->where('id','in',$groups[0]['rules'])->whereOr('pid',0)->field('id,name AS url,title AS text,iconCls,pid')->select();
            $list = db('auth_rule')->where('id','in',$groups[0]['rules'])->whereOr('pid',$pid)->where('type',1)->field('id,name AS url,title AS text,iconCls,pid')->select();
            //dump($list);
            $tree = list_to_tree($list);
            //dump($tree);
            return json($tree);
        }
    }
      
}
