<?php
namespace app\admin\controller;
use think\Db;

class Staff extends \app\common\controller\BaseController
{
    public function combo_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $where = ['user_id' => 0];
            $count = Db::name('staff')->where($where)->count();
            $list = Db::name('staff')->where($where)->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }

    public function data_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $number = input('number','');//工号
            $name = input('name','');//姓名
            $startDate = input('startDate','');
            $endDate = input('endDate','');
            $where = [];
            if($number){
                $where1 = [['number', 'like', "%$number%"]];
                $where = array_merge($where,$where1);
            }
            if($name){
                $where1 = [['name', 'like', "%$name%"]];
                $where = array_merge($where,$where1);
            }
            if($startDate && $endDate){
                $where3 = [['entry_date', 'between time', [$startDate, $endDate]]];
                $where = array_merge($where,$where3);
            }
            
            $count = Db::name('staff')->where($where)->count();
            $list = Db::name('staff')->where($where)->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }
    

    public function check_number() {
        if ($this->request->isAjax()) {
            $num = input('post.number');
            $count = Db::name('staff')->where('number', $num)->count();
            return ($count > 0) ? false : true;
        }
    }

    public function create() {
        if ($this->request->isPost()) {
            $data = input('post.');
//            if (!$data['dimission_date']) {
//                $data['dimission_date'] = null;
//            }
            $data['create_time'] = get_time();
            //var_dump($data);
            $result = 0;
            //$data['staff_id'] = Db::name('staff')->strict(false)->insertGetId($data);
            //$result = Db::name('staff_extend')->strict(false)->insert($data);
            //启动事务
            Db::startTrans();
            try {
                //$userId = Db::name('user')->strict(false)->insertGetId($data);
                $data['staff_id'] = Db::name('staff')->strict(false)->insertGetId($data);
                $result = Db::name('staff_extend')->strict(false)->insert($data);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($result) {
                return ['Success' => true, 'Message' => '添加成功！'];
            } else {
                return ['Success' => false, 'Message' => '添加失败！'];
            }
        } else {
            return view();
        }
    }

    public function getOne() {
        $id = input('id/d', 0);
        $model = Db::name('staff')->find($id);
        $model_extend = Db::name('staff_extend')->where('staff_id', $id)->find();
        if($model_extend){
            $res = array_merge($model, $model_extend);
        }else{
            $res = $model;
        }
        //dump($res);
        //return json_encode($model) . json_encode($model_extend);
        return json($res);
    }
    
    public function detail() {
        $id = input('id/d', 0);
        $model_extend = Db::name('staff_extend')->where('staff_id', $id)->find();
        if($model_extend){
            $this->assign('staff_extend',$model_extend);
        }
        $this->assign('id',$id);
        return view();
    }

    
    public function delete() {
        $ids = input('ids', '');
        // 根据主键删除
        //启动事务
        Db::startTrans();
        try {
            Db::name('staff')->delete($ids);
            $result = Db::name('staff_extend')->where('staff_id', 'in', $ids)->delete();
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }

        if ($result) {
            return ['Success' => true, 'Message' => '删除成功！'];
        } else {
            return ['Success' => false, 'Message' => '删除失败！'];
        }
    }

    public function edit() {
        if ($this->request->isPost()) {
            $data = input('post.');
            //启动事务
            Db::startTrans();
            $result = 0;
            try {
                //$userId = Db::name('user')->strict(false)->insertGetId($data);
                Db::name('staff')->strict(FALSE)->update($data);
                $result = Db::name('staff_extend')->where('staff_id', $data['id'])->strict(FALSE)->update($data);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            if ($result) {
                return ['Success' => true, 'Message' => '更新成功！'];
            } else {
                return ['Success' => false, 'Message' => '更新失败！'];
            }
        } else {
            $id = input('id/d', 0);
            $model_extend = Db::name('staff_extend')->where('staff_id', $id)->find();
            if($model_extend){
                $this->assign('staff_extend',$model_extend);
            }
            $this->assign('id', $id);
            return view();
        }
    }


}