<!DOCTYPE html>
<html>
<head>
<meta name="Author" content="微普科技http://www.wiipu.com"/>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title> 网站首页 </title>
<link rel="stylesheet" href="css/basic.css" type="text/css"/>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
</head>
<body>
		<input type="button"  id="btn_login" class="btn_submit" value="登 陆" />
		<input type="button"  id="btn_reg" class="btn_submit" value="注 册" />
</body>
<script>
$(function(){
	$('#btn_login').click(function(){
		location.href= "login.html";
		});	
	$('#btn_reg').click(function(){
		location.href= "register.html";
		});	
});
</script>

</html>