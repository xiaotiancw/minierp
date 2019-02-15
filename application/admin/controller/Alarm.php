<?php
namespace app\admin\controller;
use think\Db;
class Alarm extends \app\common\controller\BaseController
{
    public function data_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $count = Db::name('alarm')->count();
            $list = Db::name('alarm')->whereNull('state')->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }
    
    /**
     * 处理通知
     */
    public function deal() {
        $ids = input('ids', '');
        if (empty($ids)) {
            return ['Success' => false, 'Message' => '处理失败！'];
        }
        $data['state'] = '已处理';
        $data['treat_person'] = session('user')['accounts'];
        $data['treat_time'] = get_time();
        $result = Db::name('alarm')->where('id', 'in', $ids)->update($data);
        if ($result) {
            return ['Success' => true, 'Message' => '处理成功！'];
        } else {
            return ['Success' => false, 'Message' => '处理失败！'];
        }
    }

    public function alarm() {
        $id = input('id/d',0);
        $record = Db::name('record')->find($id);
        $obj = Db::name('alarm')->where('recordid',$record['recordid'])->find();
        if($obj){
            return ['Success' => false, 'Message' => '已经通知处理！'];
        }
        $record['id'] = NULL;
        $record['create_person'] = session('user')['accounts'];
        $result = Db::name('alarm')->strict(FALSE)->insert($record);
        if ($result) {
            return ['Success' => true, 'Message' => '通知成功！'];
        } else {
            return ['Success' => false, 'Message' => '通知失败！'];
        }
    }
}