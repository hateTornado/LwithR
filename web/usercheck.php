<?php
/**
 * 登陆验证  usercheck.php
*
* @version       v0.03
* @create time   2014/9/4
* @update time   2016/3/25
* @author        dxl jt
* @copyright     Copyright (c) 微普科技 WiiPu Tech Inc. (http://www.wiipu.com)
*/
require_once('../init.php');
 
$check = User::checkLogin();


if(empty($check)) {
	header('Location: login.html');
	exit();//header()之后一定要加上退出
}else{
	$userId = User::getSession();
	$USER = new User($userId);
	//$USERGROUP = new Usergroup($USER->getGroupID());
	//$USERAUTH = $USERGROUP->getAuth();
}

?>