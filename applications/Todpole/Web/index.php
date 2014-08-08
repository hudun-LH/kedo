<?php 
if(!function_exists('is_mobile'))
{
    function is_mobile()
    {
        //php判断客户端是否为手机
        $agent = $_SERVER['HTTP_USER_AGENT'];
        return (strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"));
    }
}
?>
<!doctype html>

<html>
	<head>
		<meta charset="utf-8">
		<title>Workerman小蝌蚪互动聊天室</title>
		<link rel="stylesheet" type="text/css" href="css/main.css" />
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />		
    <meta name="apple-mobile-web-app-capable" content="YES">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	   <link rel="apple-touch-icon" href="/images/apple-touch-icon.png"/>
		<meta property="fb:app_id" content="149260988448984" />
		<meta name="title" content="Workerman-todpole!" />
		<meta name="description" content="这是一个奇葩、无底线的小蝌蚪们的世界，在大家不知道彼此是谁，但是确可以感知对方的存在，大家可以实时互动、可以实时聊天" />
		<link rel="image_src" href="/images/fb-image.jpg" / >
		<link rel="stylesheet" href="css/jquery.fileupload.css">
		<script src="/js/jquery.min.js"></script>
		<script src="js/lib/jquery.ui.widget.js"></script>
		<script src="js/lib/jquery.iframe-transport.js"></script>
		<script src="js/lib/jquery.fileupload.js"></script>
	</head>
	<body>
		<canvas id="canvas"></canvas>
		
		<div id="ui">
			<div id="fps"></div>
		
			<input id="chat" type="text" />
			<div id="chatText"></div>
		<?php if(!is_mobile()){?>
			<div id="instructions">
				<span class="btn btn-success fileinput-button">
				<i class="glyphicon glyphicon-plus"></i>
					<span><img src="/images/upload_icon.png" id="icon"></span>
					<input id="fileupload" type="file" name="files[]" multiple>
				</span>
				<br>
				昵称：<input type="text" id="nick" class="input"><br>
				我是：<b style="color:rgb(192, 253, 247)">男生</b>&nbsp;<input type="radio"  id="sex1" name="sex">&nbsp;&nbsp;<b style="color:rgb(255, 181, 197)">女生</b>&nbsp;<input type="radio"  id="sex0" name="sex">
			</div>
			<aside id="info">
			<section id="share">
<?php if(!is_mobile()){ ?>
<br><br><br><br><br><br><br>
<!--<img src="/css/images/logo.png" />-->
<?php }?>
<script type="text/javascript">
/*125*125，创建于2014-5-19*/
var cpro_id = "u1560945";
</script>
<script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
			</section>
			<section id="wtf">
<br><br><br><br><br><br><br><br><br><br><br><br><br>
<h2>感谢<a href="http://rumpetroll.com/" target="_blank">rumpetroll.com</a>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="external" href="http://github.com/walkor/workerman-todpole" title="workerman-todpole at GitHub">源代码：<img src="css/images/github.png" alt="fork on github"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://workerman.net/workerman-todpole" target="_blank">安装教程</a> &nbsp;&nbsp;&nbsp;&nbsp;<!-- JiaThis Button BEGIN -->
<div class="jiathis_style_24x24">
	<a class="jiathis_button_qzone"></a>
	<a class="jiathis_button_tsina"></a>
	<a class="jiathis_button_tqq"></a>
	<a class="jiathis_button_weixin"></a>
	<a class="jiathis_button_renren"></a>
	<a href="http://www.jiathis.com/share?uid=1936942" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
	<a class="jiathis_counter_style"></a>
        <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=eca5de15cc70597f4f952a1bf2bc43dd4326007ead11e9d0eda92b513c5d8dff"><img border="0" src="http://pub.idqqimg.com/wpa/images/group.png" alt="小蝌蚪群" title="小蝌蚪群"></a>
</div>
<script type="text/javascript">
var jiathis_config = {data_track_clickback:'true'};
</script>
<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=1401807204434692" charset="utf-8"></script>
<!-- JiaThis Button END -->
<h2>
			</section>
			</aside>
			<?php }?>
            <aside id="frogMode">
                <h3>Frog Mode</h3>
                <section id="tadpoles">
                    <h4>Tadpoles</h4>
                    <ul id="tadpoleList">
                    </ul>
                </section>
                <section id="console">
                    <h4>Console</h4>
                </section>
            </aside>
		
			<div id="cant-connect">
				与服务器断开连接了。您可以重新刷新页面。
			</div>
			<div id="unsupported-browser">
				<p>
					您的浏览器不支持 <a rel="external" href="http://en.wikipedia.org/wiki/WebSocket">WebSockets</a>.
					推荐您使用以下浏览器
				</p>
				<ul>										
					<li><a rel="external" href="http://www.google.com/chrome">Google Chrome</a></li>
					<li><a rel="external" href="http://apple.com/safari">Safari 4</a></li>
					<li><a rel="external" href="http://www.mozilla.com/firefox/">Firefox 4</a></li>
					<li><a rel="external" href="http://www.opera.com/">Opera 11</a></li>
				</ul>
				<p>
					<a href="#" id="force-init-button">仍然浏览!</a>
				</p>
			</div>
			
		</div>

		<script src="/js/lib/parseUri.js"></script> 
		<script src="/js/lib/modernizr-1.5.min.js"></script>
		<script src="/js/lib/Stats.js"></script>
		
		<script src="/js/App.js"></script>
		<script src="/js/Model.js"></script>
		<script src="/js/Settings.js"></script>
		<script src="/js/Keys.js"></script>
		<script src="/js/WebSocketService.js"></script>
		<script src="/js/Camera.js"></script>
		
		<script src="/js/Tadpole.js"></script>
		<script src="/js/TadpoleTail.js"></script>
		
		<script src="/js/Message.js"></script>
		<script src="/js/WaterParticle.js"></script>
		<script src="/js/Arrow.js"></script>
		<script src="/js/formControls.js"></script>
		
		<script src="/js/Cookie.js"></script>
		<script src="/js/main.js"></script>

<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F5fedb3bdce89499492c079ab4a8a0323' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Feff9f08a2cac7544e9a6e65e33897704' type='text/javascript'%3E%3C/script%3E"));
</script>

		<script>
/*jslint unparam: true */
/*global window, $ */
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = '/icon_save.php';
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                //alert(file.thumbnailUrl);
            	$('#icon').attr('src', file.thumbnailUrl);
            	app.seticon(file.thumbnailUrl);
                return;
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>
        <?php if(!is_mobile()){?>
        <a href="https://github.com/walkor/workerman-todpole" target="_blank"><img style="position: absolute; top: 0; left: 0; border: 0;" src="/images/forkme.png" alt="Fork me on GitHub"></a>
        <?php } ?>
	</body>
</html>
