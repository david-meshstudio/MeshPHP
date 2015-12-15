<html>
<head>
 	<meta charset="utf-8">
</head>
<body>
<?php
include_once('../admin.bootstraps.php');

$dbea = new DBEntityAdaptor('product');
$productList = $dbea->ReadFromDBByID('product','4263');
var_dump($productList);

// $s = 'd';
// $ss = explode(',', $s);
// var_dump($ss);

// $keyPara = array('d');
// $name = $keyPara[2];
// $name = strpos($name, ',') > 0 ? explode(',', $name) : $name;
// var_dump($name);
// echo json_encode($productList);
// $ret = $dbea->DeleteToDB($productList,'product');
// echo json_encode($productList);

// test($productList);
// echo json_encode($productList);

// function test(&$productList) {
// 	$productList[0]['id'] = '3939393hgkn';
// }
?>
</body>
</html>