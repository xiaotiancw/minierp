<?php
namespace app\admin\controller;

class Index extends \app\common\controller\BaseController
{
    public function index()
    {
        return view();
    }
    
    public function kanban() {
        echo 'ok';
    }
    
    //加载菜单导航
    public function get_tree() {
        //得到所有的节点
        $list = db('auth_rule')->field('id,name AS url,title AS text,iconCls,pid')->select();

        $tree = list_to_tree($list, 'id', 'pid', 'children');
        return json($tree);

//        $auth = new Auth();
//        $groups = $auth->getGroups(1);
//        //需要加入顶级菜单pid=0
//        $list = db('auth_rule')->where('id','in',$groups[0]['rules'])->whereOr('pid',0)->field('id,name AS url,title AS text,iconCls,pid')->select();
//        //dump($list);
//        $tree = list_to_tree($list);
        //dump($tree);
        //return json($tree);
    }

}
