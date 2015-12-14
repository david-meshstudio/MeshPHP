<?php
  include_once(dirname(__FILE__).'/../admin.bootstraps.php');
  include_once('tablegenerater.class.php');
	$tableName = $_GET["tableName"];
	$currPage = $_GET["currPage"];
	$method = $_GET["method"];
	$count = $_GET["count"];
	$page = $_GET["page"];
	$sql = $_GET["sql"];
	$row = $_GET["row"];
  $mobi = $_GET['mobi'];
  $keyword = $_GET['keyword'];
  $keyword = base64_decode($keyword);
  $condition = GetConditionStringByTableNameKeyword($tableName,$keyword);
  //echo $mobi;
  $tg = new TableGenerator();
  switch($method) {
  	case 'init':
    	echo $tg::getFooTableHTML4DB($tableName, 5, 10, $currPage, $count, $mobi,$condition);
  		break;
  	case 'add':
  		echo $tg::addFooTableHTML4DB($tableName, 5, 10, $currPage, $count, $page, $mobi,$condition);
  		break;
  	case 'frameinit':
  		echo $tg::getFooTableHTML4Frame($tableName, $count, $mobi);
  		break;
  	case 'rowedit':	
  		echo $tg::getRowEditPage($row, $mobi);
  		break;
  	case 'rowadd':	
  		echo $tg::getRowAddPage($row, $mobi);
  		break;
  	default:
  		echo 'wrong';
  }
?>