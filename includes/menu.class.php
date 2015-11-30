<?php
class Menu {
	public $ID;
	public $Name;
	public $SubMenuList;
	public $TableName;
	public $Type;
	
	public function __construct($_ID,$_Name,$_TableName='',$Type='table') {
		$this->ID = $_ID;
		$this->Name = $_Name;
		$this->TableName = $_TableName;
		$this->Type = $Type;
		$this->SubMenuList = array();
	}
	
	public function __destruct() {
		
	}
	
	public function hasSubMenu() {
		if(count($this->SubMenuList) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function addSubMenu($menu) {
		array_push($this->SubMenuList,$menu);
	}
}
?>