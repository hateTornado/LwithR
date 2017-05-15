<?php
/**
 * 新闻编辑界面 news_edit.php
 *
 * @version       v0.01
 * @create time   2011-9-26
 * @update time   
 * @author        jiangting
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
	require_once('include/dbconn.inc.php');
	
	//获取并检查参数
	$id=sqlReplace(trim($_GET['id']));
	$id=checkData($id,"ID",0);
	
	//读取数据
	$sql="select * from news where news_id=".$id;
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	if(!$row){
		alertInfo("记录不存在或已经被删除","newslist.php",0);
	}else{
		$newstitle=$row['news_title'];
		$newstime=$row['news_addtime'];
		$newsindex=$row['news_isindex'];
		$newsdesc=HTMLDecode($row['news_desc']);
		$newscontent=HTMLDecode($row['news_content']);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> 编辑新闻 </title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="author" content="Jiangting@WiiPu -- http://www.wiipu.com" />
 </head>
 <body>
	<h1>编辑新闻</h1>
	<form action="news_do.php?act=edit&id=<?php echo $id?>" method="post" id="doForm">
		<p>标题：<input type="text" name="title" value="<?php echo $newstitle;?>"/> <span>* </span></p>
		<p>新闻时间：<input type="text" name="time" value="<?php echo $newstime;?>"/> <span>格式:2011-05-05，可不填。</span></p>
		<p>首页推荐：<input type='radio' name="index" value="1"<?php if($newsindex=="1") echo " checked='checked'";?>/> 是 <input type='radio' name="index" value="0" <?php if($newsindex=="0") echo " checked='checked'";?>/> 否</p>
		<p>简介：（不超过140个字符）</p>
		<p><textarea name="desc" rows="6" cols="40"><?php echo $newsdesc;?></textarea></p>
		<p>内容：</p>
		<p style="width:90%;">
			<textarea name="content" style="width:500px;height:300px;"><?php echo $newscontent;?></textarea>
		</p>
		<p><input type="submit" value="提交" onClick="return check();"/> <input type="button" value="返回" onclick="javascript:history.go(-1);return false;"/></p>
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