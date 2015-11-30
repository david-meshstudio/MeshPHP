<?php
function getMenuList() {
	$menuList = array();
	$menu1 = new Menu('m1','账号管理');
	$menu2 = new Menu('m2','系统管理');

	$menu11 = new Menu('m11','账号信息','acc_account_info');
	$menu12 = new Menu('m12','应用信息','acc_app_info');
	$menu13 = new Menu('m13','用户信息','acc_user_info');
	$menu1->addSubMenu($menu11);
	$menu1->addSubMenu($menu12);
	$menu1->addSubMenu($menu13);

	$menu21 = new Menu('m21','数据库环境','sys_db_info');
	$menu22 = new Menu('m22','KVDB管理','base_kvdb_keylist');
	$menu23 = new Menu('m23','MEM管理','base_mem_keylist');
	$menu2->addSubMenu($menu21);
	$menu2->addSubMenu($menu22);
	$menu2->addSubMenu($menu23);

	array_push($menuList,$menu1);
	array_push($menuList,$menu2);
	return $menuList;
}
function getTableTitle($tableName) {
	$map = array();
	$map['acc_account_info'] = '账户信息';
	
	$ret = $map[$tableName];
	return $ret === null ? $tableName : $ret;
}
function getColumnTitle($tableName,$columnName) {
	$map = array();
	$acc_account_info = array();
	$acc_account_info['sn'] = '编号';
	$acc_account_info['username'] = '账号';
	$acc_account_info['accesskey'] = 'AK';
	$acc_account_info['secretkey'] = 'SK';
	$acc_account_info['remark'] = '备注';
	$map['acc_account_info'] = $acc_account_info;

	$ret = $map[$tableName][$columnName];
	return $ret === null ? $columnName : $ret;
}
function getMobileColumnTitle($tableName,$columnName) {
	$map = array();
	$acc_account_info = array();
	$acc_account_info['sn'] = '编号';
	$acc_account_info['username'] = '账号';
	$acc_account_info['remark'] = '备注';
	$map['acc_account_info'] = $acc_account_info;

	$ret = $map[$tableName][$columnName];
	return $ret === null ? $columnName : $ret;
}
function getColumnDefault($tableName,$columnName) {
	$map = array();
	$acc_account_info = array();
	$acc_account_info['password'] = '******';
	$map['acc_account_info'] = $acc_account_info;

	return $map[$tableName][$columnName];
}
function getColumnType($tableName,$columnName) {
	$map = array();
	$image_info = array();
	$image_info['url'] = 'picture';
	$image_info['type'] = 'select,image_type';
	//$image_info['info'] = 'readonly';
	$image_info['volumn'] = 'readonly';
	$map['Base_Image_Info'] = $image_info;

	return $map[$tableName][$columnName];
}
function getImageTypeChoice() {
	$ret = array();
	$ret['0'] = '通用';
	$ret['1'] = '用户头像';
	$ret['2'] = '文章主图';
	$ret['3'] = '文章详图';
	$ret['4'] = '商品主图';
	$ret['5'] = '商品详图';
	$ret['6'] = '分类图片';
	$ret['7'] = '标签图片';
	return $ret;
}
function getUserGenderChoice() {
	$ret = array();
	$ret['0'] = '女';
	$ret['1'] = '男';
	return $ret;
}
function getUserLevelChoice() {
	$ret = array();
	$ret['0'] = '管理员';
	$ret['1'] = '普通';
	$ret['2'] = 'VIP';
	$ret['3'] = '资深VIP';
	return $ret;
}
function getArticleTypeChoice() {
	$ret = array();
	$ret['1'] = '普通';
	$ret['2'] = '精简';
	$ret['3'] = '通知';
	return $ret;
}
function getArticleUserChoice() {
	$ret = array();
	$sql = "select `id`,`name` from `Maga_Author_Info` where `status` = 1;";
	$data = MySQLGetData($sql);
	foreach ((array)$data as $row) {
		$ret[$row['id']] = $row['name'];
	}
	return $ret;
}
function getImageChoice($type='1,2,3,4,5,6,7,8,9') {
	$ret = array();
	$sql = "select `id`,`sn`,`url` from `Base_Image_Info` where `status` = 1 and `type` in (0,".$type.");";
	$data = MySQLGetData($sql);
	foreach ((array)$data as $row) {
		$ret[$row['id']] = $row['sn'].','.$row['url'];
	}
	return $ret;
}
function getCategoryParentChoice() {
	$ret = array();
	$ret['0'] = '-';
	$sql = "select `id`,`name` from `Shop_Category` where `status` = 1;";
	$data = MySQLGetData($sql);
	foreach ((array)$data as $row) {
		$ret[$row['id']] = $row['name'];
	}
	return $ret;
}
function getUserChoice() {
	$ret = array();
	$ret['0'] = '-';
	$sql = "SELECT `ID` AS id ,`UserName` AS name FROM `adm_user`;";
	$data = MySQLGetData($sql); 
	foreach ((array)$data as $row) {
		$ret[$row['id']] = $row['name'];
	}
	return $ret;
}

function getCategoryTypeChoice() {
	$ret = array();
	$ret['1'] = '种类';
	$ret['2'] = '营销';
	$ret['3'] = '统计';
	return $ret;
}
function getImageByID($id){
	$sql = 'SELECT url FROM `Base_Image_Info` WHERE id='.$id.'';
	$data = MySQLGetData($sql);
	return $data[0]['url'];
}

function getStyleBaseSQLTableName(){
	$ret = array();
	$ret['1'] = 'style_design_info'; 
	return $ret;
} 

function getSearchCondtionColumnListByTableName($tableName) {
	$ret = array();
	$ret['acc_account_info'] = array();
	$ret['acc_account_info'][] = 'username';

	return $ret[$tableName];
}