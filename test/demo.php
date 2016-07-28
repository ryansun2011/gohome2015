<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/16
 * Time: 11:24
 */
include_once ('../api/config.php');

$start = '2016-1-01';
$end = '2016-1-25';
//$start = $_POST['start'];
//$end   = $_POST['end'];


$arr = array();

$sql = "select * from ".DB_PREFIX."user where ctime>='$start' and ctime<='$end'";

$result = mysql_query($sql);
$rows = mysql_num_rows($result);


while($row = mysql_fetch_assoc($result)){
//    $a = substr($row['identity'],6,8);
    $b = substr($row['identity'],6,4);
    $c = substr($row['identity'],10,2);
    $d = substr($row['identity'],12,2);
    $f = $b.'-'.$c.'-'.$d;
    $row['birthday'] = $f;
    $info[] = $row;
}

$arr = $info;

if($rows>0){
    echo json_encode(array('result'=>1,'info'=>$arr));
}else{
    echo json_encode(array('result'=>-1,'msg'=>'失败!'));
}

