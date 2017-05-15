<?php
/**
 * 新闻列表 newslist.php
 *
 * @version       v0.01
 * @create time   2011-9-26
 * @update time
 * @author        jiangting
 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
 */
	require_once('../include/dbconn.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> 新闻列表 </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="author" content="Jiangting@WiiPu -- http://www.wiipu.com" />
 </head>
 <body>
	<h1>新闻列表</h1>
	<p><a href="news_add.php">添加新闻</a></p>
	<div class="content">
		<form action="#" method="post" name="listForm"><table width="100%" border="1">
			<tr>
				<th>标题</th>
				<th>首页推荐</th>
				<th>浏览次数</th>
				<th>添加时间</th>
				<th>编辑</th>
				<th>删除</th>
			</tr>
			<?php
				//分页设置
				$pagesize = 5;
				$startrow = 0;
				
				//记录总数
				$sql_c="select news_id from news";
				$rs_c=mysql_query($sql_c);
				$rscount=mysql_num_rows($rs_c);
				
				//分页参数计算
				if ($rscount%$pagesize==0)
					$pagecount=$rscount/$pagesize;
				else
					$pagecount=ceil($rscount/$pagesize);

				if (empty($_GET['page'])||!is_numeric($_GET['page']))
					$page=1;
				else{
					$page=$_GET['page'];
					if($page<1) $page=1;
					if($page>$pagecount) $page=$pagecount;
					$startrow=($page-1)*$pagesize;
				}

				if($page>=$pagecount)
					$nextpage=$pagecount;
				else
					$nextpage=$page+1;
				

				if($page<=1)
					$prepage=1;
				else
					$prepage=$page-1;
				
				//查询结果
				$sql="select * from news order by news_id desc limit $startrow,$pagesize";
				$result=mysql_query($sql);
				while($rows=mysql_fetch_array($result))
				{
					if($rows['news_isindex']=="1")
						$index="是";
					else
						$index="-";

			?>
			<tr>
				<td><?php echo $rows['news_title']?></td>
				<td><?php echo $index?></td>
				<td><?php echo $rows['news_viewcount']?></td>
				<td><?php echo $rows['news_addtime']?></td>
				<td><a href="news_edit.php?id=<?php echo $rows["news_id"]?>">编辑</a></td>
				<td><a href="javascript:if(confirm('您确定要删除吗？')){location.href='news_do.php?act=del&id=<?php echo $rows['news_id'];?>'}">删除</a></td>
			</tr>
			<?php
				}
			?>
		</table></form>
		<p>当前页:<?php echo $page;?>/<?php echo $pagecount;?>页 每页 <?php echo $pagesize?> 条，共 <?php echo $rscount?> 条</p>
		<?php
		if($pagecount>1){//当页面数大于1时显示分页
			$url="newslist.php?";
		?>
		<p>
			<a href="<?php echo $url;?>page=1">首页</a> 
			<a href="<?php echo $url;?>page=<?php echo $prepage;?>">上一页</a> 
			<a href="<?php echo $url;?>page=<?php echo $nextpage;?>">下一页</a> 
			<a href="<?php echo $url;?>page=<?php echo $pagecount;?>">尾页</a>
		</p>
		<?php
			}
		?>
	</div>
 </body>
</html>