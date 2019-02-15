<?php
namespace app\admin\controller;
use think\Db;
class Assign extends \app\common\controller\BaseController
{
    
    public function device_type() {
        if ($this->request->isAjax()) {
            $device_list = Db::name('device_type')->select();
            return json($device_list);
        }
    }
    
    /**
     * 生产排单
     * @return type
     */
    public function index() {
        $where = [];
        $device_type = input('search_key1','');
        $device_type ? $where['device_type'] = $device_type : null;
        $sale_type = input('search_key2','');
        $sale_type ? $where['sale_type'] = $sale_type : null;
        $productid = input('search_key3','');
        //$productid ? $where['productid'] = ['like',"%$productid%"] : null;
        if($productid){
            $where1 = [['productid', 'like', "%$productid%"]];
            $where = array_merge($where, $where1);
        }
//        $startDate = input('search_startDate','');
//        $endDate = input('search_endDate','');
//        if ($startDate && $endDate) {
//            $where3 = [['deadline', 'between time', [$startDate, $endDate]]];
//            $where = array_merge($where, $where3);
//        }
        //$where['end_treatstate'] = null;

        // 查询状态为1的用户数据 并且每页显示10条数据 总记录数为1000
        //$count = Db::name('record')->where($where)->whereNull('delete_time')->count();
        $list = Db::name('record')->alias('a')->join('device d','a.deviceid = d.device_name')
                ->field('a.id,deviceid,recordid,productid,sale_type,num,deadline,d.device_type,'
                        . 'lean_treatstate,assign_treatstate,assigned')
                ->where($where)->whereNull('end_treatstate')->order('deadline asc')
                ->paginate(15,false,['query'=>request()->param()])
                ->each(function($item, $key){
            switch ($item['assign_treatstate']) {
                case NULL:
                    //该订单还没生产处理	
                    $item['scid'] = 'gray'; //颜色
                    break;
                case '已处理':
                    //该订单已经生产处理，可以排单	
                    $item['scid'] = 'orange'; //颜色
                    break;
//                case '已处理':
//                    //该订单已经排单，锁定	
//                    $item['scid'] = 'red'; //颜色
//                    break;
            }
            if($item['lean_treatstate'] === '已处理'){
                //该订单已经取纱线
                $item['scid'] = 'green'; //颜色
            }
            if(get_date() > $item['deadline']){
                //该订单已经取纱线
                $item['color'] = 'red'; //颜色
            }

            return $item;
        });
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);
        
        $device_list = Db::name('device')->order('device_type asc,device_name asc')->select();
        $ass_list = Db::name('record_assign')->where('assign_state','=',null)->select();
        
        $ddlist = [];
        foreach ($device_list as $key => $device) {
            $dname = $device['device_name'];
            $assign = $device;
            foreach ($ass_list as $akey => $ass) {
                if($dname == $ass['bind_device']){
                    //$assign['A'.$ass['assign_seq']] = $ass['productid'];
                    $assign['A'.$ass['assign_seq']] = '<div class="item" data="'.$ass['recordid'].'" title="'.$ass['sale_type'].'/'.$ass['num'].'米/'.$ass['deviceid'].'">'.$ass['productid'].'</div>';
                }
            }
            $ddlist[] = $assign;
        }
        //dump($ddlist);
        $this->assign('device_list', $ddlist);
        

//        $this->assign('ass_list', $ass_list);
        // 渲染模板输出
        return $this->fetch();
    }
    
    /**
     * 机台安排查询
     */
    public function query() {
        $device_list = Db::name('device')->order('device_type asc,device_name asc')->select();
        $ass_list = Db::name('record_assign')->where('assign_state', '=', null)->select();

        $ddlist = [];
        foreach ($device_list as $key => $device) {
            $dname = $device['device_name'];
            $assign = $device;
            foreach ($ass_list as $akey => $ass) {
                if ($dname == $ass['bind_device']) {
                    //$assign['A'.$ass['assign_seq']] = $ass['productid'];
                    $assign['A' . $ass['assign_seq']] = '<div class="item" data="' . $ass['recordid'] . '" title="' . $ass['sale_type'] . '/' . $ass['num'] . '米/' . $ass['deviceid'] . '">' . $ass['productid'] . '</div>';
                }
            }
            $ddlist[] = $assign;
        }
        //dump($ddlist);
        $this->assign('device_list', $ddlist);

        // 渲染模板输出
        return $this->fetch();
    }
}