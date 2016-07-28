<?php
/**
 * Created by PhpStorm.
 * User: ��ǿ
 * Date: 2016/1/23
 * Time: 12:45
 */

header("content-type:text/html;charset=utf-8");
error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');
require_once './PHPExcel_1.8.0_doc/Classes/PHPExcel.php';
include_once './PHPExcel_1.8.0_doc/Classes/PHPExcel/Writer/Excel2007.php';
include_once './PHPExcel_1.8.0_doc/Classes/PHPExcel/IOFactory.php';


$a = new info();
$data = $a->information();







$objPHPExcel=new PHPExcel();
$objPHPExcel->getProperties()
    ->setTitle('Office 2007 XLSX Document')
    ->setSubject('Office 2007 XLSX Document')
    ->setDescription('Document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Result file');
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1','ID')
    ->setCellValue('B1','用户名')
    ->setCellValue('C1','电话号码')
    ->setCellValue('D1','身份证号码')
    ->setCellValue('E1','创建时间')
    ->setCellValue('F1','出生年月');

$i=2;
foreach($data as $k=>$v){
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,$v['id'])
        ->setCellValue('B'.$i,$v['username'])
        ->setCellValue('C'.$i,$v['telphone'])
        ->setCellValue('D'.$i,"'".$v['identity'])
        ->setCellValue('E'.$i,$v['ctime'])
        ->setCellValue('F'.$i,$v['birthday']);
    $i++;
}
$objPHPExcel->getActiveSheet()->setTitle('123');
$objPHPExcel->setActiveSheetIndex(0);
$filename=urlencode('123').'_'.date('Y-m-dHis');


////*����xlsx�ļ�
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
//header('Cache-Control: max-age=0');
//$a = $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');



//*����xls�ļ�
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


$objWriter->save('php://output');
exit;



class info
{
    function information()
    {
        include_once ('../api/config.php');

        $start = '2016-1-1';
        $end = '2016-3-1';



        $arr = array();

        $sql = "select * from ".DB_PREFIX."user where ctime>='$start' and ctime<='$end'";

        $result = mysql_query($sql);


        while($row = mysql_fetch_assoc($result)){
//            $a = substr($row['identity'],6,8);
            $b = substr($row['identity'],6,4);
            $c = substr($row['identity'],10,2);
            $d = substr($row['identity'],12,2);
            $f = $b.'-'.$c.'-'.$d;
            $row['birthday'] = $f;
            $info[] = $row;
        }

        $arr = $info;

        return $arr;

    }
}