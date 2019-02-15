<?php
namespace app\home\controller;
use think\Db;
class Index extends \think\Controller {

    public function index() {
        return view();
    }

    public function data_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $recordId = input('recordid', ''); //产品型号
            $deadline = input('deadline', '');
            $startDate = input('startDate', '');
            $endDate = input('endDate', '');
            $where = [];
            if ($recordId) {
                $where1 = [['productid', 'like', "%$recordId%"]];
                $where = array_merge($where, $where1);
            }
            if ($deadline) {
                $where2 = [['deadline', 'between time', [$deadline, $deadline]]];
                $where = array_merge($where, $where2);
            }
            if ($startDate && $endDate) {
                $where3 = [['deadline', 'between time', [$startDate, $endDate]]];
                $where = array_merge($where, $where3);
            }
            $count = Db::name('record')->where($where)->whereNull('delete_time')->count();
            $list = Db::name('record')->where($where)->whereNull('delete_time')->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }

}

