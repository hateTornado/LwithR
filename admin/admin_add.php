<?php
	/**
	 * 添加管理员  admin_add.php
	 *
	 * @version       v0.03
	 * @create time   2014-8-3
	 * @update time   2016/3/27
	 * @author        hlc jt
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
	
	require_once('admin_init.php');
	require_once('admincheck.php');
    
    $POWERID = '7002';//权限
    Admin::checkAuth($POWERID, $ADMINAUTH);
	
    //左侧菜单
	$FLAG_LEFTMENU = 'admin_add';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="微普科技 http://www.wiipu.com" />
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/form.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/layer/layer.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript">
			$(function(){
				$('#btn_sumit').click(function(){

					account = $('input[name="account"]').val();
					group = $('#group').val();
					password = $('input[name="password"]').val();
					
					if(account == ''){
						layer.tips('账号不能为空', '#s_admin_account');
						return false;
					}
					if(password == ''){
						layer.tips('密码不能为空', '#s_admin_password');
						return false;
					}
					if(group == ''){
						layer.tips('请选择管理员组', '#s_admin_group');
						return false;
					}
					$.ajax({
						type        : 'POST',
						data        : {
								account  : account,
								password  : password,
								group  : group
						},
                        dataType :    'json',
						url :         'admin_do.php?act=add',
						success :     function(data){
                                            code = data.code;
											msg  = data.msg;
											switch(code){
												case 1:
													layer.alert(msg, {icon: 6,shade: false}, function(index){
        												parent.location.reload();
        											});
													break;
												default:
													layer.alert(msg, {icon: 5});
											}
									  }
					});
				});
			});
		</script>
	</head>
	<body>
		<div id="formlist">
			<p>
				<label>帐号</label>
				<input type="text" class="text-input input-length-30" name="account" id="account" />
				<span class="warn-inline" id="s_admin_account">* </span>
			</p>
			<p>
				<label>密码</label>
				<input type="password" class="text-input input-length-30" name="password" id="password" />
				<span class="warn-inline" id="s_admin_password">* </span>
			</p>
			<p>
				<label>管理员所属组</label>
				<select name="group" class="select-option" id="group">
					<?php
						$group = Admingroup::getList();
						foreach($group as $g){
							$gid   = $g['groupid'];
							$gname = $g['name'];
							echo '<option value="'.$gid.'">'.$gname.'</option>';
						}
					?>
				</select>
				<span class="warn-inline" id="s_admin_group">* </span>
			</p>
			<p>
				<label>　　</label>
				<input type="submit" id="btn_sumit" class="btn_submit" value="提　交" />
			</p>
		</div>
	</body>
</html>