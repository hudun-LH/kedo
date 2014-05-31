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

$msg = '';

foreach($need as $key)
{
    if(!isset($_POST[$key]))
    {
        $msg = "参数错误";
        require view('reg');
        return;
    }
}

$nick = $_POST['nick'];
$email = $_POST['email'];
$sex = (int)$_POST['sex'];
$password = $_POST['password'];

if(strlen($nick) > $NAME_MAX_LEN*3)
{
    $msg = "昵称长度不能超过{$NAME_MAX_LEN}个汉字";
    require view('reg');
    return;
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    $msg = "邮件格式不正确";
    require view('reg');
    return;
}

if($sex != 0 && $sex !=1)
{
    $msg = "性别格式不正确";
    require view('reg');
    return;
}

if($PASS_MIN_LEN > strlen($password))
{
    $msg = "密码长度最少{$PASS_MIN_LEN}个字符";
    require view('reg');
    return;
}

// 检查email是否已经注册过
echo "头像设置";
