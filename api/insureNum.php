<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/30
 * Time: 15:15
 */
include_once ('config.php');

$sql = "select count(id) as total from " .DB_PREFIX."user";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
if($row['total']>=MAX_NUM){
    echo json_encode(array('result'=>-1,'msg'=>'很抱歉，您来晚了，免费申领已经结束。'));
    exit;
}else{
    echo json_encode(array('result'=>1,'msg'=>'恭喜你，获得免费申领。'));
    exit;
}
