<?php
namespace app\admin\controller;
use think\Db;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use \PhpOffice\PhpSpreadsheet\Shared\File;

class Record extends \app\common\controller\BaseController
{

    public function data_grid($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $recordId = input('recordid','');//产品型号
            $deadline = input('deadline','');
            $startDate = input('startDate','');
            $endDate = input('endDate','');
            $where = [];
            if($recordId){
                $where1 = [['productid', 'like', "%$recordId%"]];
                $where = array_merge($where,$where1);
            }
            if($deadline){
//                // 大于某个时间
//                where('create_time', '> time', '2016-1-1');
//                // 小于某个时间
//                where('create_time', '<= time', '2016-1-1');
//                // 时间区间查询
//                where('create_time', 'between time', ['2015-1-1', '2016-1-1']);
                $where2 = [['deadline', 'between time', [$deadline,$deadline]]];
                $where = array_merge($where,$where2);
            }
            if($startDate && $endDate){
                $where3 = [['deadline', 'between time', [$startDate, $endDate]]];
                $where = array_merge($where,$where3);
            }
            $count = Db::name('record')->where($where)->whereNull('delete_time')->count();
            $list = Db::name('record')->where($where)->whereNull('delete_time')->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }
    
    /**
     * 已过交期欠单
     */
    public function expired() {
        return view();
    }

    public function datagrid_expired($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $recordId = input('recordid','');//产品型号
            $deadline = input('deadline','');
            $startDate = input('startDate','');
            $endDate = input('endDate','');
            $where = [];
            if($recordId){
                $where1 = [['productid', 'like', "%$recordId%"]];
                $where = array_merge($where,$where1);
            }
            if($deadline){
//                // 大于某个时间
//                where('create_time', '> time', '2016-1-1');
//                // 小于某个时间
//                where('create_time', '<= time', '2016-1-1');
//                // 时间区间查询
//                where('create_time', 'between time', ['2015-1-1', '2016-1-1']);
                $where2 = [['deadline', 'between time', [$deadline,$deadline]]];
                $where = array_merge($where,$where2);
            }
            if($startDate && $endDate){
                $where3 = [['deadline', 'between time', [$startDate, $endDate]]];
                $where = array_merge($where,$where3);
            }
            $where4 = [
                ['deadline', '<= time', get_date()],
                ['sale_type','=','内销'],
                ['end_treatstate','exp',Db::raw('is null')],
                ['delete_time','exp',Db::raw('is null')]
                ];
            $where = array_merge($where,$where4);
            $count = Db::name('record')->where($where)->count();
            $list = Db::name('record')->where($where)->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }
    
    public function generateRecordId(){
        $recordId = '';
        //获取当前年月份
        $ym = date('Ym');
        $record = Db::query("select get_trans_num() as recordid;");
        return json($record[0]);
        //var_dump($ym);
        //查询是否存在max(201812)
//        $record = db('record')->where('recordid','like',$ym.'%')->max('recordid');
//        if($record){
//            
//        }else{
//            $recordId = $ym.'0001';
//        }
//        var_dump($record);
//        if ($result) {
//            return ['Success' => true, 'Message' => '添加成功！','Data'=>''];
//        } else {
//            return ['Success' => false, 'Message' => '添加失败！'];
//        }
    }


    public function create() {
        if ($this->request->isPost()) {
            $data = input('post.');
            if($data['recordid']){
                $r = Db::name('record')->where('recordid',$data['recordid'])->find();
                if($r){
                    return ['Success' => false, 'Message' => '已经存在相同记录号！'];
                }
            }
            $result = Db::name('record')->insert($data);
            if ($result) {
                return ['Success' => true, 'Message' => '添加成功！'];
            } else {
                return ['Success' => false, 'Message' => '添加失败！'];
            }
            
        } 
    }
    
    public function detail() {
        $id = input('id/d', 0);
        $model = Db::name('record')->find($id);
        return json($model);
    }
    
    public function delete() {
        $ids = input('ids','');
        if(empty($ids)){
            return ['Success' => false, 'Message' => '删除失败！'];
        }
        //var_dump($ids);
        // 根据主键软删除
        //$result = db('record')->delete($ids);
        $data['delete_time'] = get_time();
        $result = Db::name('record')->where('id','in',$ids)->update($data);
        if ($result) {
            return ['Success' => true, 'Message' => '删除成功！'];
        } else {
            return ['Success' => false, 'Message' => '删除失败！'];
        }
    }
    
    
    public function edit() {
        if($this->request->isPost()){
            $data = input('post.');
//            $record = db('record')->find($data['id']);
//            if($record && !empty($record['sha_treatstate'])){
//                return ['Success'=>false,'Message'=>'已经纱线处理，不能编辑！'];
//            }
            $result = Db::name('record')->update($data);
            if($result){
                return ['Success'=>true,'Message'=>'更新成功！'];
            }else{
                return ['Success'=>false,'Message'=>'更新失败！'];
            }
            
        }
    }
    
    /**
     * 纱线处理
     */
    public function sha() {
        if($this->request->isPost()){
            $data = input('post.');
            $record = Db::name('record')->find($data['id']);
            if($record && !empty($record['mtr_treatstate'])){
                return ['Success'=>false,'Message'=>'已经备料处理，不能修改！'];
            }
            $data['sha_treattime'] = get_time();
            $data['sha_treatstate'] = "已处理";
            $result = Db::name('record')->update($data);
            if($result){
                return ['Success'=>true,'Message'=>'更新成功！'];
            }else{
                return ['Success'=>false,'Message'=>'更新失败！'];
            }
            
        }
    }
    
    /**
     * 备料处理
     */
    public function mtr() {
        if($this->request->isPost()){
            $data = input('post.');
            $record = Db::name('record')->find($data['id']);
            if($record && empty($record['sha_treatstate'])){
                return ['Success'=>false,'Message'=>'请先纱线处理！'];
            }
            if($record && !empty($record['assign_treatstate'])){
                return ['Success'=>false,'Message'=>'已经生产排单处理，不能修改！'];
            }
            $data['mtr_treattime'] = get_time();
            $data['mtr_treatstate'] = "已处理";
            $data['sha_returntime'] = get_time();
            $result = Db::name('record')->update($data);
            if($result){
                return ['Success'=>true,'Message'=>'更新成功！'];
            }else{
                return ['Success'=>false,'Message'=>'更新失败！'];
            }
            
        }
    }
    
    /**
     * 生产排单处理
     */
    public function sc() {
        if($this->request->isPost()){
            $data = input('post.');
            $record = Db::name('record')->find($data['id']);
            if($record && empty($record['mtr_treatstate'])){
                return ['Success'=>false,'Message'=>'请先备料处理！'];
            }
            if($record && !empty($record['lean_treatstate'])){
                return ['Success'=>false,'Message'=>'已经领料处理，不能修改！'];
            }
            $data['assign_treattime'] = get_time();
            $data['assign_treatstate'] = "已处理";
            $data['mtr_returntime'] = get_time();
            $result = Db::name('record')->update($data);
            if($result){
                return ['Success'=>true,'Message'=>'更新成功！'];
            }else{
                return ['Success'=>false,'Message'=>'更新失败！'];
            }
            
        }
    }
    
    /**
     * 领料处理
     */
    public function lean() {
        if($this->request->isPost()){
            $data = input('post.');
            $record = Db::name('record')->find($data['id']);
            if($record && empty($record['assign_treatstate'])){
                return ['Success'=>false,'Message'=>'请先生产排单处理！'];
            }
            if($record && !empty($record['end_treatstate'])){
                return ['Success'=>false,'Message'=>'已经结束处理，不能修改！'];
            }
            $data['lean_treattime'] = get_time();
            $data['lean_treatstate'] = "已处理";
            $data['assign_returntime'] = get_time();
            $result = Db::name('record')->update($data);
            if($result){
                return ['Success'=>true,'Message'=>'更新成功！'];
            }else{
                return ['Success'=>false,'Message'=>'更新失败！'];
            }
            
        }
    }
    
    /**
     * 结束处理
     */
    public function end() {
        if($this->request->isPost()){
            $data = input('post.');
            $record = Db::name('record')->find($data['id']);
            if($record && empty($record['lean_treatstate'])){
                return ['Success'=>false,'Message'=>'请先领料处理！'];
            }
            if($record && !empty($record['end_treatstate'])){
                return ['Success'=>false,'Message'=>'已经结束处理，不能修改！'];
            }
            $data['end_treattime'] = get_time();
            $data['end_treatstate'] = "已处理";
            $data['lean_returntime'] = get_time();
            
            Db::startTrans();
            try {
                $result = Db::name('record')->update($data);
                //更新record_assign排单表
                Db::name('record_assign')->where('recordid',$record['recordid'])
                        ->update(['assign_state'=>'已处理']);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
            
//            $result = Db::name('record')->update($data);
//            
//            //更新record_assign排单表
//            Db::name('record_assign')->where('recordid',$record['recordid'])->update(['assign_state'=>'已处理']);
            
            if($result){
                return ['Success'=>true,'Message'=>'更新成功！'];
            }else{
                return ['Success'=>false,'Message'=>'更新失败！'];
            }
            
        }
    }
    
    
    /**
     * 导出
     * @param type $param
     */
    public function export() {
        $ids = input('ids', '');
        if (empty($ids)) {
            return ['Success' => false, 'Message' => '导出失败！'];
        }
        $data = Db::name('record')->where('id', 'in', $ids)->select();

        //从数据库查询需要的数据
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        // Add title
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', '记录号')
                ->setCellValue('B1', '产品型号')
                ->setCellValue('C1', '数量/米')
                ->setCellValue('D1', '交期')
                ->setCellValue('E1', '创建时间')
                ->setCellValue('F1', '备注');
        // Rename worksheet
        //设置工作表标题名称
        $spreadsheet->getActiveSheet()->setTitle('生产记录表');
        $i = 2;
        foreach ($data as $rs) {
            
            // Add data
            $spreadsheet->getActiveSheet()
                    ->setCellValue('A' . $i, $rs['recordid'])
                    ->setCellValue('B' . $i, $rs['productid'])
                    ->setCellValue('C' . $i, $rs['num'])
                    ->setCellValue('D' . $i, $rs['deadline'])
                    ->setCellValue('E' . $i, $rs['create_time'])
                    ->setCellValue('F' . $i, $rs['message']);
            $i++;
        }
        
        $styleArray = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray)->getFont()->setSize(14);
        //Set width
        $spreadsheet->getActiveSheet()
                ->getColumnDimension('A')
                ->setWidth(15);
        $spreadsheet->getActiveSheet()
                ->getColumnDimension('B')
                ->setWidth(15);
        $spreadsheet->getActiveSheet()
                ->getColumnDimension('C')
                ->setWidth(10);
        $spreadsheet->getActiveSheet()
                ->getColumnDimension('D')
                ->setWidth(15);
        $spreadsheet->getActiveSheet()
                ->getColumnDimension('E')
                ->setWidth(20);
        $spreadsheet->getActiveSheet()
                ->getColumnDimension('F')
                ->setWidth(20);
        // Set alignment
        $spreadsheet->getActiveSheet()->getStyle('A1:F' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('C2:C' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);
        return $this->exportExcel($spreadsheet, 'xls', '生产记录表');
    }

    /**
     * 导出Excel
     * @param  object $spreadsheet  数据
     * @param  string $format       格式:excel2003 = xls, excel2007 = xlsx
     * @param  string $savename     保存的文件名
     * @return filedownload         浏览器下载
     */
    function exportExcel($spreadsheet, $format = 'xls', $savename = 'export') {
        if (!$spreadsheet)
            return false;
        if ($format == 'xls') {
            //输出Excel03版本
            header('Content-Type:application/vnd.ms-excel');
            $class = "\PhpOffice\PhpSpreadsheet\Writer\Xls";
        } elseif ($format == 'xlsx') {
            //输出07Excel版本
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $class = "\PhpOffice\PhpSpreadsheet\Writer\Xlsx";
        }
        //输出名称
        header('Content-Disposition: attachment;filename="' . $savename . '.' . $format . '"');
        //禁止缓存
        header('Cache-Control: max-age=0');
        $writer = new $class($spreadsheet);
        $filePath = env('runtime_path') . "temp/" . time() . microtime(true) . ".tmp";
        $writer->save($filePath);
        readfile($filePath);
        unlink($filePath);
        //return ['Success'=>true, 'url'=>$filePath];
    }
    
    
    /**
     * 导出
     * @param type $param
     */
    public function export1() {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        //设置工作表标题名称
        $worksheet->setTitle('学生成绩表');

        //表头
        //设置单元格内容
        $worksheet->setCellValueByColumnAndRow(1, 1, '学生成绩表');
        $worksheet->setCellValueByColumnAndRow(1, 2, '姓名');
        $worksheet->setCellValueByColumnAndRow(2, 2, '语文');
        $worksheet->setCellValueByColumnAndRow(3, 2, '数学');
        $worksheet->setCellValueByColumnAndRow(4, 2, '外语');
        $worksheet->setCellValueByColumnAndRow(5, 2, '总分');

        //合并单元格
        $worksheet->mergeCells('A1:E1');

        $styleArray = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        //设置单元格样式
        $worksheet->getStyle('A1')->applyFromArray($styleArray)->getFont()->setSize(28);

        $worksheet->getStyle('A2:E2')->applyFromArray($styleArray)->getFont()->setSize(14);
//        $sql = "SELECT id,name,chinese,maths,english FROM `t_student`";
//        $stmt = $Db::name->query($sql);
        $rows = $data = Db::name('record')->select();
        $len = count($rows);
        $j = 0;
        for ($i = 0; $i < 10; $i++) {
            $j = $i + 3; //从表格第3行开始
            $worksheet->setCellValueByColumnAndRow(1, $j, 'name');
            $worksheet->setCellValueByColumnAndRow(2, $j, 'chinese');
            $worksheet->setCellValueByColumnAndRow(3, $j, 'maths');
            $worksheet->setCellValueByColumnAndRow(4, $j, 'english');
            $worksheet->setCellValueByColumnAndRow(5, $j, 'chinese');
        }

        $styleArrayBody = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '666666'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $total_rows = $len + 2;
        //添加所有边框/居中
        $worksheet->getStyle('A1:E' . $total_rows)->applyFromArray($styleArrayBody);

        $filename = '成绩表.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    /**
     * 已经完成记录
     */
    public function finished() {
        return view();
    }
    
    public function datagrid_finished($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
        if ($this->request->isAjax()) {
            $recordId = input('recordid','');//产品型号
            $deadline = input('deadline','');
            $startDate = input('startDate','');
            $endDate = input('endDate','');
            $where = [];
            if($recordId){
                $where1 = [['productid', 'like', "%$recordId%"]];
                $where = array_merge($where,$where1);
            }
            if($deadline){
                $where2 = [['deadline', 'between time', [$deadline,$deadline]]];
                $where = array_merge($where,$where2);
            }
            if($startDate && $endDate){
                $where3 = [['deadline', 'between time', [$startDate, $endDate]]];
                $where = array_merge($where,$where3);
            }
            $where4 = [
                //['deadline', '<= time', get_date()],
                //['sale_type','=','内销'],
                ['end_treatstate','=','已处理'],
                ['delete_time','exp',Db::raw('is null')]
                ];
            $where = array_merge($where,$where4);
            $count = Db::name('record')->where($where)->count();
            $list = Db::name('record')->where($where)->order([$sort => $order])->limit($rows)->page($page)->select();
            return ['total' => $count, 'rows' => $list ? $list : ''];
        }
    }
    
    
    public function query() {
        return view();
    }
    /**
     * 生产记录查询
     */
    public function datagrid_query($page = 1, $rows = 20, $sort = 'id', $order = 'desc') {
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