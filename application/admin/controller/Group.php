<?php
namespace app\admin\controller;
use app\common\controller\BaseController;
class Group extends BaseController
{   
    public function data_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if($this->request->isAjax()){
            $count = db('auth_group')->count();
            $list = db('auth_group')->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total'=>$count, 'rows'=> $list ? $list: ''];
        }
    }
    
    public function combo_grid() {
        if($this->request->isAjax()){
            //$count = db('auth_group')->count();
            $list = db('auth_group')->select();
            return json($list);
        }
    }
    
    public function create() {
        if($this->request->isPost()){
            $data = input('post.');
            $data['create_time'] = get_time();
            $result = db('auth_group')->insert($data);
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
    
    public function detail() {
        $id = input('id/d',0);
        $model = db('auth_group')->find($id);
        return json($model);
    }
    
    public function delete() {
        $ids = input('ids','');
        //dump($ids);
        //$db = db('auth_group');
        // 根据主键删除
        $result = db('auth_group')->delete($ids);
        if ($result) {
            return ['Success' => true, 'Message' => '删除成功！'];
        } else {
            return ['Success' => false, 'Message' => '删除失败！'];
        }
    }
    
    public function edit() {
        if($this->request->isPost()){
            $data = input('post.');
            //dump($data);
            $result = db('auth_group')->update($data);
            if($result){
                return ['Success'=>true,'Message'=>'更新成功！'];
            }else{
                return ['Success'=>false,'Message'=>'更新失败！'];
            }
            
        } else {
            $id = input('id/d',0);
            //$model = db('auth_group')->find($id);
            //$json = json($model);
            $this->assign('id', $id);
            return view();
        }
    }
}