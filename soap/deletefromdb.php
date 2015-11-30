<?php
include_once('api.bootstraps.php');

$code = $_REQUEST['code'];
if($code === 'david') {
	$id = $_REQUEST['id'];
	$tableName = $_REQUEST['tableName'];
	$sql = GetDeleteByIDSQL($tableName,$id);
	//$mysql = new SaeMysql(); 
    $ret = MySQLRunSQL($sql); 
	if($ret) {
		echo '删除成功，请刷新列表。';
	} else {
		echo $sql;
	}
}
?>