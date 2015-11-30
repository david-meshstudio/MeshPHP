<?php
function SaveAppInfo2Mem($appid) {
	$sql = "select * from `acc_app_info` where `appid` = '".$appid."' and `status` = 1;";
	$data = MySQLGetData($sql);
	$ret = $data[0];
	$appid = $ret['appid'];
	$dblink = $ret['dblink'];
	if(strpos($dblink, ',') > 0) {
		$ret['dblink'] = 'here';
		$dblinks = explode(',', $dblink);
		$ret['dbserver'] = $dblinks[0];
		$ret['dbuser'] = $dblinks[1];
		$ret['dbpwd'] = $dblinks[2];
		$ret['dbname'] = $dblinks[3];
		$ret['dbport'] = $dblinks[4];
	} else {
		$ret['dblink'] = 'other';
		$ret['dbname'] = $dblink;
	}
	$ret = SetMemKey('appid:'.$appid,json_encode($ret));
	return $ret;
}

function GetAppInfo4Mem($appid) {
	$ret = GetMemKey("appid:".$appid);
	$ret = json_decode($ret,true);
	return $ret;
}