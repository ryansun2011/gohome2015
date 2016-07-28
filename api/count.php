
<?php
$count = new SaeCounter();
if($count->exists('c1')){
    $count->incr('c1');
}else{
    $count->create('c1');
    $count->incr('c1');
}
$getcount = $count->get('c1');
echo json_encode(array('result'=>1,'count'=>$getcount));
//echo '你是第'.$count->get('c1').'次访问本站!';
?>










