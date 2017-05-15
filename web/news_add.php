<?php
/**
 * 新闻添加界面 news_add.php
 *
 * @version       v0.01
 * @create time   2011-9-26
 * @update time 
 * @author        jiangting
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> 添加新闻 </title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="author" content="Jiangting@WiiPu -- http://www.wiipu.com" />
  <link rel="stylesheet" href="style2.css" type="text/css"/>
 </head>
 <body>
	<h1>添加新闻</h1>
	<form action="news_do.php?act=add" method="post" id="doForm">
		<p>标题：<input type="text" name="title"/> <span>* </span></p>
		<p>新闻时间：<input type="text" name="time"/> <span>格式:2011-05-05，可不填。</span></p>
		<p>首页推荐：<input type='radio' name="index" value="1"/> 是 <input type='radio' name="index" value="0" checked/> 否</p>
		<p>简介：（不超过140个字符）</p>
		<p><textarea name="desc" rows="6" cols="40"></textarea></p>
		<p>内容：</p>
		<p style="width:90%;">
			<textarea name="content" style="width:500px;height:300px;"></textarea>
		</p>
		<p><input type="submit" value="提交" onClick="return check();"/></p>
		<script type="text/javascript">
			function check(){
				var f=document.getElementById('doForm');
				if(f.title.value=="")
				{
					alert('标题不能为空');
					f.title.focus();
					return false;
				}
				if(f.desc.value.length>140)
				{
					alert('简介不能超过140字符');
					return false;
				}
			}
		</script>
	</form>
 </body>
</html>