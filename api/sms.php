<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
require_once('BechSMS.class.php');

//使用
$bechsms = new BechSMS();
$codeNum = rand(100000,999999);
$content = "验证码：".$codeNum."【同方全球人寿】";
$mobile = $_POST['mobile'];

//$mobile = '';
//$mobile = '15800663462';
//echo $codeNum;
//echo "<br>";
//echo $content;
//echo "<br>";
if($mobile == null){
	echo json_encode(array('result'=>-1,'msg'=>'电话号码为空..'));
	exit;
}

$arr = $bechsms->sendmsg($mobile,$content); //发送的内容您在 免审模板里应该有一个这样的免审模板： {字符}您好，验证码：{数字}【支付宝】
//如果您的网站正在使用gbk编码
//使用 ： $arr = $bechsms->sendmsg('13011110000','张三您好，验证码：84757【支付宝】','gbk');
//var_dump($arr);
if($arr>0){
	$_SESSION['codeNum'] = $codeNum;
	$_SESSION['mobile'] = $mobile;
//	setcookie('codeNum',$codeNum,time()+120,COOKIE_PATH);
//	setcookie('mobile',$mobile,time()+120,COOKIE_PATH);

	echo json_encode(array('result'=>1,'msg'=>'发送成功!'));
}else{
	echo json_encode(array('result'=>-1,'msg'=>'发送失败!'));
}
//var_dump($_SESSION);exit;
//if($arr['result']=='01'){
//
//	echo 'msg send ok'; //发送成功
//}else{
//	//echo 'msg send error:'.$arr['result'];
//	var_dump($arr);
//}