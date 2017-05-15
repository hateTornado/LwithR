<?php
	/**
	 * 系统首页  index.php
	 *
	 * @version       v0.03
	 * @create time   2014/8/4
	 * @update time   2014/9/4 2016/3/25
	 * @author        hlc jt
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
	require_once('admin_init.php');
	require_once('admincheck.php');
	$FLAG_TOPNAV = 'index';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="微普科技 http://www.wiipu.com" />
		<title> 管理首页 </title>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<link rel="stylesheet" href="css/boxy.css" type="text/css" />
		<link rel="stylesheet" href="js/tips/tipsy.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		
	</head>
	<body>
		<div id="header">
			<?php include('top.inc.php');?>
			<?php include('nav.inc.php');?>
		</div>
		<div id="container">
			<div id="main_index">
				<div class="boxlist">
					<div class="indexbox">
						<div class="title icondefault">事务提醒</div>
						<div class="content">
							<table class="indextable">
								<tr>
									<td width="40%" class="c1">昨日新增A卡</td>
									<td width="40%" class="c2">200</td>
									<td width="20%" class="c3"></td>
								</tr>
								<tr>
									<td class="c1">未处理A卡</td>
									<td class="c2">50000</td>
									<td class="c3"></td>
								</tr>
								<tr>
									<td class="c1">经销商总数</td>
									<td class="c2">20</td>
									<td class="c3"><a href="#">查看</a></td>
								</tr>
								<tr>
									<td class="c1">处理率&gt;50%的经销商数</td>
									<td class="c2">10</td>
									<td class="c3"><a href="#">查看</a></td>
								</tr>
								<tr>
									<td class="c1">未处理留言</td>
									<td class="c2">90</td>
									<td class="c3"></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="indexbox">
						<div class="title iconstat">数据统计</div>
						<div class="content"></div>
					</div>
					<div class="indexbox">
						<div class="title icondefault">特别关注</div>
						<div class="content">
							
						</div>
					</div>
					<div class="indexbox">
						<div class="title iconstat">系统信息</div>
						<div class="content"></div>
					</div>
					<div class="indexbox">
						<div class="title icondefault">特别关注</div>
						<div class="content">
							
						</div>
					</div>
					<div class="indexbox">
						<div class="title iconstat">系统信息</div>
						<div class="content"></div>
					</div>
					<div class="clear"></div>
				</div>
				<div></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php include('footer.inc.php');?>
	</body>
</html>