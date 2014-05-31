<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php if(isset($html_title))echo $html_title;else echo '小蝌蚪注册';?></title>
    <meta name="description" content="<?php if(isset($html_desc))echo $html_desc;?>">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
</head>
<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-4 column">
			<form class="form-horizontal" role="form"  action="/regsave.php" method="post">
				<div class="form-group">
					 <label for="inputEmail3" class="col-sm-2 control-label" >邮箱</label>
					<div class="col-sm-10">
						<input type="email" class="form-control" id="inputEmail3"  name="email"/>
					</div>
				</div>
				<div class="form-group">
					 <label for="inputEmail3" class="col-sm-2 control-label" >昵称</label>
					<div class="col-sm-10">
						<input type="email" class="form-control" id="inputEmail3" name="nick" />
					</div>
				</div>
				<div class="form-group">
					 <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
					<div class="col-sm-10">
						<input type="password" class="form-control" id="inputPassword3" type="password"/>
					</div>
				</div>
				<div class="form-group">
					<label  class="col-sm-2 control-label">我是</label>
					<div class="col-sm-10">
							 <label>男生<input type="radio"  name="sex" value="1" /> </label>
							 <label>女生<input type="radio"  name="sex" value="0" /> </label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						 <button type="submit" class="btn btn-default">注册</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-4 column">
		</div>
		<div class="col-md-4 column">
		</div>
	</div>
</div>
</body>
</html>