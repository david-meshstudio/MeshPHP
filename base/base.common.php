<?php
// MySQL
function MySQLGetData($sql,$dbname='') {
	if($dbname === '') {
		if(DBName === '') {
			$mysql = new SaeMysql();
			$mysql->runSql("SET NAMES utf8");
			$data = $mysql->getData($sql);
		} else {
			return MySQLGetData($sql,DBName);
		}
	} else {
		$mysqli = mysqli_connect(DBServerName,DBUserName,DBUserPwd,$dbname,DBPort);
		if ($mysqli->connect_error) {
		    $mysqli->close();
		    return false;
		}
		$mysqli->query("SET NAMES utf8");
		$ret = $mysqli->query($sql);
	    $data = array();
	    if($ret) {
		    while($row = mysqli_fetch_array($ret,MYSQLI_ASSOC)) {
		    	$data[] = $row;
		    }
	    }
	    $mysqli->close();
	}
	return $data;
}

function MySQLRunSQL($sql,$dbname='') {
	if($dbname === '') {
		if(DBName === '') {
			$mysql = new SaeMysql();
			$ret = $mysql->runSql($sql);
		} else {
			return MySQLRunSQL($sql,DBName);
		}
	} else {
		$mysqli = mysqli_connect(DBServerName,DBUserName,DBUserPwd,$dbname,DBPort);
		if($mysqli) {
			$mysqli->query("SET NAMES utf8");
		    $ret = mysqli_query($mysqli, $sql);
		    mysqli_close($mysqli);
		} else {
			$ret = false;
		}
	}
	return $ret;
}

function MySQLRunSQLBatch($sqlArray,$dbname='') { 
	if($dbname === '') {
		if(DBName === '') {
			$mysql = new SaeMysql();
			foreach ($sqlArray as $sql) {
				$ret = $mysql->runSql($sql);
			}
		} else {
			return MySQLRunSQLBatch($sqlArray,DBName);
		}
	} else {
		$mysqli = mysqli_connect(DBServerName,DBUserName,DBUserPwd,$dbname,DBPort);
		$mysqli->query("SET NAMES utf8");
		if($mysqli) {
		    foreach ($sqlArray as $sql) {
				$ret = mysqli_query($mysqli, $sql);
			}
		    mysqli_close($mysqli);
		} else {
			$ret = false;
		}
	}
	return $ret;
}

// KVDB

function KVSet($key, $value) {
	$kv = new SaeKV();
	$kv->init();
	$ret = KVReplace($key, $value);
	if(!$ret) {
		$ret = KVAdd($key, $value);
		addKVDBKey($key);
	}
	return $ret;
}

function KVGet($key) {
	$kv = new SaeKV();
	$kv->init();
	$ret = $kv->get($key);
	return $ret;
}

function KVAdd($key, $value) {
	$kv = new SaeKV();
	$kv->init();
	$ret = $kv->add($key, $value);
	addKVDBKey($key);
	return $ret;
}

function KVReplace($key, $value) {
	$kv = new SaeKV();
	$kv->init();
	$ret = $kv->replace($key, $value);
	return $ret;
}

function KVDelete($key) {
	$kv = new SaeKV();
	$kv->init();
	$ret = $kv->delete($key);
	delKVDBKey($key);
	return $ret;
}

// Memcache

function GetMemKey($key) {
	$mmc = memcache_init();
	if($mmc == false) {
		return -1;
	} else {
		return memcache_get($mmc,$key);
	}
}

function SetMemKey($key,$value) {
	$mmc = memcache_init();
	if($mmc == false) {
		return -1;
	} else {
		$ret = memcache_set($mmc,$key,$value);
		addKVDBKey($key);
		return $ret;
	}
}

// KVDB Key Management in MySQL

function addKVDBKey($key) {
	$sql = "insert into `base_kvdb_keylist` (`key`) values ('".$key."');";
	return MySQLRunSQL($sql);
}

function delKVDBKey($key) {
	$sql = "delete `base_kvdb_keylist` where `key` = '".$key."';";
	return MySQLRunSQL($sql);
}

// Memcache Key Management in MySQL

function addMemKey($key) {
	$sql = "insert into `base_mem_keylist` (`key`) values ('".$key."');";
	return MySQLRunSQL($sql);
}

function delMemKey($key) {
	$sql = "delete `base_mem_keylist` where `key` = '".$key."';";
	return MySQLRunSQL($sql);
}