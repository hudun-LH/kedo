<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php if(isset($html_title))echo $html_title;else echo 'PHPGame 主页 高性能PHP游戏框架';?></title>
    <meta name="description" content="<?php if(isset($html_desc))echo $html_desc;?>">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/bootstrap.js"></script>
    <script type="text/javascript" src="/js/phpgame.js"></script>
</head>
<body style="" screen_capture_injected="true">
    <header>
        <div class="container">
            <div class="logo pull-left"><a href="/"><img src="/img/logo.png" alt="PHPGame"></a></div>
            <nav class="pull-right">
                <ul>
                    <?php if(!isset($html_nav)){$html_nav='home';}?>
                    <li<?php if($html_nav=='home'){?> class="current"<?php }?>><a href="/">首页</a></li>
                    <li<?php if($html_nav=='downloads'){?> class="current"<?php }?>><a href="/downloads/">下载</a></li>
                    <li<?php if($html_nav=='documentation'){?> class="current"<?php }?>><a href="/documentation/">文档</a></li>
                    <li<?php if($html_nav=='buy'){?> class="current"<?php }?>><a href="/buy/">购买</a></li>
                    <li<?php if($html_nav=='wenda'){?> class="current"<?php }?>><a href="http://wenda.phpgame.cn/">社区</a></li>
                </ul>
            </nav>
        </div>
    </header>
