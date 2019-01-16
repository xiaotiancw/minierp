<?php
namespace app\admin\controller;
use think\Db;
class Assign extends \app\common\controller\BaseController
{
    public function index($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
            $where = [
               [ 'mtr_treatstate','=','已处理'],
               [ 'end_treatstate','=','已处理'],
            ];
            $count = Db::name('record')->where($where)->whereNull('delete_time')->count();
            $list = Db::name('record')->where($where)->whereNull('delete_time')->order([$sort => $order])->limit($rows)->page($page)->select();
            
            $device_list = Db::name('device')->select();
            
            $this->assign('record_list', $list);
            $this->assign('device_list', $device_list);
            $this->assign('record_count', $count);
            
            return view();
    }
}