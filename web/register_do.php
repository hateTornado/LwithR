<?php
require_once('../init.php');

$account = safeCheck($_POST['account'],0);
$password = safeCheck($_POST['password'],0);

$group = 1;


	echo User::add($account, $password, $group);
  


?>