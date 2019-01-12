<?php
namespace app\admin\controller;
use app\admin\model\User as UserModel;
use think\Db;

class User extends \app\common\controller\BaseController
{
    public function data_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $count = db('user')->count();
            $list = UserModel::with('staff')->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }
    
    public function create() {
        if ($this->request->isPost()) {
            $data = input('post.');
            $data['create_time'] = get_time();
            $data['password'] = md5($data['password']);
            $result = 0;
            // 启动事务
            Db::startTrans();
            try {
                $userId = Db::name('user')->strict(false)->insertGetId($data);
                $staff = Db::name('staff')->find($data['staff_id']);
                if($staff){
                    $result1 = Db::name('staff')->update(['id' => $data['staff_id'], 'user_id' => $userId]);
                    //新增用户权限分配
                    $result2 = Db::name('auth_group_access')->insert([
                        'uid' => $userId,
                        'group_id' => $staff['group_id']
                    ]);
                }
                $result = $result1 && $result2;
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
        $model = db('user')->find($id);
        return json($model);
    }

    public function delete() {
        $ids = input('ids', '');
        // 启动事务
        Db::startTrans();
        try {
            Db::name('staff')->where('user_id', 'in', $ids)->setField('user_id', 0);
            // 根据主键删除
            Db::name('user')->delete($ids);
            Db::name('auth_group_access')->where('uid', 'in', $ids)->delete();
            // 提交事务
            Db::commit();
            return ['Success' => true, 'Message' => '删除成功！'];
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['Success' => false, 'Message' => '删除失败！' . $e->getMessage()];
        }
    }

    public function edit() {
        if ($this->request->isPost()) {
            $data = input('post.');
            //判断当前用户是否已经关联staff_id,如果关联则原来的值清零，现在的值赋值
            // 启动事务
            Db::startTrans();
            try {
                $user = Db::name('user')->find($data['id']);
                Db::name('staff')->update(['id' => $user['staff_id'], 'user_id' => 0]);
                
                Db::name('auth_group_access')->where('uid', $data['id'])->delete();
                
                $result = Db::name('user')->strict(false)->update($data);
                $staff = Db::name('staff')->find($data['staff_id']);
                if($staff){
                    $result = Db::name('staff')->update(['id' => $data['staff_id'], 'user_id' => $data['id']]);
                    //新增用户权限分配
                    $result2 = Db::name('auth_group_access')->insert([
                        'uid' => $user['id'],
                        'group_id' => $staff['group_id']
                    ]);
                }
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
            $this->assign('id', $id);
            return view();
        }
    }

//    private function insert_test(){
//        $data = [];
//        for($i = 1; $i <= 30; $i++){
//            $user['accounts'] = 'test'.$i;
//            $user['password'] = 'e10adc3949ba59abbe56e057f20f883e';
//            $user['state'] = '正常';
//            $data[] = $user;
//        }
//        db('user')->insertAll($data);
//    }
}