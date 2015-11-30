<?php
class MDBO {
	public $ID;
	public $Type;
	public $Properties;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		 
	}
	
	protected function GetRowArray() {
		$arr = array();
		$arr['ID'] = $this->ID;
		foreach($this->Properties as $k => $v) {
			$arr[$k] = $v;
		}
		return $arr;
	}
	
	protected function RemoveAt($arr,$k) {
		$ret = array();
		$keys = array_keys($arr);
		$ki = $this->array_site($arr,$k);
		if($ki>=0) {
			$arrList = array_chunk($arr,$ki+1,true);
			array_pop($arrList[0]);
			$ret = $this->array_merge_all($arrList);
		} else {
			$ret = $arr;
		}
		return $ret;
	}
	
	public function Create2Table($data) {
		$arr = $this->GetRowArray();
		array_push($data,$arr);
		return $data;
	}
	
	public function Create2DB() {
		$sql = $this->Create2SQL();
		//$mysql = new SaeMysql();
		//$ret = $mysql->runSql($sql);
		
		$ret = MySQLGetData($sql);
		return $ret;
	}
	
	public function Create2SQL() {
		$sql = "insert into `".$this->Type."` ";
		$sql1 = '(';
		$sql2 = ' values (';
		foreach($this->Properties as $k => $v) {
			$sql1 .= '`'.$k.'`,';
			$sql2 .= "'".$v."',";
		}
		$sql1 = substr($sql1,0,strlen($sql1)-1).")";
		$sql2 = substr($sql2,0,strlen($sql2)-1).");";
		$sql .= $sql1.$sql2;
		return $sql;
	}
	
	public function Read4Table($type,$data,$index) {
		if($index>=count($data)) return;
		$arr = $data[$index];
		$this->Type = $type;
		foreach($arr as $ck => $cv) {
			if(strtoupper($ck)=='ID') {
				$this->ID = $cv;
			} else {
				$this->Properties[$ck] = $cv;
			}
		}
		//return null;//$data;
	}
	
	public function Read4DB($tableName,$condition='') {
		//$mysql = new SaeMysql();
		if($condition=='') {
			$sql = "select * from `".$tableName."`;";
		} else {
			$sql = "select * from `".$tableName."` where ".$condition."`;";
		}		
		//$data = $mysql->getData($sql);

		$data = MySQLGetData($sql);
		$data = $this->Read4Table($tableName,$data,0);
		//return null;//$data;
	}
	
	public function Update2Table($data) {		
		$arr = $this->GetRowArray();
		$data[$this->ID] = $arr;
		return $data;
	}
	
	public function Update2DB() {
		$sql = $this->Update2SQL();
		//$mysql = new SaeMysql();
		//$ret = $mysql->runSql($sql);

		$ret = MySQLGetData($sql);
		return $ret;
	}
	
	public function Update2SQL() {
		$sql = "update `".$this->Type."` set ";
		foreach($this->Properties as $k => $v) {
			$sql = $sql."`".$k."`='".$v."',";
		}
		$sql = substr($sql,0,strlen($sql)-1)." where `ID`=".$this->ID.";";
		return $sql;
	}
	
	public function Update2DBByColumn($cname) {
		$sql = $this->Update2SQLByColumn($cname);
		//$mysql = new SaeMysql();
		//$ret = $mysql->runSql($sql);

		$ret = MySQLGetData($sql);
		return $ret;
	}
	
	public function Update2SQLByColumn($cname) {
		$sql = "update `".$this->Type."` set ";
		foreach($this->Properties as $k => $v) {
			$sql = $sql."`".$k."`='".$v."',";
		}
		$sql = substr($sql,0,strlen($sql)-1)." where `".$cname."`='".$this->Properties[$cname]."';";
		return $sql;
	}
	
	public function Delete2Table($data) {
		$data = $this->RemoveAt($data,$this->ID);
		return $data;
	}
	
	public function Delete2DB() {
		$sql = $this->Delete2SQL();
		//$mysql = new SaeMysql();
		//$ret = $mysql->runSql($sql);
		
		$ret = MySQLGetData($sql);
		return $ret;
	}
	
	public function Delete2SQL() {
		$sql = "delete from `".$this->Type."` where ID=".$this->ID.";";
		return $sql;
	}
	
	public function Delete2DBByColumn($cname) {
		$sql = $this->Delete2SQLByColumn($cname);
		//$mysql = new SaeMysql();
		//$ret = $mysql->runSql($sql);

		$ret = MySQLGetData($sql);
		return $ret;
	}
	
	public function Delete2SQLByColumn($cname) {
		$sql = "delete from `".$this->Type."` where `".$cname."`='".$this->Properties[$cname]."';";
		return $sql;
	}
}
?>