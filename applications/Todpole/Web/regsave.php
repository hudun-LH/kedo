<?php 
require_once __DIR__ . '/_init.php';

$NAME_MAX_LEN = 10;
$PASS_MIN_LEN = 6;
$need = array(
        'nick',
        'email',
        'sex',
        'password',
);

foreach($need as $key)
{
    if(!isset($_POST[$key]))
    {
        echo json_encode(array("code"=>400, "msg"=>"bad request"));
        return;
    }
}

$nick = $_POST['nick'];
$email = $_POST['email'];
$sex = (int)$_POST['sex'];
$password = $_POST['password'];

if(mb_strlen($nick) > $NAME_MAX_LEN)
{
    echo json_encode(array("code"=>401, "msg"=>"昵称长度不能超过{$NAME_MAX_LEN}个汉字"));
    return;
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    echo json_encode(array("code"=>402, "msg"=>"email格式不正确"));
    return;
}

if($sex != 0 && $sex !=1)
{
    echo json_encode(array("code"=>403, "msg"=>"性别格式不正确"));
    return;
}

if($PASS_MIN_LEN > strlen($password))
{
    echo json_encode(array("code"=>404, "msg"=>"密码长度最少{$PASS_MIN_LEN}个字符"));
    return;
}

// 检查email是否已经注册过

