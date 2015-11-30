<?php
class MDBAdaptor {
	public function __construct() {
		
	}
	
	public function __destruct() {
		 
	}
	
	public function RunSQL($sql) {
		//$mysql = new SaeMysql();
		//$ret = $mysql->runSql($sql);
		$ret = MySQLGetData($sql);
		return $ret;
	}
	
	public function RunSQLData($tableName,$condition='') {
		if($condition=='') {
			$sql = "select * from `".$tableName."`;";
		} else {
			$sql = "select * from `".$tableName."` where ".$condition.";";
		}
		//$mysql = new SaeMysql();
		//$data = $mysql->getData($sql);
		$data = MySQLGetData($sql);
		return $data;
	}
	
	public function GetNextIndex($tableName) {
		//$mysql = new SaeMysql();
		$ret = 1;
		//$data = $mysql->getData("select max(ID)+1 as a from `".$tableName."`");
		//$data = $ret = MySQLGetData($sql);
		$data = MySQLGetData($sql);
		if(count($data)>0) {
			$ret = $data[0]['a'];
		}
		return $ret;
	}
	
	public function Create2DB($objList) {
		$sql = '';
		$rObj = $objList[0];
		$tableName = $rObj->Type;
		$index = $this->GetNextIndex($tableName);
		foreach($objList as $obj) {
			//$currObj = $obj;
			$sql .= $obj->Create2SQL();
			$obj->ID = $index;
			$index++;
		}
		//$mysql = new SaeMysql();
		//$ret = $mysql->runSql($sql);
		$ret = MySQLGetData($sql);
		return $ret;
	}
	
	public function Read4DB($tableName,$condition='') {
		$objList = array();
		if($condition=='') {
			$sql = "select * from `".$tableName."`;";
		} else {
			$sql = "select * from `".$tableName."` where ".$condition.";";
		}
		//$mysql = new SaeMysql();
		//$data = $mysql->getData($sql);
		$data = MySQLGetData($sql);
		for($i=0;$i<count($data);$i++) {
			$mdbo = new MDBO();
			$mdbo->Read4Table($tableName,$data,$i);
			array_push($objList,$mdbo);
		}
		return $objList;
	}
	
	public function Read4DBDesc($tableName,$condition='') {
		$objList = array();
		if($condition=='') {
			$sql = "select * from `".$tableName."` order by ID desc;";
		} else {
			$sql = "select * from `".$tableName."` where ".$condition." order by ID desc;";
		}
		//$mysql = new SaeMysql();
		//$data = $mysql->getData($sql);
		$data = MySQLGetData($sql);
		for($i=0;$i<count($data);$i++) {
			$mdbo = new MDBO();
			$mdbo->Read4Table($tableName,$data,$i);
			array_push($objList,$mdbo);
		}
		return $objList;
	}
	
	public function Read4DBArray($tableName,$data) {
		$objList = array();
		for($i=0;$i<count($data);$i++) {
			$mdbo = new MDBO();
			$mdbo->Read4Table($tableName,$data,$i);
			$objList[] = $mdbo;
		}
		return $objList;
	}
	
	public function Read4DBCount($tableName,$condition='') {
		$objList = array();
		if($condition=='') {
			$sql = "select count(*) as c from `".$tableName."`;";
		} else {
			$sql = "select count(*) as c from `".$tableName."` where ".$condition.";";
		}
		//$mysql = new SaeMysql();
		//$data = $mysql->getData($sql);
		$data = MySQLGetData($sql);
		return $data[0]["c"];
	}
	
	public function Read4DBPart($tableName,$max_dbrow,$curr_page,$condition='') {
		$objList = array();
		$sql = "";
		if($condition=='') {
			$sql = "select * from `".$tableName."` limit ".$max_dbrow*($curr_page-1).",".$max_dbrow.";";
		} else {
			$sql = "select * from `".$tableName."` where ".$condition." limit ".$max_dbrow*($curr_page-1).",".$max_dbrow.";";
		}
		//$mysql = new SaeMysql();
		//$data = $mysql->getData($sql);
		$data = MySQLGetData($sql);
		for($i = 0;$i < $max_dbrow;$i++) {
			$mdbo = new MDBO();
			$mdbo->Read4Table($tableName,$data,$i);
			array_push($objList,$mdbo);
		}
		if(count($data) === 0 && $max_dbrow === 1) {
			$sql = "show columns from `".$tableName."`;";
			//$data = $mysql->getData($sql);
			$data = MySQLGetData($sql);
			$obj = new MDBO();
			$table = array(array());
			foreach ($data as $row) {
				$cname = $row['Field'];
				$default = $row['Default'];
				$value = '';
				if($default === 'CURRENT_TIMESTAMP') {
					$value = time();
				} else {
					$value = $default;
				}
				$table[0][$cname] = $value;
			}
			$obj->Read4Table($tableName,$table,0);
			$objList[0] = $obj;
		}
		return $objList;
	}
	
	public function Read4DBPartDesc($tableName,$max_dbrow,$curr_page,$condition='') {
		$objList = array();
		$sql = "";
		if($condition=='') {
			$sql = "select * from `".$tableName."` order by ID desc limit ".$max_dbrow*($curr_page-1).",".$max_dbrow.";";
		} else {
			$sql = "select * from `".$tableName."` where ".$condition." order by ID desc limit ".$max_dbrow*($curr_page-1).",".$max_dbrow.";";
		}
		//$mysql = new SaeMysql();
		//$data = $mysql->getData($sql);
		$data = MySQLGetData($sql);
		for($i = 0;$i < $max_dbrow;$i++) {
			$mdbo = new MDBO();
			$mdbo->Read4Table($tableName,$data,$i);
			array_push($objList,$mdbo);
		}
		return $objList;
	}
	
	public function Update2DB($objList) {
		$sql = '';
		foreach($objList as $obj) {
			$sql = $sql.$obj->Update2SQL();
		}
		//$mysql = new SaeMysql();

    	$mysql = mysql_connect('byfulpuiqylq.rds.sae.sina.com.cn'.':'.'11092','admin','gemstone2008_'); 
		$ret = $mysql->runSql($sql);
		return $ret;
	}
	
	public function Delete2DB($objList) {
		$sql = '';
		foreach($objList as $obj) {
			$sql = $sql.$obj->Delete2SQL();
		}
		//$mysql = new SaeMysql();
		//$ret = $mysql->runSql($sql);
		$ret = MySQLGetData($sql);
		return $ret;
	}
	
	public function FindObjectByID($objList, $ID) {
		for($i=0;$i<count($objList);$i++) {
			$currID = $objList[$i]->getID();
			if($currID == $ID) {
				return $objList[$i];
			}
		}
		return null;
	}
	
	public function FindObjectByTypeID($objDic, $Type, $ID) {
		for($i=0;$i<count($objDic[$Type]);$i++) {
			$currID = $objDic[$Type][$i]->getID();
			if($currID == $ID) {
				return $objDic[$Type][$i];
			}
		}
		return null;
	}
	
	public function FindObjectByProperty($objList, $PropertyName, $PropertyValue) {
		for($i=0;$i<count($objList);$i++) {
			$pv = $objList[$i]->Properties[$PropertyName];
			if($pv == $PropertyValue) {
				return $objList[$i];
			}
		}
		return null;
	}
	
	public function FindGeneralObjectByProperty($objList, $PropertyName, $PropertyValue) {
		for($i=0;$i<count($objList);$i++) {
			$pva = get_object_vars($objList[$i]->Properties);
			$pv = $pva[$PropertyName];
			if($pv == $PropertyValue) {
				return $objList[$i];
			}
		}
		return null;
	}
}
?>