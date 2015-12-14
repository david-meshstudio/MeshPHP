<?php
function GetEntityDefinition($name) {
	$entityList = array();
	// product
	$product = array();
	$product['1_main'] = array('table'=>'Shop_Product_Info','pk'=>'id','mode'=>'base','para'=>array());
	$product['1_add_main_image'] = array('fk'=>'pic','ftable'=>'Base_Image_Info','lk'=>'id','mode'=>'base','para'=>array());
	$product['1_add_main_category'] = array('fk'=>'cid','ftable'=>'Shop_Category','lk'=>'id','mode'=>'selflink','para'=>array('parent','id'));
	$product['1_add_main_category2'] = array('fk'=>'cid2','ftable'=>'Shop_Category','lk'=>'id','mode'=>'selflink','para'=>array('parent','id'));
	$product['1_add_main_para'] = array('fk'=>'id','ftable'=>'Shop_Product_Parameter','lk'=>'pid','mode'=>'base','para'=>array());
	$product['2_relation_piclist'] = array('fk'=>'id','ftable'=>'Base_Image_Info','lk'=>'id','rtable'=>'Base_Relation_Product_To_Image','rmk'=>'pid','rfk'=>'imageid','mode'=>'base','para'=>array());
	$product['2_list_detail'] = array('fk'=>'id','ftable'=>'Shop_Product_Detail','lk'=>'pid','mode'=>'base','para'=>array());
	$product['2_add_detail_pic'] = array('fk'=>'image','ftable'=>'Base_Image_Info','lk'=>'id','mode'=>'base','para'=>array());
	$product['2_list_spec'] = array('fk'=>'id','ftable'=>'Shop_Product_Spec','lk'=>'pid','mode'=>'base','para'=>array());
	$product['2_relation_lable'] = array('fk'=>'id','ftable'=>'Shop_Label','lk'=>'id','rtable'=>'Base_Relation_Lable_To_Object','rmk'=>'fid','rfk'=>'lid','mode'=>'select','para'=>array(array('tablename','Shop_Product_Info')));
	$product['2_list_comment'] = array('fk'=>'id','ftable'=>'Shop_Product_Comment','lk'=>'pid','mode'=>'base','para'=>array());
	$product['2_list_appraise'] = array('fk'=>'id','ftable'=>'Shop_Product_Appraise','lk'=>'pid','mode'=>'base','para'=>array());
	$entityList['product'] = $product;

	// default name=tablename
	$default = array();
	$default['main'] = array('table'=>$name,'pk'=>'id','mode'=>'base','para'=>array());

	return array_key_exists($name, $entityList) ? $entityList[$name] : $default;
}