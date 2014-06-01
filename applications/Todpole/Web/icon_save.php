<?php
/* 温馨提示：
 * 在flash的参数名upload_url中可自行定义一些参数（请求方式：GET），定义后在服务器端获取即可，比如可以应用到用户验证，文件的保存名等。
 * 本示例未作极致的用户体验与严谨的安全设计（如用户直接访问此页时该如何，万一客户端数据不可信时验证文件的大小、类型等），只保证正常情况下无误，请阁下注意。
 */
require_once __DIR__ . '/_init.php';
session_start();
if(!isset($_SESSION['uid']))
{
    $result['msg'] = '请先登录';
    $result['success'] = false;
    print json_encode($result);
    return;
}
$uid = $_SESSION['uid'];
session_write_close();
header('Content-Type: text/html; charset=utf-8');
$result = array();
$result['success'] = false;
$successNum = 0;
//定义一个变量用以储存当前头像的序号
$avatarNumber = 1;
$i = 0;
$msg = '';
//上传目录
$dir = ROOT_DIR."/Web/icon/$uid";

if(!is_dir($dir))
{
    mkdir($dir, 0777, true);
}

$size_map = array(100,50,32);
$index = 0;
//遍历所有文件域
while (list($key, $val) = each($_FILES))
{
	if ( $_FILES[$key]['error'] > 0)
    {
		$msg .= $_FILES[$key]['error'];
	}
	else
	{
		//处理原始图片（默认的 file 域的名称是__source，可在插件配置参数中自定义。参数名：src_field_name）
		//如果在插件中定义可以上传原始图片的话，可在此处理，否则可以忽略。
		if ($key == '__source')
		{
			//当前头像基于原图的初始化参数，用于修改头像时保证界面的视图跟保存头像时一致。帮助提升用户体验度。修改头像时设置默认加载的原图的url为此图片的url+该参数即可。
			$initParams = $_POST["__initParams"];
			$virtualPath = "$dir/$uid-source.jpg";
			$result['sourceUrl'] = '/' . $virtualPath.$initParams;
			move_uploaded_file($_FILES[$key]["tmp_name"], $virtualPath);
			/*
				可在此将 $result['sourceUrl'] 储存到数据库
			*/
			$successNum++;
		}
		//处理头像图片(默认的 file 域的名称：__avatar1,2,3...，可在插件配置参数中自定义，参数名：avatar_field_names)
		else if (strpos($key, '__avatar') === 0)
		{
		    $size = $size_map[$index++];
		    $fileName = $uid.'-'.$size;
			$virtualPath = "$dir/$fileName.jpg";
			$result['avatarUrls'][$i] = '/' . $virtualPath;
			move_uploaded_file($_FILES[$key]["tmp_name"], $virtualPath);
			/*
				可在此将 $result['avatarUrls'][$i] 储存到数据库
			*/
			$successNum++;
			$i++;
		}
		/*
		else
		{
			如下代码在上传接口upload.php中定义了一个user=xxx的参数：
			var swf = new fullAvatarEditor("swf", {
				id: "swf",
				upload_url: "Upload.php?user=xxx"
			});
			在此即可用$_POST["user"]获取xxx。
		}
		*/
	}
}
$result['msg'] = $msg;
if ($successNum > 0)
{
	$result['success'] = true;
}
//返回图片的保存结果（返回内容为json字符串）
print json_encode($result);
