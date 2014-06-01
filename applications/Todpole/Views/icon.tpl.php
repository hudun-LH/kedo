<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php if(isset($html_title))echo $html_title;else echo '小蝌蚪注册';?></title>
    <meta name="description" content="<?php if(isset($html_desc))echo $html_desc;?>">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script type="text/javascript" src="/js/lib/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="/js/lib/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/lib/swfobject.js"></script>
    <script type="text/javascript" src="/js/lib/fullAvatarEditor.js"></script>
</head>
<body>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12column">
		<script type="text/javascript">
            swfobject.addDomLoadEvent(function () {
                var swf = new fullAvatarEditor("swfContainer", {
					    id: 'swf',
						upload_url: '/icon_save.php',
						src_upload:2
					}, function (msg) {
						switch(msg.code)
						{
							case 1 : alert("页面成功加载了组件！");break;
							case 2 : alert("已成功加载默认指定的图片到编辑面板。");break;
							case 3 :
								if(msg.type == 0)
								{
									alert("摄像头已准备就绪且用户已允许使用。");
								}
								else if(msg.type == 1)
								{
									alert("摄像头已准备就绪但用户未允许使用！");
								}
								else
								{
									alert("摄像头被占用！");
								}
							break;
							case 5 : 
								if(msg.type == 0)
								{
									if(msg.content.sourceUrl)
									{
										alert("原图已成功保存至服务器，url为：\n" +　msg.content.sourceUrl);
									}
									alert("头像已成功保存至服务器，url为：\n" + msg.content.avatarUrls.join("\n"));
								}
							break;
						}
					}
				);
				document.getElementById("upload").onclick=function(){
					swf.call("upload");
				};
            });
        </script>
        </div>
    </div>
</div>
</body>
</html>
