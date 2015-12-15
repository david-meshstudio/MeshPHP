<?php
class DBEntityAdaptor {
	public function __construct() {

	}
	
	public function __destruct() {
		
	}

	// read

	public function ReadFromDB($name,$condition="") {
		$data = array();
		$definitionList = GetEntityDefinition($name);
		// every table have a column named 'status' defaultly
		foreach ($definitionList as $key => $definition) {
			$keyPara = explode('_', $key);
			$layer = $keyPara[0];
			$type = $keyPara[1];
			$name = $keyPara[2];
			$name = strpos($name, ',') > 0 ? explode(',', $name) : $name;
			switch ($type) {
				case 'main':
					$data = $this->getMainData($definition,$condition);
					break;
				case 'add':
					$data = $this->loopGetAddData($data,$layer,$name,$keyPara[3],$definition);
					break;
				case 'list':
					$data = $this->loopGetListData($data,$layer,$name,$definition);
					break;
				case 'relation':
					$data = $this->loopGetRelationData($data,$layer,$name,$definition);
					break;
				default:
					# code...
					break;
			}
		}
		return $data;
	}

	public function ReadFromDBByID($name,$id,$condition="") {
		$condition .= " and `id` = '".$id."'";
		return $this->ReadFromDB($name,$condition);
	}

	public function getMainData($definition,$condition="") {
		$data = array();
		$tablename = $definition['table'];
		$sql = "select * from `".$tablename."` where `status` = 1".$condition.";";
		$data = MySQLGetData($sql,DBName);
		return $data;
	}

	public function loopGetAddData($data,$layer,$name,$aname,$definition,$condition="") {
		if($layer === '1') {
			for($i = 0; $i < count($data); $i++) {
				$data[$i] = $this->getAddData($data[$i],$aname,$definition,$condition);
			}
		} else if($layer === '2') {
			for($k = 0; $k < count($data); $k++) {
				for($i = 0; $i < count($data[$k][$name]); $i++) {
					$data[$k][$name][$i] = $this->getAddData($data[$k][$name][$i],$aname,$definition,$condition);
				}
			}
		} else if($layer === '3') {
			for($k = 0; $k < count($data); $k++) {
				for($i = 0; $i < count($data[$k][$name[0]]); $i++) {
					for($j = 0; $j < count($data[$k][$name[0]][$i][$name[1]]); $j++) {
						$data[$k][$name[0]][$i][$name[1]][$j] = $this->getAddData($data[$k][$name[0]][$i][$name[1]][$j],$aname,$definition,$condition);
					}
				}
			}
		}
		return $data;
	}

	public function getAddData($mother,$name,$definition,$condition="") {
		$mode = $definition['mode'];
		if($mode === 'base') {
			$mother = $this->getAddDataBase($mother,$name,$definition,$condition);
		} else if($mode === 'selflink') {
			$mother = $this->getAddDataSelflink($mother,$name,$definition,$condition);
		} else if($mode === 'select') {
			$mother = $this->getAddDataSelect($mother,$name,$definition,$condition);
		}
		return $mother;
	}

	public function getAddDataBase($mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$ftable = $definition['ftable'];
		$lk = $definition['lk'];
		$data = $this->getForeignTableData($ftable,$lk,$fkv,$condition);
		$data = count($data) > 0 ? $data[0] : array();
		$mother[$name] = $data;
		return $mother;
	}

	public function getAddDataSelflink($mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$ftable = $definition['ftable'];
		$lk = $definition['lk'];
		$para = $definition['para'];
		$data = $this->getForeignTableData($ftable,$lk,$fkv,$condition);
		if(count($data) > 0) {
			$data = $data[0];
			$definition = array('fk'=>$para[0],'ftable'=>$ftable,'lk'=>$para[1],'mode'=>'selflink','para'=>$para);
			$son = $this->getAddDataSelflink($data,$name,$definition,$condition);
			$mother[$name] = $son;
		}
		return $mother;
	}

	public function getAddDataSelect($mother,$name,$definition,$condition="") {
		$para = $definition['para'];
		foreach ($para as $item) {
			$condition .= " and `".$item[0]."` = '".$item[1]."'";
		}
		$mother = $this->getAddDataBase($mother,$name,$definition,$condition);
		return $mother;
	}

	public function loopGetListData($data,$layer,$name,$definition,$condition="") {
		if($layer === '2') {
			for($i = 0; $i < count($data); $i++) {
				$data[$i] = $this->getListData($data[$i],$name,$definition,$condition);
			}
		} else if($layer === '3') {
			for($k = 0; $k < count($data); $k++) {
				for($i = 0; $i < count($data[$k][$name[0]]); $i++) {
					$data[$k][$name[0]][$i] = $this->getListData($data[$k][$name[0]][$i],$name[1],$definition,$condition);
				}
			}
		}
		return $data;
	}

	public function getListData($mother,$name,$definition,$condition="") {
		$mode = $definition['mode'];
		if($mode === 'base') {
			$mother = $this->getListDataBase($mother,$name,$definition,$condition);
		} else if($mode === 'select') {
			$mother = $this->getListDataSelect($mother,$name,$definition,$condition);
		}
		return $mother;
	}

	public function getListDataBase($mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$ftable = $definition['ftable'];
		$lk = $definition['lk'];
		$data = $this->getForeignTableData($ftable,$lk,$fkv,$condition);
		$mother[$name] = count($data) > 0 ? $data : array();
		return $mother;
	}

	public function getListDataSelect($mother,$name,$definition,$condition="") {
		$para = $definition['para'];
		foreach ($para as $item) {
			$condition .= " and `".$item[0]."` = '".$item[1]."'";
		}
		$mother = $this->getListDataBase($mother,$name,$definition,$condition);
		return $mother;
	}

	public function loopGetRelationData($data,$layer,$name,$definition,$condition="") {
		if($layer === '2') {
			for($i = 0; $i < count($data); $i++) {
				$data[$i] = $this->getRelationData($data[$i],$name,$definition,$condition);
			}
		} else if($layer === '3') {
			for($k = 0; $k < count($data); $k++) {
				for($i = 0; $i < count($data[$k][$name[0]]); $i++) {
					$data[$k][$name[0]][$i] = $this->getRelationData($data[$k][$name[0]][$i],$name[1],$definition,$condition);
				}
			}
		}
		return $data;
	}

	public function getRelationData($mother,$name,$definition,$condition="") {
		$mode = $definition['mode'];
		if($mode === 'base') {
			$mother = $this->getRelationDataBase($mother,$name,$definition,$condition);
		} else if($mode === 'select') {
			$mother = $this->getRelationDataSelect($mother,$name,$definition,$condition);
		}
		return $mother;
	}

	public function getRelationDataBase($mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$ftable = $definition['ftable'];
		$lk = $definition['lk'];
		$rtable = $definition['rtable'];
		$rmk = $definition['rmk'];
		$rfk = $definition['rfk'];
		$data = $this->getRelationTableData($ftable,$lk,$rtable,$rmk,$rfk,$fkv,$condition);
		$mother[$name] = count($data) > 0 ? $data : array();
		return $mother;
	}

	public function getRelationDataSelect($mother,$name,$definition,$condition="") {
		$para = $definition['para'];
		foreach ($para as $item) {
			$condition .= " and `".$item[0]."` = '".$item[1]."'";
		}
		$mother = $this->getRelationDataBase($mother,$name,$definition,$condition);
		return $mother;
	}

	public function getForeignTableData($ftable,$lk,$fkv,$condition="") {
		$sql = "select * from `".$ftable."` where `status` = 1 and `".$lk."` = '".$fkv."'".$condition.";";
		$data = MySQLGetData($sql,DBName);
		return $data;
	}

	public function getRelationTableData($ftable,$lk,$rtable,$rmk,$rfk,$mkv,$condition="") {
		$sql = "select * from `".$ftable."` where `status` = 1 and `".$lk."` in (select `".$rfk."` from `".$rtable."` where `status` = 1 and `".$rmk."` = '".$mkv."'".$condition.");";
		$data = MySQLGetData($sql,DBName);
		return $data;
	}

	// write

	public function WriteToDB(&$data,$name) {
		foreach ($data as &$item) {
			$this->WriteToDBSingle($item,$name);
		}
	}

	public function WriteToDBSingle(&$data,$name) {
		$definitionList = GetEntityDefinition($name);
		foreach ($definitionList as $key => $definition) {
			$keyPara = explode('_', $key);
			$layer = $keyPara[0];
			$type = $keyPara[1];
			$name = $keyPara[2];
			$name = strpos($name, ',') > 0 ? explode(',', $name) : $name;
			switch ($type) {
				case 'main':
					$ret = $this->updateMainData($data,$definition,$condition);
					break;
				case 'add':
					$ret = $this->loopUpdateAddData($data,$layer,$name,$keyPara[3],$definition);
					break;
				case 'list':
					$ret = $this->loopUpdateListData($data,$layer,$name,$definition);
					break;
				case 'relation':
					$ret = $this->loopUpdateRelationData($data,$layer,$name,$definition);
					break;
				default:
					# code...
					break;
			}
		}
		return $ret;
	}

	public function updateMainData(&$data,$definition,$condition="") {
		$tablename = $definition['table'];
		$sql = GetUpdateSQLI($tablename,$data);
		$ret = MySQLRunSQL($sql,DBName);
		// echo $sql.'<br>';
		return $ret;
	}

	public function loopUpdateAddData(&$data,$layer,$name,$aname,$definition,$condition="") {
		if($layer === '1') {
			$ret = $this->updateAddData($data,$aname,$definition,$condition);
		} else if($layer === '2') {
			for($i = 0; $i < count($data[$name]); $i++) {
				$ret = $this->updateAddData($data[$name][$i],$aname,$definition,$condition);
			}
		} else if($layer === '3') {
			for($i = 0; $i < count($data[$k][$name[0]]); $i++) {
				for($j = 0; $j < count($data[$name[0]][$i][$name[1]]); $j++) {
					$ret = $this->updateAddData($data[$name[0]][$i][$name[1]][$j],$aname,$definition,$condition);
				}
			}
		}
		return $ret;
	}

	public function updateAddData(&$mother,$name,$definition,$condition="") {
		$mode = $definition['mode'];
		if($mode === 'base') {
			$ret = $this->updateAddDataBase($mother,$name,$definition,$condition);
		} else if($mode === 'selflink') {
			$ret = $this->updateAddDataSelflink($mother,$name,$definition,$condition);
		} else if($mode === 'select') {
			$ret = $this->updateAddDataSelect($mother,$name,$definition,$condition);
		}
		return $ret;
	}

	public function updateAddDataBase(&$mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$ftable = $definition['ftable'];
		$lk = $definition['lk'];
		$ret = $this->updateForeignTableData($mother[$name],$ftable,$lk,$fkv,$condition);
		return $ret;
	}

	public function updateAddDataSelflink(&$mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$ftable = $definition['ftable'];
		$lk = $definition['lk'];
		$para = $definition['para'];
		if(count($mother[$name]) > 0) {
			$ret = $this->updateForeignTableData($mother[$name],$ftable,$lk,$fkv,$condition);
			$definition = array('fk'=>$para[0],'ftable'=>$ftable,'lk'=>$para[1],'mode'=>'selflink','para'=>$para);
			$ret = $this->updateAddDataSelflink($mother[$name],$name,$definition,$condition);
		}
		return $ret;
	}

	public function updateAddDataSelect(&$mother,$name,$definition,$condition="") {
		$para = $definition['para'];
		foreach ($para as $item) {
			$condition .= " and `".$item[0]."` = '".$item[1]."'";
		}
		$ret = $this->updateAddDataBase($mother,$name,$definition,$condition);
		return $ret;
	}

	public function loopUpdateListData(&$data,$layer,$name,$definition,$condition="") {
		if($layer === '2') {
			$ret = $this->deleteListData($data,$name,$definition,$condition);
			for($i = 0; $i < count($data[$name]); $i++) {
				$ret = $this->updateListData($data[$name][$i],$name,$definition,$condition);
			}
		} else if($layer === '3') {
			for($k = 0; $k < count($data[$name[0]]); $k++) {
				$ret = $this->deleteListData($data[$name[0]],$name[1],$definition,$condition);
				for($i = 0; $i < count($data[$name[0]][$k][$name[1]]); $i++) {
					$ret = $this->updateListData($data[$name[0]][$k][$i][$name[1]],$name[1],$definition,$condition);
				}
			}
		}
		return $ret;
	}

	public function updateListData(&$mother,$name,$definition,$condition="") {
		$mode = $definition['mode'];
		if($mode === 'base') {
			$ret = $this->updateListDataBase($mother,$name,$definition,$condition);
		} else if($mode === 'select') {
			$ret = $this->updateListDataSelect($mother,$name,$definition,$condition);
		}
		return $ret;
	}

	public function updateListDataBase(&$mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$ftable = $definition['ftable'];
		$lk = $definition['lk'];
		$ret = $this->updateForeignTableData($mother,$ftable,$lk,$fkv,$condition);
		return $ret;
	}

	public function updateListDataSelect(&$mother,$name,$definition,$condition="") {
		$para = $definition['para'];
		foreach ($para as $item) {
			$condition .= " and `".$item[0]."` = '".$item[1]."'";
		}
		$ret = $this->updateListDataBase($mother,$name,$definition,$condition);
		return $ret;
	}

	public function loopUpdateRelationData(&$data,$layer,$name,$definition,$condition="") {
		if($layer === '2') {
			$ret = $this->deleteRelationData($data,$name,$definition,$condition);
			for($i = 0; $i < count($data[$name]); $i++) {
				$ret = $this->updateRelationData($data[$name][$i],$name,$definition,$condition);
			}
		} else if($layer === '3') {
			for($k = 0; $k < count($data[$name[0]]); $k++) {
				$ret = $this->deleteRelationData($data[$name[0]],$name[1],$definition,$condition);
				for($i = 0; $i < count($data[$name[0]][$k][$name[1]]); $i++) {
					$ret = $this->updateRelationData($data[$name[0]][$k][$i][$name[1]],$name[1],$definition,$condition);
				}
			}
		}
		return $ret;
	}

	public function updateRelationData(&$mother,$name,$definition,$condition="") {
		$mode = $definition['mode'];
		if($mode === 'base') {
			$ret = $this->updateRelationDataBase($mother,$name,$definition,$condition);
		} else if($mode === 'select') {
			$ret = $this->updateRelationDataSelect($mother,$name,$definition,$condition);
		}
		return $ret;
	}

	public function updateRelationDataBase(&$mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$ftable = $definition['ftable'];
		$lk = $definition['lk'];
		$rtable = $definition['rtable'];
		$rmk = $definition['rmk'];
		$rfk = $definition['rfk'];
		$ret = $this->updateRelationTableData($mother,$ftable,$lk,$rtable,$rmk,$rfk,$fkv,$condition);
		return $ret;
	}

	public function updateRelationDataSelect(&$mother,$name,$definition,$condition="") {
		$para = $definition['para'];
		foreach ($para as $item) {
			$condition .= " and `".$item[0]."` = '".$item[1]."'";
		}
		$ret = $this->updateRelationDataBase($mother,$name,$definition,$condition);
		return $ret;
	}

	public function updateForeignTableData(&$data,$ftable,$lk,$fkv,$condition="") {
		$sql = GetUpdateSQLFK($ftable,$data,$lk,$fkv,' and `status`=1'.$condition);
		$ret = MySQLRunSQL($sql,DBName);
		// echo $sql.'<br>';
		return $ret;
	}

	public function updateRelationTableData(&$data,$ftable,$lk,$rtable,$rmk,$rfk,$mkv,$condition="") {
		$sqlArray = GetUpdateSQLRelation($ftable,$data, $lk, $rfk, $rtable, $rmk, $mkv, ' and `status` = 1'.$condition);
		$ret = MySQLRunSQLBatch($sql,DBName);
		// foreach ($sqlArray as $sql) {
		// 	echo $sql.'<br>';
		// }		
		return $ret;
	}

	public function deleteListData($mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$tablename = $definition['ftable'];
		$lk = $definition['lk'];
		$idList = array();
		foreach ($mother[$name] as $item) {
			$idList[] = $item['id'];
		}
		$ret = $this->removeForeignTableData($tablename,$lk,$fkv,$idList,$condition);
		return $ret;
	}

	public function deleteRelationData($mother,$name,$definition,$condition="") {
		$fk = $definition['fk'];
		$fkv = $mother[$fk];
		$ftable = $definition['ftable'];
		$lk = $definition['lk'];
		$rtable = $definition['rtable'];
		$rmk = $definition['rmk'];
		$rfk = $definition['rfk'];
		$lkvList = array();
		foreach ($mother[$name] as $item) {
			$lkvList[] = $item[$lk];
		}
		$ret = $this->removeRelationTableData($rtable,$rmk,$fkv,$rfk,$lkvList,$condition);
		return $ret;
	}

	public function removeForeignTableData($tablename,$lk,$fkv,$idList,$condition="") {
		$sql = GetDeleteSQLFK($tablename,$lk,$fkv,$idList,' and `status`=1'.$condition);
		$ret = MySQLRunSQL($sql,DBName);
		// echo $sql.'<br>';
		return $ret;
	}

	public function removeRelationTableData($tablename,$rmk,$fkv,$rfk,$lkvList,$condition="") {
		$sql = GetDeleteSQLRelation($tablename,$rmk,$fkv,$rfk,$lkvList,' and `status`=1'.$condition);
		$ret = MySQLRunSQL($sql,DBName);
		// echo $sql.'<br>';
		return $ret;
	}
}