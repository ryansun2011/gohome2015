<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/16
 * Time: 9:47
 */
session_start();
include_once ('config.php');

function validateIdCard($idCard){
    //15位和18位身份证号码的正则表达式
    $regIdCard="/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/";

    $isValid = false;
    //如果通过该验证，说明身份证格式正确，但准确性还需计算
    if(preg_match($regIdCard,$idCard)){
        if(strlen($idCard)==18){
            $idCardWi=array( 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 ); //将前17位加权因子保存在数组里
            $idCardY=array( 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ); //这是除以11后，可能产生的11位余数、验证码，也保存成数组
            $idCardWiSum=0; //用来保存前17位各自乖以加权因子后的总和
            for($i=0;$i<17;$i++){
                $idCardWiSum+=substr($idCard,$i,1)*$idCardWi[$i];
            }

            $idCardMod=$idCardWiSum%11;//计算出校验码所在数组的位置

            $idCardLast=substr($idCard,17);//得到最后一位身份证号码

            //如果等于2，则说明校验码是10，身份证号码最后一位应该是X
            if($idCardMod==2){
                if($idCardLast=="X"||$idCardLast=="x"){
                    $isValid = true;
                }else{
                    $isValid = false;
                }
            }else{
                //用计算出的验证码与最后一位身份证号码匹配，如果一致，说明通过，否则是无效的身份证号码
                if($idCardLast==$idCardY[$idCardMod]){
                    $isValid = true;
                }else{
                    $isValid = false;
                }
            }
        }
    }else{
        $isValid = false;
    }

    return $isValid;
}

function checkAge($idCard, $min, $max){
    $year = substr($idCard,6,4);
    $age = 2016 - $year;
    if($age<$min || $age>$max){
        return false;
    }else{
        return true;
    }
}



$username = $_POST['username'];
$telphone = $_POST['telphone'];
$identity = $_POST['identity'];
$codeNum = $_POST['codeNum'];


//$username = '张飞';
//$telphone = '15800663465';
//$identity = '341225155854654888';
//$codeNum = '177856';

$code = $_SESSION['codeNum'];

if($codeNum != $code || $telphone != $_SESSION['mobile']){
    echo json_encode(array('result'=>-1,'msg'=>'验证码或电话匹配失败!'));
    exit;
}

$hasError = false;
$strError = '';

//用户名验证
if(mb_strlen($username,'utf-8')<=0||mb_strlen($username,'utf-8')>30){
    $hasError = true;
    $strError .= '姓名为空或多于30字。';
}
//手机验证
$pattern = "/^1[3458]{1}\d{9}$/i";
if ( !preg_match( $pattern, $telphone ) ) {
    $hasError = true;
    $strError .= '手机格式不对。';
}

//身份证号码验证
if(!validateIdCard($identity)){
    $hasError = true;
    $strError .= '请输入正确的身份证号码';
}else{
    if(!checkAge($identity,18,65)){
        $hasError = true;
        $strError .= '投保年龄必须在18-65岁之间';
    }
}




if($hasError){
    echo '{"result":-1,"msg":"'.$strError.'"}';
    exit();
}


$username = addslashes($username);
$telphone = addslashes($telphone);
$identity = addslashes($identity);

//验证电话号码是否已经使用
$phone = "select telphone from ".DB_PREFIX."user where telphone = '$telphone'";
//var_dump($phone);
$hear =mysql_query($phone);
$result = mysql_num_rows($hear);

//上线前取消注释
if($result>0){
    echo json_encode(array('result' =>-1, 'msg'=>'电话号码已经使用'));
    exit();
}

//验证身份证号码是否已经使用.
$identit = "select identity from ".DB_PREFIX."user where identity = '$identity'";
$resulta = mysql_query($identit);
$rowa = mysql_num_rows($resulta);
if($rowa>0){
    echo json_encode(array('result'=>-1,'msg'=>'很抱歉,该身份证号码已经使用过.'));
    exit;
}


$sql = "insert into ".DB_PREFIX."user(username,telphone,identity)values('$username','$telphone','$identity')";
$result = mysql_query($sql);
//$row = mysql_num_rows($result);
//var_dump($sql);
//var_dump($result);
//var_dump($row);


if($result){
    echo json_encode(array('result'=>1,'msg'=>'数据提交成功!'));
}else{
    echo json_encode(array('result'=>-1,'msg'=>'数据提交失败!'));
}
exit;