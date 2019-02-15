<?php
namespace app\admin\controller;
use app\admin\model\User as UserModel;
use think\Db;

class User extends \app\common\controller\BaseController
{
    public function data_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $count = db('user')->count();
            
            $list = UserModel::with('groups')->order([$sort => $order])->limit($rows)->page($page)->select();
            //dump($list);
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }
    
    public function create() {
        if ($this->request->isPost()) {
            $data = input('post.');
            //$data['create_time'] = get_time();
            $data['password'] = md5($data['password']);
            // 启动事务
            Db::startTrans();
            try {
                $userId = Db::name('user')->strict(false)->insertGetId($data);
                //新增用户权限分配
                $result = Db::name('auth_group_access')->insert([
                    'uid' => $userId,
                    'group_id' => $data['group_id']
                ]);
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
        $user = UserModel::get($id);
        $groups = $user->groups;
        foreach ($groups as $group) {
            // 输出用户的角色名
            $user['group_id'] = $group->id;
        }
        return json($user);
    }

    public function delete() {
        $ids = input('ids', '');
        $arr = explode(',', $ids);
        if(in_array('1',$arr)){
            return ['Success' => false, 'Message' => '管理员不能删除！'];
        }
        // 启动事务
        Db::startTrans();
        try {
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
                //$user = Db::name('user')->find($data['id']);
                Db::name('auth_group_access')->where('uid', $data['id'])->delete();
                
                Db::name('user')->strict(false)->update($data);
                $result = Db::name('auth_group_access')->insert([
                        'uid' => $data['id'],
                        'group_id' => $data['group_id']
                    ]);
                
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
}