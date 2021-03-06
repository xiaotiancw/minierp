<?php
namespace app\admin\controller;
use app\common\controller\BaseController;
use think\Db;
class Auth extends BaseController {
    
    public function data_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if($this->request->isAjax()){
            $count = Db::name('auth_group')->count();
            $list = Db::name('auth_group')->order([$sort => $order])->limit($rows)->page($page)->select();
            //对rules转换
            foreach ($list as $key => $value) {
                $titleArray = Db::name('auth_rule')->field('title')->where('id','in',$value['rules'])->select();
                if ($titleArray) {
                    $title = '';
                    foreach ($titleArray as $key2 => $value2) {
                        $title .= $value2['title'] . ',';
                    }
                    $list[$key]['title_rules'] = substr($title, 0, -1);
                }
            }
            return ['total'=>$count, 'rows'=> $list ? $list: ''];
        }
    }    
    
    
    public function create() {
        if($this->request->isPost()){
            $data = input('post.');
            $data['create_time'] = get_time();
            $result = Db::name('auth_group')->insert($data);
            if($result){
                return ['Success'=>true,'Message'=>'添加成功！'];
            }else{
                return ['Success'=>false,'Message'=>'添加失败！'];
            }
            //dump($data);
        } else {
            return view();
        }
        
    }
    
    public function get_auth_rule($rules = '') {
        $list = Db::name('auth_rule')->field('id,name AS url,title AS text,iconCls,pid')->select();
        if(!empty($rules)){
            //得到需要选择的数组
            $rules_array = explode(',', $rules);
            foreach ($list as $key => $value) {
                //让权限设置选定状态
                if (in_array($value['id'], $rules_array)) {
                    $list[$key]['checked'] = true;
                }
            }
        }
        $tree = list_to_tree($list);
        return json($tree);
    }
    
    public function edit() {
        if($this->request->isPost()){
            $data = input('post.');
            //dump($data);
            $result = Db::name('auth_group')->update($data);
            if($result){
                return ['Success'=>true,'Message'=>'更新成功！'];
            }else{
                return ['Success'=>false,'Message'=>'更新失败！'];
            }
            
        } else {
            $id = input('id/d',0);
            $model = Db::name('auth_group')->find($id);
            $this->assign('vo', $model);
            //$this->assign('id', $id);
            return view();
        }
    }
    
    public function delete() {
        $ids = input('ids','');
        // 根据主键删除
        $result = db('auth_group')->delete($ids);
        if ($result) {
            return ['Success' => true, 'Message' => '删除成功！'];
        } else {
            return ['Success' => false, 'Message' => '删除失败！'];
        }
    }
}