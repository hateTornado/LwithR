<?php
	/**
	 * 管理员处理  admin_do.php
	 *
	 * @version       v0.03
	 * @create time   2014-9-4
	 * @update time   2016/3/25
	 * @author        dxl jt
	 * @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
	 */
	require_once('admin_init.php');
	require_once('admincheck.php');//登陆检测，所有页面都应包含

	$POWERID = '7002';//权限
	Admin::checkAuth($POWERID, $ADMINAUTH);

	$act = safeCheck($_GET['act'], 0);
	switch($act){
		case 'add'://添加管理员
			$account   =  safeCheck($_POST['account'], 0);
			$password  =  safeCheck($_POST['password'], 0);
			$group     =  safeCheck($_POST['group']);
			
            try {
				$rs = Admin::add($account, $password, $group);
				echo $rs;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'edit'://编辑管理员
			$id            = safeCheck($_POST['id']);
			$account = safeCheck($_POST['account'], 0);
			$group   = safeCheck($_POST['group']);
   
            try {
				$rs = Admin::edit($id, $account, $group);
				echo $rs;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'del'://删除管理员
			$id = safeCheck($_POST['id']);
            
            try {
				$rs = Admin::del($id);
				echo $rs;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
            
		case 'reset'://重置密码
			$id = safeCheck($_POST['id']);
			$newpass = 'wiipu888';
            
            try{
                $r = Admin::resetPwd($id, $newpass);
                echo $r;
            }catch(MyException $e){
                echo $e->jsonMsg();
            }
			break;
            
		case 'editpass'://修改密码
			$old_password = safeCheck($_POST['old_password'], 0);
			$new_password = safeCheck($_POST['new_password'], 0);
			$re_password  = safeCheck($_POST['re_password'], 0);
            
			if($new_password != $re_password){
				echo action_msg('两次密码不一致', -1);
                exit();
			}
			try {
				$r = $ADMIN->updatePwd($old_password, $new_password);
				echo $r;
			}catch (MyException $e){
				echo $e->jsonMsg();
			}
			break;
	}
?>