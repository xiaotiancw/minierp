<?php
namespace app\admin\controller;
use think\Db;

class Staff extends \app\common\controller\BaseController
{
    public function combo_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $where = ['user_id' => 0];
            $count = db('staff')->where($where)->count();
            $list = db('staff')->where($where)->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }

    public function data_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $count = db('staff')->count();
            $list = db('staff')->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }
    

    public function check_number() {
        if ($this->request->isAjax()) {
            $num = input('post.number');
            $count = db('staff')->where('number', $num)->count();
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
            $result = 0;
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

    public function detail() {
        $id = input('id/d', 0);
        $model = db('staff')->find($id);
        $model_extend = db('staff_extend')->where('staff_id', $id)->find();
        $res = array_merge($model, $model_extend);
        //dump($res);
        //return json_encode($model) . json_encode($model_extend);
        return json($res);
    }

    public function delete() {
        $ids = input('ids', '');
        // 根据主键删除
        //启动事务
        Db::startTrans();
        try {
            db('staff')->delete($ids);
            $result = db('staff_extend')->where('staff_id', 'in', $ids)->delete();
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
            try {
                //$userId = Db::name('user')->strict(false)->insertGetId($data);
                db('staff')->update($data);
                $result = db('staff_extend')->where('staff_id', $id)->update($data);
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
            $model = db('staff_extend')->where('staff_id', $id)->find();
            $this->assign('id', $id);
            $this->assign('details', $model['details']);
            return view();
        }
    }


}