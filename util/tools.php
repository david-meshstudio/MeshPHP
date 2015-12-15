<?php
function array2object($array) {
    if (is_array($array)) {  
        $obj = new StdClass();  
   
        foreach ($array as $key => $val){  
            $obj->$key = $val;  
        }  
    }  
    else { $obj = $array; }  
   
    return $obj;  
}  
   
function object2array($object) {  
    if (is_object($object)) {  
        foreach ($object as $key => $value) {  
            $array[$key] = $value;  
        }  
    }  
    else {  
        $array = $object;  
    }  
    return $array;  
}

function createToken() {
    $uuid = createGUID();
    $ret = $uuid.':'.time();
    return $ret;
}

function createGUID() {	
    $uuid = md5(uniqid(mt_rand(), true));
    return $uuid;
}

function createDoubleGUID() {	
    $uuid = md5(uniqid(mt_rand(), true)).':'.md5(uniqid(mt_rand(), true));
    return $uuid;
}

function createNGUID($n) {	
    $uuid = '';
    foreach($n as $i) {
    	$uuid .= md5(uniqid(mt_rand(), true)).':';
    }
    $uuid = substr($uuid,0,-1);
    return $uuid;
}

function index_of($arr,$item) {
	$ret = array_search($item,$arr);
	if(!$ret && $item != $arr[0]) {
		return array(false, $ret);
	} else {
		return array(true, $ret);
	}
}

function array_remove($needle, $haystack) {
  $ret = array();
  foreach ($haystack as $item) {
    if($item != $needle) {
      $ret[] = $item;
    }
  }
  return $ret;
}

function beginWith($str,$find) {
	return strpos($str,$find)===0;
}

function getIP() {
  if (getenv("HTTP_CLIENT_IP"))
  $ip = getenv("HTTP_CLIENT_IP");
  else if(getenv("HTTP_X_FORWARDED_FOR"))
  $ip = getenv("HTTP_X_FORWARDED_FOR");
  else if(getenv("REMOTE_ADDR"))
  $ip = getenv("REMOTE_ADDR");
  else $ip = "Unknow";
  return $ip;
}

function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

function microtime_string()
{
   list($usec, $sec) = explode(" ", microtime());
   return $usec.$sec;

}

function getDateString() {
	return date('ymd');
}

function getTimeString() {
	return date('Y/m/d H:i:s',time());
}

function urlsafe_b64decode($string) {
    $res = str_replace(' ', '+', $string);
    $res = str_replace('\\', '', $res);
    $res = base64_decode($res);
    return $res;
}

function unescape($str) 
{ 
  $str = str_replace('\\u','%u',$str);
    $ret = ''; 
    $len = strlen($str); 

    for ($i = 0; $i < $len; $i++) 
    { 
        if ($str[$i] == '%' && $str[$i+1] == 'u') 
        { 
            $val = hexdec(substr($str, $i+2, 4)); 

            if ($val < 0x7f) $ret .= chr($val); 
            else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f)); 
            else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f)); 

            $i += 5; 
        } 
        else if ($str[$i] == '%') 
        { 
            $ret .= urldecode(substr($str, $i, 3)); 
            $i += 2; 
        } 
        else $ret .= $str[$i]; 
    } 
    return $ret; 
}

function checkLegelName($str) {
  $legel = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','','','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0','_','.','@','-');
  $strArray = str_split($str);
  foreach ($strArray as $char) {
    if(!in_array($char, $legel)) {
      return false;
    }
  }
  return true;
}

// SQL

function GetDatabyID($tableName,$id){
    $sql = "SELECT * FROM `".$tableName."` WHERE `id` ='".$id."'";
    $data = MySQLGetData($sql);
    return $data;
}

function GetInsertSQL($tableName,$object) {
    $res = 'insert into `'.$tableName.'` ';
    $col = '(';
    $val = '(';
    foreach($object as $k=>$v) {
        if($k === 'id' || $k === 'ID') continue;
        $col .= '`'.$k.'`,';
        $val .= "'".$v."',";
    }
    $col = substr($col,0,-1).')';
    $val = substr($val,0,-1).')';
    $res = $res.$col.' values '.$val.';';
    return $res;
} 

function GetUpdateSQL($tableName,$object) {
    $res = 'update `'.$tableName.'` set ';
    $id = 0;
    foreach($object as $k=>$v) {
        if(is_array($v)) continue;
        if($k === 'id' || $k === 'ID') {
            $id = $v;
            continue;
        }
        $res .= "`".$k."` = '".$v."',";
    }
    $res = substr($res,0,-1);
    $res .= ' where `id`='.$id.';';
    return $res;
}

function GetUpdateSQLI($tableName,&$object) {
    $id = array_key_exists('id', $object) ? $object['id'] : $object['ID'];
    if($id > 0) {
        $res = 'update `'.$tableName.'` set ';
        $id = 0;
        foreach($object as $k=>$v) {
            if(is_array($v)) continue;
            if($k === 'id' || $k === 'ID') {
                $id = $v;
                continue;
            }
            $res .= "`".$k."` = '".$v."',";
        }
        $res = substr($res,0,-1);
        $res .= ' where `id`='.$id.';';
    } else {
        $res = GetInsertSQL($tableName,$object);
        if($object[$lk] === null || $object[$lk] === '' || $lk === 'id' || $lk === 'ID') {
            $sql = "select `".$lk."` from `".$tableName."` order by `id` DESC limit 0,1;";
            $ret = MySQLGetData($sql);
            $object[$lk] = $ret[0][$lk];
        }
    }
    return $res;
}

function GetUpdateSQLFK($tableName,&$object, $lk, $fkv, $condition="") {
    $id = array_key_exists('id', $object) ? $object['id'] : $object['ID'];
    if($id > 0) {
        $res = 'update `'.$tableName.'` set ';
        $id = 0;
        foreach($object as $k=>$v) {
            if(is_array($v)) continue;
            if($k === 'id' || $k === 'ID') {
                $id = $v;
                continue;
            }
            $res .= "`".$k."` = '".$v."',";
        }
        $res = substr($res,0,-1);
        $res .= " where `".$lk."` = '".$fkv."'".$condition.";";
    } else {
        $res = GetInsertSQL($tableName,$object);
        if($object[$lk] === null || $object[$lk] === '' || $lk === 'id' || $lk === 'ID') {
            $sql = "select `".$lk."` from `".$tableName."` order by `id` DESC limit 0,1;";
            $ret = MySQLGetData($sql);
            $object[$lk] = $ret[0][$lk];
        }
    }
    return $res;
}

function GetUpdateSQLRelation($tableName,&$object, $lk, $rfk, $rtable, $rmk, $mkv, $condition="") {
    $id = array_key_exists('id', $object) ? $object['id'] : $object['ID'];
    if($id > 0) {
        $res = 'update `'.$tableName.'` set ';
        $id = 0;
        foreach($object as $k=>$v) {
            if(is_array($v)) continue;
            if($k === 'id' || $k === 'ID') {
                $id = $v;
                continue;
            }
            $res .= "`".$k."` = '".$v."',";
        }
        $res = substr($res,0,-1);
        $res .= " where `".$lk."` in (select `".$rfk."` from `".$rtable."` where `status` = 1 and `".$rmk."` = '".$mkv."'".$condition.");";
        $res = array($res);
    } else {
        $res = array();
        $res[] = GetInsertSQL($tableName,$object);
        if($object[$lk] === null || $object[$lk] === '' || $lk === 'id' || $lk === 'ID') {
            $sql = "select `".$lk."` from `".$tableName."` order by `id` DESC limit 0,1;";
            $ret = MySQLGetData($sql);
            $object[$lk] = $ret[0][$lk];
        }
        $res[] = "insert into `".$rtable."` (`".$rfk."`,`".$rmk."`) values ('".$object[$lk]."','".$mkv."');";
    }
    return $res;
}

function GetDeleteByIDSQL($tableName,$id) {
    $sql = "delete from `".$tableName."` where `id` =".$id."";
    return $sql;
}

function GetDeleteSQL($tableName,$object) {
    $id = array_key_exists('id', $object) ? $object['id'] : $object['ID'];
    if($id > 0) {
        $ret = GetDeleteByIDSQL($tableName,$id);
    }
    return $ret;
}

function GetUpdateDeleteSQLFK($tableName,$lk,$fkv,$idList,$condition="") {
    $sql = "delete from `".$tableName."` where `".$lk."` = '".$fkv."' and `id` not in ('".implode("','", $idList)."')".$condition.";";
    return $sql;
}

function GetUpdateeDeleteSQLRelation($tableName,$rmk,$fkv,$rfk,$lkvList,$condition="") {
    $sql = "delete from `".$tableName."` where `".$rmk."` = '".$fkv."' and `".$rfk."` not in ('".implode("','", $lkvList)."')".$condition.";";
    return $sql;
}

function GetDeleteSQLFK($tableName, $lk, $fkv, $condition="") {
    if($lk === 'id' || $lk === 'ID') {
        $sql = null;
    } else {
        $sql = "delete from `".$tableName."` where `".$lk."` = '".$fkv."'".$condition.";";
    }
    return $sql;
}

function DrawJson($json) {
    if(is_string($json)) {
        $json = json_decode($json,true);
    } else if($is_object($json)) {
        $json = object2array($json);
    } else if(!$is_array($json)) {
        $json = array($json);
    }

    $html = '<div class="json_display" >';
    $layer = 1;
    foreach ($json as $key => $value) {
        if(is_array($value)) {

        } else {
            $space = GetNBlankString(2*$layer);
        }
    }
    $html .= '</div>';
    return $html;
}

function GetNBlankString($n) {
    $blank = '&nbsp;';
    $res = '';
    for($i = 0; $i < $n; $i++) {
        $res .= $blank;
    }
    return $res;
}