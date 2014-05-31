<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php if(isset($html_title))echo $html_title;else echo '小蝌蚪注册';?></title>
    <meta name="description" content="<?php if(isset($html_desc))echo $html_desc;?>">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script type="text/javascript" src="/js/lib/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="/js/lib/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/AC_RunActiveContent.js"></script>
</head>
<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<script>
				if (AC_FL_RunContent == 0) {
					alert("此页需要 AC_RunActiveContent.js");
				} else {
					AC_FL_RunContent(
						'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
						'width', '550',
						'height', '450',
						'src', 'icon',
						'quality', 'high',
						'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
						'align', 'middle',
						'play', 'true',
						'loop', 'true',
						'scale', 'showall',
						'wmode', 'window',
						'devicefont', 'false',
						'id', 'icon',
						'bgcolor', '#ffffff',
						'name', 'icon',
						'menu', 'true',
						'allowFullScreen', 'false',
						'allowScriptAccess','sameDomain',
						'movie', '/swf/icon',
						'salign', '',
						'flashVars','sid=<?=session_id()?>'
						); //end AC code
				}
			</script>
			<noscript>
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="550" height="450" id="icon" align="middle">
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="allowFullScreen" value="false" />
				<param name="movie" value="/swf/icon.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />	<embed src="/swf/icon.swf" quality="high" bgcolor="#ffffff" width="550" height="450" name="icon" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
				</object>
			</noscript>
		</div>
	</div>
</div>
</body>
</html>