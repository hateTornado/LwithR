<?php
require_once('../init.php');
 
 

$account=safeCheck($_POST['account'],0);
$password=safeCheck($_POST['pass'],0);
$vercode=safeCheck($_POST['vercode'],0);
$remember=safeCheck($_POST['remember'],0);

 

if($vercode != $_SESSION['WiiPHP_imgcode']){
	echo action_msg('验证码错误', -4);
	exit();
}else {
 
	try {
		$user = new User();
		$r = $user->login($account, $password, $remember);
		echo $r;
		 
	     }catch(MyException $e){
			  echo $e->jsonMsg();
	}
}

 
?>