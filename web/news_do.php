<?php
/**
 * 新闻添加、删除
 *
 * @version       v0.01
 * @create time   2011-9-26
 * @update time   
 * @author        jiangting
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
require_once('include/dbconn.inc.php');

$act=$_GET['act'];
switch($act)
{
	case 'add':
		//得到提交的数据，并进行过滤
		$title =  sqlReplace(trim($_POST['title']));
		$time  =  sqlReplace(trim($_POST['time']));
		$index =  sqlReplace(trim($_POST['index']));
		$desc  =  $_POST['desc'];
		$content= $_POST['content'];
		
		//检验数据的合法性
		checkData($title,'标题',1);
		if(strlen($desc)>420) alertInfo("简介不能超过140个字符。",'',1);

		$desc    = HTMLEncode($desc);
		$content = HTMLEncode($content);
		
		if(empty($time)) $time=date('Y-m-d H:i:s');


		//添加到新闻表中
		$sql="insert into news(news_title,news_isindex,news_desc,news_content,news_addtime) values('".$title."',".$index.",'".$desc."','".$content."','".$time."')";
		if(mysql_query($sql))
			alertInfo('添加成功','newslist.php',0);
		else
			alertInfo('添加失败，请重试。失败原因：'.mysql_error(),'',1);

		break;
		
	case 'edit':
		//得到提交的数据，并进行过滤
		$id=sqlReplace(trim($_GET['id']));
		$title=sqlReplace(trim($_POST['title']));
		$time=sqlReplace(trim($_POST['time']));
		$index=sqlReplace(trim($_POST['index']));
		$desc=$_POST['desc'];
		$content=$_POST['content'];		
		
		//检验数据的合法性
		$id=checkData($id,"ID",0);
		checkData($title,'标题',1);
		if(strlen($desc)>420) alertInfo("简介不能超过140个字符。",'',1);

		$desc=HTMLEncode($desc);
		$content = HTMLEncode($content);

		if(empty($time)) $time=date('Y-m-d H:i:s');

		
		//更新新闻表的新闻信息
		$sql="update news set news_title='".$title."',news_addtime='".$time."',news_isindex=".$index.",news_desc='".$desc."',news_content='".$content."' where news_id=".$id;
		if(mysql_query($sql))
			alertInfo('修改成功','news_edit.php?id='.$id,0);
		else
			alertInfo('修改失败，请重试。失败原因：'.mysql_error(),'news_edit.php?id='.$id,0);

		break;

	case 'del':

		$id=sqlReplace(trim($_GET['id']));
		$id=checkData($id,"ID",0);

		$sql="select * from news where news_id=".$id;
		$result=mysql_query($sql);
		$row=mysql_fetch_assoc($result);
		if(!$row){
			alertInfo('您要删除的数据不存在','',1);
		}else{
			$sql2="delete from news where news_id=".$id;
			if(mysql_query($sql2))
				alertInfo('删除成功','',1);
			else
				alertInfo('删除失败，请重试。失败原因：'.mysql_error(),'',1);
		}
		break;
}
?>