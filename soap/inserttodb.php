<?php
include_once('api.bootstraps.php');

$code = $_REQUEST['code'];
if($code === 'david') {
	$allowedtp = array('image/gif','image/pjpeg','image/jpeg','image/png','image/tiff');
	$urlhead = 'http://7xjm3j.com1.z0.glb.clouddn.com/';
	$colstr = $_REQUEST['colstr'];
	$colArray = explode(',',$colstr);
	$tableName = array_shift($colArray);
	$data = array();
	foreach($colArray as $col) {
		$type = getColumnType($tableName,$col);
     	if($type === 'picture') {
     		$filetype = $_FILES[$col]['type'];
	  		if(!in_array($filetype,$allowedtp)) {
	  			continue;
	  		}
	  		$filename = createGUID().':'.time();
	  		switch($filetype) {
	  			case 'image/jpeg':
	  				$filename .= '.jpg';
	  				break;
	  			case 'image/pjpeg':
	  				$filename .= 'jpg';
	  				break;
	  			case 'image/gif':
	  				$filename .= '.gif';
	  				break;
	  			case 'image/png':
	  				$filename .= '.png';
	  				break;
	  			case 'image/tiff':
	  				$filename .= '.tiff';
	  				break;
	  		}
  			$file = file_get_contents($_FILES[$col]['tmp_name']);
  			$length = strlen($file);
  			//var_dump($length);
  			QNWrite($filename,$file);
  			$filename = $urlhead.$filename;
  			$exif = file_get_contents($filename.'?exif');
  			$data[$col] = $filename;
  			$data['info'] = str_replace('"', '”', $exif);
  			$data['volumn'] = $length;
     	} else if($type === 'readonly') {
     		$filetype = $_FILES['pic']['type'];  
	  		if(!in_array($filetype,$allowedtp)) {
	  			continue;
	  		}/**/ 
	  		$filename = createGUID().':'.time();   
	  		switch($filetype) {
	  			case 'image/jpeg':
	  				$filename .= '.jpg';
	  				break;
	  			case 'image/pjpeg':
	  				$filename .= 'jpg';
	  				break;
	  			case 'image/gif':
	  				$filename .= '.gif';
	  				break;
	  			case 'image/png':
	  				$filename .= '.png';
	  				break;
	  			case 'image/tiff':
	  				$filename .= '.tiff';
	  				break;
	  		}
  			$file = file_get_contents($_FILES['pic']['tmp_name']); 
  			$length = strlen($file); 
  			QNWrite($filename,$file);
  			$filename = $urlhead.$filename;
  			
  			$exif = file_get_contents($filename.'?exif');
  			$data['url'] = $filename;
  			$data['info'] = str_replace('"', '”', $exif);
  			$data['volumn'] = $length; 
  			
     		//continue;
     	}else if($type === 'select,style_pic') {    
     		$filetype = $_FILES[$col]['type']; 
	  		if(!in_array($filetype,$allowedtp)) {
	  			continue;
	  		}/**/ 
	  		$filename = createGUID().':'.time();   
	  		switch($filetype) {
	  			case 'image/jpeg':
	  				$filename .= '.jpg';
	  				break;
	  			case 'image/pjpeg':
	  				$filename .= 'jpg';
	  				break;
	  			case 'image/gif':
	  				$filename .= '.gif1';
	  				break;
	  			case 'image/png':
	  				$filename .= '.png';
	  				break;
	  			case 'image/tiff':
	  				$filename .= '.tiff';
	  				break;
	  		}
  			$file = file_get_contents($_FILES[$col]['tmp_name']);  
  			$length = strlen($file); 
  			UPWrite($filename,$file);
  			$filename = 'http://image.colored-stone.com.cn/stylebase/'.$filename;  
  			$data['picurl'] = $filename; 
  			
     		//continue;
     	} else if($type === 'select,category_image'||$type === 'select,article_image') {   
     		$filetype = $_FILES[$col]['type'];  
	  		if(!in_array($filetype,$allowedtp)) {
	  			continue;
	  		}/**/ 
	  		$filename = createGUID().':'.time();   
	  		switch($filetype) {
	  			case 'image/jpeg':
	  				$filename .= '.jpg';
	  				break;
	  			case 'image/pjpeg':
	  				$filename .= 'jpg';
	  				break;
	  			case 'image/gif':
	  				$filename .= '.gif1';
	  				break;
	  			case 'image/png':
	  				$filename .= '.png';
	  				break;
	  			case 'image/tiff':
	  				$filename .= '.tiff';
	  				break;
	  		}
  			$file = file_get_contents($_FILES[$col]['tmp_name']); 
  			$length = strlen($file); 
  			QNWrite($filename,$file);
  			$filename = $urlhead.$filename;
  			
  			$exif = file_get_contents($filename.'?exif');
  			$data['url'] = $filename.'!small';
  			$data['info'] = str_replace('"', '”', $exif);
  			$data['volumn'] = $length;  
     	}   else {
     		$data[$col] = $_POST[$col];
     	}
	}                   
	if($tableName == "Shop_Product_Info"){
		if ($data['url'] !=null ||$data['url'] !='') {	
			$res = productUploadImage($data,'4'); 		
			foreach ($data as $key => $value) {
				if ($key == "url"|| $key == "info"|| $key == "volumn") {
					unset($data[$key]); 
				}
			}
			if ($res) {
				$imageid = GetLastID("Base_Image_Info");   
		    	$data['pic'] =  $imageid[0]['id'];  
			}
		}/**/
		//array_splice($data);  
	}elseif ($tableName == "Shop_Category") {
		if ($data['url'] !=null ||$data['url'] !='') {	
			$res = productUploadImage($data,'6'); 	 
			if ($res) {
				$imageid = GetLastID("Base_Image_Info");   
		    	$data['pic'] =  $imageid[0]['id'];  
			}/**/
			foreach ($data as $key => $value) {
				if ($key == "url"|| $key == "info"|| $key == "volumn") {
					unset($data[$key]); 
				}
			}
		}
	}elseif ($tableName == "Maga_Article_Info") {
		if ($data['url'] !=null ||$data['url'] !=''){	
			$res = productUploadImage($data,'2'); 	 
			if ($res) {
				$imageid = GetLastID("Base_Image_Info");   
		    	$data['mainpic'] =  $imageid[0]['id'];  
			}/**/
			foreach ($data as $key => $value) {
				if ($key == "url"|| $key == "info"|| $key == "volumn") {
					unset($data[$key]); 
				}
			}
		}      
	} 
	
	$sql = GetInsertSQL($tableName,$data); 
	//$mysql = new SaeMysql(); 
	$ret = MySQLRunSQL($sql);
	if($ret) {
		echo '插入成功，请关闭页面，并刷新列表。';
	} else {
		echo $sql.'<br>'.$ret;
	}
}