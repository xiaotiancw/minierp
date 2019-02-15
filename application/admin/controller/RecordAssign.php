<?php
namespace app\admin\controller;
use think\Db;

class RecordAssign extends \app\common\controller\BaseController
{
    public function update() {
        $id = input('id/s','');//record表recordid
        $device = input('device/s','');//待排单机台号，即页面上显示机台号
        $device_type = input('device_type/s','');//待排单机台精密
        $seq = input('seq/d',0);//待排单机台顺序seq
        
        //$record = Db::name('record')->field('deviceid,recordid,productid,sale_type,num,deadline')->find($id);
        $record = Db::name('record')->field('deviceid,recordid,productid,sale_type,num,deadline')
                ->where('recordid',$id)->find();
        
        $dev = Db::name('device')->where('device_name',$record['deviceid'])->find();
        //判断机密是否相同
        if($device_type && $dev && $device_type != $dev['device_type']){
            return ['Success' => false, 'Message' => '精密不同！'];
        }
        
        //该位置是否已经排单，定位如下：机台号+seq
        $where = [];
        $where['bind_device'] = $device;
        $where['assign_seq'] = $seq;
        $as = Db::name('record_assign')->where($where)->where('assign_state','=',null)->find();
        if($as){
            Db::name('record_assign')->delete($as['id']);
        }
        
        //该记录是否已经排单
        $ass = Db::name('record_assign')->where('recordid',$record['recordid'])->find();
        //如果已经排单，则更新，没有则新增
        Db::startTrans();
        try {
            $result = Db::name('record')->where('recordid', $record['recordid'])->update(['assigned'=>'机台已排单']);
            //更新record_assign排单表
            if ($ass) {
                $ass['bind_device'] = $device;
                $ass['device_type'] = $device_type;
                $ass['assign_seq'] = $seq;
                Db::name('record_assign')->update($ass);
            } else {
                $record['bind_device'] = $device;
                $record['device_type'] = $device_type;
                $record['assign_seq'] = $seq;
                Db::name('record_assign')->insert($record);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }




        return ['Success' => true, 'Message' => '排单成功！'];
    }
    
//    public function check_assign() {
//        $id = input('id/s', ''); //record表recordid
//        //该记录是否已经排单
//        $ass = Db::name('record_assign')->where('recordid', $id)
//                ->where('assign_state', '=', null)
//                ->where('delete_time','=',null)
//                ->find();
//        if($ass){
//            return ['Success'=>true,'Message'=>'已经排单'];
//        }else{
//            return ['Success' => false, 'Message' => '未排单'];
//        }
//    }
}