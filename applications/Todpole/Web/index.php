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
		<meta name="description" content="这是一个奇葩、无底线的小蝌蚪们的世界，在大家不知道彼此是谁，但是确可以感知对方的存在，大家可以实时互动、可以实时聊天、可以同城交友、可以寻找异性伙伴。我们都是蝌蚪，我们不仅找妈妈" />
		<link rel="image_src" href="/images/fb-image.jpg" / >
	</head>
	<body>
		<canvas id="canvas"></canvas>
		
		<div id="ui">
			<div id="fps"></div>
		
			<input id="chat" type="text" />
			<div id="chatText"></div>
			<h1>workerman</h1>
		<?php if(!is_mobile()){?>
			<div id="instructions">
				<h2>介绍</h2>
				<p>直接打字聊天!<br />输入 name: XX 则会设置你的昵称为XX</p>
				<a href="javascript:login();;return false;">登录</a>&nbsp;<a href="javascript:reg();;return false;">注册</a>
			</div>
			<aside id="info">
			<section id="share">
				<script type="text/javascript">
/*125*125，创建于2014-5-19*/
var cpro_id = "u1560945";
</script>
<script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
			</section>
			<section id="wtf">
			<br><br><br><br>
				<h2><a rel="external" href="http://github.com/walkor/workerman-todpole" title="workerman-todpole at GitHub">源代码：<img src="css/images/github.png" alt="fork on github"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://workerman.net/workerman-todpole" target="_blank">安装教程</a> &nbsp;&nbsp;&nbsp;&nbsp;感谢<a href="http://rumpetroll.com/" target="_blank">rumpetroll.com</a>提供的界面</h2>
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
		<script src="/js/jquery.min.js"></script>
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
		
	</body>
</html>
