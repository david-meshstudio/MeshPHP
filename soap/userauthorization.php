<?php
include_once('api.bootstraps.php');
session_start();

$method = $_GET["method"];
$account = $_GET["account"];
$password = $_GET["password"];
$vcode = $_GET["vcode"];
if($method == 'check' && checkLegelName($account) && strtolower($vcode) == strtolower($_SESSION['vcode'])) {
	$sql = "select `".LOGIN_TABLE_PASSWORD."` from `".LOGIN_TABLE."` where `".LOGIN_TABLE_USERNAME."` = '".$account."';";
	//echo $sql;
	$data = MySQLGetData($sql);
	$ret = -1;
	if(count($data) === 0) {
		$ret = 1;
	} else {
		$pwd = $data[0][LOGIN_TABLE_PASSWORD];
		if($pwd === $password) {
			$ret = 0;
		} else {
			$ret = 2;
		}
	}
	echo $ret;
} else if($method === 'login') {
	$_SESSION['cusr']=$account;
    // $_SESSION['cak']=$data[0]['accesskey'];
    // $_SESSION['csk']=$data[0]['secretkey'];
	echo $_SESSION['cusr'];
} else if($method === 'logout') {
	$_SESSION['cusr']='';
    // $_SESSION['cak']=$data[0]['accesskey'];
    // $_SESSION['csk']=$data[0]['secretkey'];
	echo $_SESSION['cusr'];
} else if($method === 'session') {
	echo $_SESSION['cusr'];
    // $_SESSION['cak']=$data[0]['accesskey'];
    // $_SESSION['csk']=$data[0]['secretkey'];
}
?>