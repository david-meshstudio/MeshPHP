<?php
class TableGenerator{
	public function __construct() {
		
	}
		
	public function __destruct() {
		
	}
	//<img id="reload-'.$count.'" src="http://csxrm.sinaapp.com/admin/images/reload_small.png" alt="reloadicon" onclick="reloadTab('.$count.');"/>
  public static function genJQP($tableName,$max_page,$arr_count,$max_row,$max_dbrow,$count,$method,$dp=false, $mobi) {
  	$arr_count = $arr_count === null || $arr_count === '' ? 0 : $arr_count;
    $htmltoolbar = self::genToolbarHTMLCode($tableName,$count);
    $htmlsearch = '<div style="position:absolute;top:7px;right:350px;"><input id="searchinput-'.$count.'" /><img src="images/toolbar/search.png" style="position:absolute;top:2px;left:203px;" data-tablename="'.$tableName.'" data-count="'.$count.'" onclick="getSearchResult(this);" id="searchbutton-'.$count.'" /><script>$("#searchinput-'.$count.'").keyup(function(e){if(e.keyCode==13){getSearchResult($("#searchbutton-'.$count.'"));}});</script></div>';
  	$htmljqp = '<div class="pagination" id="jqp'.$count.'" style="position:absolute;right:4px;top:9px;">
            <a href="#" class="first" data-action="first">&laquo;</a>
            <a href="#" class="previous" data-action="previous">&lsaquo;</a>
            <input id="jqpinput'.$count.'" type="text" data-current-page="1">
            <a href="#" class="next" data-action="next">&rsaquo;</a>
            <a href="#" class="last" data-action="last">&raquo;</a>     
          </div>';
    $htmldp = '<div class="lb">
          	<b>Date From</b>
          </div>
          <div class="dp">
            <input type="text" class="datepicker" id="dateFrom'.$count.'" readOnly onClick="javascript:pickDate(this);" />
          </div>
          <div class="lb">
          	<b>To</b>
          </div>
          <div class="dp">
            <input type="text" class="datepicker" id="dateTo'.$count.'" readOnly onClick="javascript:pickDate(this);" />
          </div>
          <button id="buttonGO'.$count.'" style="height:20px;font-size:9px;width:auto;">GO</button>';
    $htmlbr = '</div>';
    $js = '<script>function reloadTab(count) {
      	var mid = $(".addtabs:has(#reload-"+count+")").attr("id");
      	$lis = $("#tabul>li");
      	$.each($lis,function(key,val) {
        		if($(this).attr("value") === mid) {
        			var tid = $(this).attr("id");
        			$("#tabs-"+count).html(gentabcontent(tid, count));
        		}
      		});
      }</script>';
    $dpjs = "<script>
    	$( '#dateFrom".$count."' ).prop('readOnly', true).datepicker({dateFormat:'yy-mm-dd',selectOtherMonths: true,showOtherMonths: true});
      $( '#dateTo".$count."' ).prop('readOnly', true).datepicker({dateFormat:'yy-mm-dd',selectOtherMonths: true,showOtherMonths: true});
      $('#buttonGO".$count."').button();
    </script>";
    $jqpscript = "<script>
    	$('#jqp".$count."').jqPagination({
    		cid:".$count.",
    	  current_page:1,
    	  link_string:'/?page={page_number}',
    	  max_page:".$max_page.",
    	  page_string:'Page {current_page} of {max_page}',
    	  paged:function(page){
    	    // do something with the page variable
    	    //$('#row0').css('display','none');
    	    var arr_count=".$arr_count.";
    	    var max_row=".$max_row.";
    	    var max_dbrow=".$max_dbrow.";
    	    var batchsize=".(int)($max_dbrow/$max_row).";
    	    var dbpage=parseInt((page-1)/batchsize)+1;
    	    var firstrownumber=(dbpage-1)*max_dbrow;
    	    if($('#t".$count."row'+firstrownumber).length <= 0 && ".$max_page." > 0 ) {
    	      var xmlHttp = createXMLHttpRequest();
            var url = './includes/tabcontent.php?tableName=".$tableName."&currPage='+dbpage+'&method=".$method."&count=".$count."&page='+page+'&mobi=".$mobi."';
            xmlHttp.open('GET', url, true);// 异步处理返回   
            xmlHttp.onreadystatechange =  function() { 
              if (xmlHttp.readyState == 4 && xmlHttp.status  == 200) {
              	$('#table".$count." tbody').append(xmlHttp.responseText);
              	//$('.footable').css('display','none');
              }
            };
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');  
            xmlHttp.send();
    	    }
    	    for(var i=0;i<$arr_count;i++) {
    	      if(i>=max_row*(page-1) && i<max_row*page) {
    	        $('#t".$count."row'+i).css('display','table-row');
    	      } else {
    	      	$('#t".$count."row'+i).css('display','none');
    	      }
    	    }
    	  }
    	});
    	</script>";
    $html = '';
    if($dp) {
    	$html = $htmltoolbar.$htmlsearch.$htmljqp.$htmldp.$htmlbr.$js.$dpjs.$jqpscript;
    } else {
    	$html = $htmltoolbar.$htmlsearch.$htmljqp.$htmlbr.$js.$jqpscript;
    }
    echo $html;
  }
  
  public static function getFooTableHTML4Frame($url,$count, $mobi) {
  	$result = '<iframe src="'.$url.'" width=100% height=70% frameborder=0></iframe>';
    echo $result;
  }
  
  public static function addFooTableHTML4DB($tableName,$batchsize,$max_row,$curr_page,$count,$page, $mobi,$condition='') {
  	$max_dbrow = $batchsize*$max_row;
  	$ada = new MDBAdaptor();
  	$arr = array();
  	$arr = $ada->Read4DBPart($tableName,$max_dbrow,$curr_page);
  	$basecount = $max_dbrow*($curr_page-1);

    $result = self::genTableHTMLCodeAdd($arr, $count, $max_row, $mobi, $page, $basecount,$condition);
    echo $result;
  }
    
  public static function getFooTableHTML4DB($tableName,$batchsize,$max_row,$curr_page, $count, $mobi,$condition='') {
  	$max_dbrow = $batchsize*$max_row;
  	$ada = new MDBAdaptor();
  	$arr_count = $ada->Read4DBCount($tableName,$condition);
  	
  	$max_page = (int)($arr_count/$max_row);
  	if($arr_count % $max_row > 0) {
  		$max_page += 1;
  	}
  	$max_dbpage = (int)($arr_count/$max_dbrow);
  	if($arr_count % $max_dbrow) {
  		$max_dbpage += 1;
  	}
  	$arr = array();
  	$arr = $ada->Read4DBPart($tableName,$max_dbrow,1,$condition);
	
    self::genJQP($tableName,$max_page,$arr_count,$max_row,$max_dbrow, $count, 'add', false, $mobi);
  	$result = self::genTableHTMLCode($arr, $count, $max_row, $mobi);
  	return $result;
  }
  
  public static function getRowEditPage($row, $mobi) {
    //var_dump($row);
  	$row = str_replace('||','"',$row);
    //var_dump($row);
  	$row = json_decode($row,true);
    //var_dump($row);
  	$colstr = $row['Type'].',ID,';
  	$res = '<div><form class="editform" method="post" target="_blank" enctype="multipart/form-data" action="./soap/savetodb.php" onsubmit="return submitedit($(this));"><table><tr><td>ID:</td><td><input type="text" name="ID" value="'.$row['ID'].'" readonly="readonly"/></td></tr>';
  	foreach($row['Properties'] as $k=>$v) {
      $title = getColumnTitle($row['Type'],$k);
      $type = getColumnType($row['Type'],$k);
      if ($title === '' || $title === null || $title === false) {
        continue;
      }
  		if($k === 'Content' || $k === 'Remark' || $k === 'Desc' || $k === 'descript' || $k === 'remark' || $k === 'detail' || $k === 'abstract') {
        $w = $mobi === 'y' ? '20' : '50';
        $res .= '<tr><td>'.$title.':</td><td><textarea name="'.$k.'" cols="'.$w.'" rows="4">'.$v.'</textarea></td></tr>';
  		} else {
        $w = $mobi === 'y' ? '214px' : '521px';
  			//$res .= '<tr><td>'.$title.':</td><td><input type="text" name="'.$k.'" value="'.$v.'" style="width:'.$w.';"/></td><tr>';
        if($type === 'picture') {
          $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
          $res .= '<tr><td></td><td><input type="hidden" name="'.$k.'_old" value="'.$v.'" /></td><tr>';
          $res .= "<tr><td></td><td><a href='".$v."'' target='_blank' ><image style='border-style:solid;border-width:1px;border-color:#bbb;max-width:".$w."' src='".$v."' /></td><td></td></tr>";
        } else if($type === 'readonly') {
          $res .= '<tr><td>'.$title.':</td><td><input type="text" readonly="readonly" name="'.$k.'" value="'.$v.'" style="width:'.$w.';"/></td><tr>';
        } else if(strpos($type, 'select') === 0) {
          $typepara = explode(',', $type); 
          switch ($typepara[1]) {
            case 'image_type':
              $choices = getImageTypeChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'user_gender':
              $choices = getUserGenderChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'user_level':
              $choices = getUserLevelChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'user_image':
              $choices = getImageChoice('1');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';

              /*$image = getImageByID($v);
              $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
              $res .= '<tr><td></td><td><input type="hidden" name="'.$k.'_old" value="'.$image.'" /></td><tr>';
              $res .= "<tr><td></td><td><a href='".$image."'' target='_blank' ><image style='border-style:solid;border-width:1px;border-color:#bbb;max-width:".$w."' src='".$image."' /></td><td></td></tr>";*/
              break;
            case 'user_meb':
              $choices = getUserChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v); 
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'article_type':
              $choices = getArticleTypeChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'article_user':
              $choices = getArticleUserChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'article_image':
             /*$choices = getImageChoice('2');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';*/  
              $image = getImageByID($v);              
              $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
              $res .= '<tr><td></td><td><input type="hidden" name="'.$k.'_old" value="'.$image.'" /></td><tr>';
              $res .= "<tr><td></td><td><a href='".$image."'' target='_blank' ><image style='border-style:solid;border-width:1px;border-color:#bbb;max-width:".$w."' src='".$image."' /></td><td></td></tr>";
              break;
            case 'category_parent':
              $choices = getCategoryParentChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'category_type':
              $choices = getCategoryTypeChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'category_image':
              /*$choices = getImageChoice('6');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';*/
              
              $image = getImageByID($v);
              $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
              $res .= '<tr><td></td><td><input type="hidden" name="'.$k.'_old" value="'.$image.'" /></td><tr>';
              $res .= "<tr><td></td><td><a href='".$image."'' target='_blank' ><image style='border-style:solid;border-width:1px;border-color:#bbb;max-width:".$w."' src='".$image."' /></td><td></td></tr>";
              break;
            case 'product_image':
              /*$choices = getImageChoice('4');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';*/
              $image = getImageByID($v);
              $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
              $res .= '<tr><td></td><td><input type="hidden" name="'.$k.'_old" value="'.$image.'" /></td><tr>';
              $res .= "<tr><td></td><td><a href='".$image."'' target='_blank' ><image style='border-style:solid;border-width:1px;border-color:#bbb;max-width:".$w."' src='".$image."' /></td><td></td></tr>";
                //!small
              break;
            case 'general_image':              
              $choices = getImageChoice('0');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';              
              break;
            case 'label_image':
              $choices = getImageChoice('7');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'style_pic':
                //$choices = getImageChoice('7');
                //$vs = explode(',', $choices[$value]); 
              $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
              $res .= '<tr><td></td><td><input type="hidden" name="'.$k.'_old" value="'.$v.'" /></td><tr>';
              $res .= "<tr><td></td><td><a href='".$v."'' target='_blank' ><image style='border-style:solid;border-width:1px;border-color:#bbb;max-width:".$w."' name='style_pic' src='".$v."' /></td><td></td></tr>";
              
                break;
            default:
              # code...
              break;
          }
        } else {
          $res .= '<tr><td>'.$title.':</td><td><input type="text" name="'.$k.'" value="'.$v.'" style="width:'.$w.';"/></td><tr>';
        }
  		}
  		$colstr .= $k.',';
  	}
    $w = $mobi === 'y' ? '214px' : '521px';
    // $div = genRelationDIV($row['Type'],$row['ID'],$w);
  	$colstr = substr($colstr,0,-1);
  	$res .= '<tr>'.$div.'</tr><tr><td><input type="hidden" name="code" value="david"/><input type="hidden" name="colstr" value="'.$colstr.'"/></td><td><input type="submit" value="OK"/></td></tr></table></form></div>';
  	return $res;
  }
  
  public static function getRowAddPage($tableName, $mobi) {
  	$ada = new MDBAdaptor();
  	$arr = array();
  	$arr = $ada->Read4DBPart($tableName,1,1);
    //var_dump($arr);
  	$row = $arr[0];
  	$colstr = $tableName.',ID,';
  	$res = '<div><form class="editform" method="post" target="_blank" enctype="multipart/form-data" action="./soap/inserttodb.php" onsubmit="return submitadd($(this));"><table><tr><td>ID:</td><td><input type="text" name="ID" value="-" readonly="readonly"/></td></tr>';
  	foreach($row->Properties as $k=>$v) {
      $title = getColumnTitle($row->Type,$k);
      $type = getColumnType($row->Type,$k);
      //var_dump($type.','.(strpos($type, 'start') === 0));
      $default = getColumnDefault($row->Type,$k);
      if ($title === '' || $title === null || $title === false) {
        continue;
      }
  		if($k === 'Content' || $k === 'Remark' || $k === 'Desc' || $k === 'descript' || $k === 'remark' || $k === 'detail' || $k === 'abstract') {
        $w = $mobi === 'y' ? '20' : '50';
  			$res .= '<tr><td>'.$title.':</td><td><textarea name="'.$k.'" cols="'.$w.'" rows="4"></textarea></td></tr>';
  		} else {
        $w = $mobi === 'y' ? '214px' : '521px';
        $v = $default ? $default : '';// === false ? '' : $default;
        if($type === 'picture') {
          $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="'.$v.'" style="width:'.$w.';"/></td><tr>';
        } else if($type === 'readonly') {
          $res .= '<tr><td>'.$title.':</td><td><input type="text" readonly="readonly" name="'.$k.'" value="'.$v.'" style="width:'.$w.';"/></td><tr>';
        } else if(strpos($type, 'select') === 0) {
          $typepara = explode(',', $type);
          switch ($typepara[1]) {
            case 'image_type':
              $choices = getImageTypeChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'user_gender':
              $choices = getUserGenderChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'user_level':
              $choices = getUserLevelChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'user_image':
              $choices = getImageChoice('1');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              /*$res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';*/
              break;
            case 'user_meb':
              $choices = getUserChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v); 
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'article_type':
              $choices = getArticleTypeChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'article_user':
              $choices = getArticleUserChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'article_image':
             /*$choices = getImageChoice('2');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';*/ 

              $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
              break;
            case 'category_parent':
              $choices = getCategoryParentChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'category_type':
              $choices = getCategoryTypeChoice();
              $css = "width:".$w.";";
              $selectItem = genSelectItem($choices,$css,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'category_image':
              /*$choices = getImageChoice('6');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';*/ 
              $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
              break;
            case 'product_image':
             $choices = getImageChoice('4');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
                
              $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
              break;
            case 'general_image':
              $choices = getImageChoice('0');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'label_image':
              $choices = getImageChoice('7');
              $selectItem = genImageSelectDIVLimited($choices,$w,$k,$v);
              $res .= '<tr><td>'.$title.':</td><td>'.$selectItem.'</td><tr>';
              break;
            case 'style_pic': 
                $res .= '<tr><td>'.$title.':</td><td><input type="file" accept="image/*" name="'.$k.'" value="" style="width:'.$w.';"/></td><tr>';
                $res .= '<tr><td></td><td><input type="hidden" name="'.$k.'" value="'.$v.'" /></td><tr>';
                $res .= "<tr><td></td><td><a href='".$v."'' target='_blank' ><image style='border-style:solid;border-width:1px;border-color:#bbb;max-width:".$w."' name='style_pic' src='".$v."' /></td><td></td></tr>";   
                break;
            default:
              # code...
              break;
          }
        } else {
          $res .= '<tr><td>'.$title.':</td><td><input type="text" name="'.$k.'" value="'.$v.'" style="width:'.$w.';"/></td><tr>';
        }
  		}
  		$colstr .= $k.',';
  	}
  	$colstr = substr($colstr,0,-1);
  	$res .= '<tr><td><input type="hidden" name="code" value="david"/><input type="hidden" name="colstr" value="'.$colstr.'"/></td><td><input type="submit" value="OK"/></td></tr></table></form></div>';
  	return $res;
  }
  
  private static function genTableHTMLCode($tableObj, $count, $max_row, $mobi) {
  	$result = '<table class="footable" id="table'.$count.'"><thead>';
  	if(count($tableObj) > 0 && $tableObj[0]->Properties != null) {
  		$result .= '<tr>';  
    	$keys = array_keys($tableObj[0]->Properties);
      $jumpIndex = array();
    	for($i = 0;$i < count($keys);$i++) {
        //$title = getColumnTitle($tableObj[0]->Type,$keys[$i]);
        $title = $mobi === 'y' ? getMobileColumnTitle($tableObj[0]->Type,$keys[$i]) : getColumnTitle($tableObj[0]->Type,$keys[$i]);
        if ($title === '' || $title === null || $title === false) {
          $jumpIndex[] = $i;
          continue;
        }
    		$result .= '<th';
    		if($i == 0) {
    			$result .= ' data-sort-initial="true"';
    		}
    		$result .= '><span title="'.$keys[$i].'">'.$title.'</span></th>';
    	}
    	//$result .= '<th><span title="button">操作</span></th>';
    	$result .= '</tr></thead><tbody>';
    	for($i = 0;$i < count($tableObj);$i++) {
    		$obj = $tableObj[$i];
    		if($obj->ID == '') break;
      		if($i < $max_row) {// onClick="change()"   onMouseOut="out()"
  				$result .= '<tr class="datarow" id="t'.$count.'row'.$i.'"  >';
  			} else {
  				$result .= '<tr class="datarow" id="t'.$count.'row'.$i.'" style="display:none">';
  			}
    		for($j = 0;$j < count($keys);$j++) {
          if(in_array($j, $jumpIndex)) continue;
          $type = getColumnType($obj->Type,$keys[$j]);
          $value = $obj->Properties[$keys[$j]];
          //var_dump($type);
          if($type === 'picture') {//!small
            $result .= '<td><a href="'.$value.'" target="_blank"><img src="'.$value.'" style="width:50px;" /></a></td>';
          } else if(strpos($type, 'select') === 0) {
            $typepara = explode(',', $type);
            switch ($typepara[1]) {
              case 'image_type':
                $choices = getImageTypeChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'user_gender':
                $choices = getUserGenderChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'user_level':
                $choices = getUserLevelChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'user_image':
                $choices = getImageChoice('1');
                $vs = explode(',', $choices[$value]);//!small
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break;
              case 'user_meb':
                $choices = getUserChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'article_type':
                $choices = getArticleTypeChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'article_user':
                $choices = getArticleUserChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'article_image':
                /*$choices = getImageChoice('2');
                $vs = explode(',', $choices[$value]);//!small
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                */

                $choices = getImageChoice('2');
                $vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                          
                break;
              case 'category_parent':
                $choices = getCategoryParentChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'category_type':
                $choices = getCategoryTypeChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'category_image':
                $choices = getImageChoice('6');
                $vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break;
              case 'product_image':
                $choices = getImageChoice('4');
                $vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                //!small
                break;
              case 'general_image':
                $choices = getImageChoice('0');
                $vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break; 
              case 'label_image':
                $choices = getImageChoice('7');
                $vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break;
              case 'style_pic':
                //$choices = getImageChoice('7');
                //$vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$value.'" target="_blank"><img src="'.$value.'" style="width:50px;" /></a></td>';
                break;
              default:
                # code...
                break;
            }
          } else {
            if ($obj->Properties[$keys[$j]]===null||$obj->Properties[$keys[$j]]==='') {
              $result .= '<td>&nbsp</td>';
            }else{
              if ($keys[$j] === 'status') {  
                if ($obj->Properties[$keys[$j]] === '1') {
                    $result .= '<td>'.'是'.'</td>';
                  }else{
                    $result .= '<td>'.'否'.'</td>';
                  }    
              }else{         
                  $result .= '<td>'.$obj->Properties[$keys[$j]].'</td>';
                }
            }
          }    
    		}   
         
        $result .='<script type="text/javascript"> 
         $(document).ready(function () {             
            $("#t'.$count.'row'.$i.'").click(function(){  
                var editstr =  $(this).find("input").val();   
                var delstr =  $(this).find("p").text();   
                var pubstr =  $(this).find("span").html();    
                if($(this).attr("style").indexOf("background-color") != (-1)){     
                  $(this).css("background-color", "").removeAttr("selected");

                  $("#tb'.$count.'").children("#delete'.$count.'").removeAttr("onclick");
                  $("#tb'.$count.'").children("#edit'.$count.'").removeAttr("onclick"); 
                  $("#tb'.$count.'").children("#pub'.$count.'").removeAttr("onclick"); 
                }else{            
                  $(this).css("background-color", "#DDDDFF").attr("selected", "");
                  $(this).siblings().css("background-color", "").removeAttr("selected");

                  $("#tb'.$count.'").children("#delete'.$count.'").attr("onclick", delstr);
                  $("#tb'.$count.'").children("#edit'.$count.'").attr("onclick", editstr); 
                  $("#tb'.$count.'").children("#pub'.$count.'").attr("onclick", pubstr); 
                }  

                $(this).attr("ondblclick", editstr);
            });                 
        }); </script>'; 
 
  			$objstr = json_encode($obj);
  			$objstr2 = str_replace('"','',$objstr);
  			$objstr = str_replace('"','||',$objstr); 
        $datas = GetDatabyID($obj->Type,$obj->ID);
        $tablename = $obj->Type;
        $datastr = base64_encode(json_encode($datas));  
      

        $result .= '<td   style="display:none"  id="data_'.$count.'"> <input type="text" name="edit" id="editdata_'.$count.'" value="para=\''.$objstr.'\';total_tabs++;addtab(total_tabs,\'Edit\',\'edit-'.$obj->ID.'\');"  />';

        $result .= '<p id="deldata_'.$count.'">para=\''.$obj->ID.','.$obj->Type.'\';$(\'#dialogDel\').dialog(\'open\');$(\'#confirmInfo\').html(\''.$objstr2.'\');</p> ';

        $result .= '<span id="pubdata_'.$count.'">pubdata(\''.$datastr.'\',\''.$tablename.'\');</span> </td>';

  			$result .= '<td style="display:none"> <button class="ui-button ui-widget ui-state-default ui-button-text-only" id="edit-'.$obj->ID.'" onclick="para=\''.$objstr.'\';total_tabs++;addtab(total_tabs,\'Edit\',\'edit-'.$obj->ID.'\');" > <span class="ui-button-text">Edit</span> </button> <button class="ui-button ui-widget ui-state-default ui-button-text-only" id="del-'.$obj->ID.'" onclick="para=\''.$obj->ID.','.$obj->Type.'\';$(\'#dialogDel\').dialog(\'open\');$(\'#confirmInfo\').html(\''.$objstr2.'\');" > <span class="ui-button-text">Del</span> </button> <button class="ui-button ui-widget ui-state-default ui-button-text-only"  id="publish-'.$obj->ID.'" onclick="pubdata(\''.$datastr.'\',\''.$tablename.'\');"> <span class="ui-button-text">Pub</span></button></td>';
    		$result .= '</tr>';
    	}
    } else {
    	$result .= '</thead><tbody>';
    }
  	$result .= '</tbody></table>';
  	return $result;
  }

  private static function genTableHTMLCodeAdd($tableObj, $count, $max_row, $mobi, $page, $basecount) {
    $result = '';
    if(count($tableObj) > 0 && $tableObj[0]->Properties != null) {
      $keys = array_keys($tableObj[0]->Properties);
      $jumpIndex = array();
      for($i = 0;$i < count($keys);$i++) {
        //$title = getColumnTitle($tableObj[0]->Type,$keys[$i]);
        $title = $mobi === 'y' ? getMobileColumnTitle($tableObj[0]->Type,$keys[$i]) : getColumnTitle($tableObj[0]->Type,$keys[$i]);
        if ($title === '' || $title === null || $title === false) {
          $jumpIndex[] = $i;
          continue;
        }
      }
      for($i = 0;$i < count($tableObj);$i++) {
        $obj = $tableObj[$i];
        
        if($obj->ID == '') break;
        $ri = $i+$basecount;
        if($ri >= $max_row*($page-1) && $ri < $max_row*$page) {
          $result .= '<tr class="datarow" id="t'.$count.'row'.$ri.'">';
        } else {
          $result .= '<tr class="datarow" id="t'.$count.'row'.$ri.'" style="display:none">';
        }
        for($j = 0;$j < count($keys);$j++) {
          if(in_array($j, $jumpIndex)) continue;
          $type = getColumnType($obj->Type,$keys[$j]);
          $value = $obj->Properties[$keys[$j]];
          //var_dump($type);
          if($type === 'picture') {
            $result .= '<td><a href="'.$value.'" target="_blank"><img src="'.$value.'" style="width:50px;" /></a></td>';
          } else if(strpos($type, 'select') === 0) {
            $typepara = explode(',', $type);
            switch ($typepara[1]) {
              case 'image_type':
                $choices = getImageTypeChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'user_gender':
                $choices = getUserGenderChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'user_level':
                $choices = getUserLevelChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'user_meb':
                $choices = getUserChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'user_image':
                $choices = getImageChoice('1');
                $vs = explode(',', $choices[$value]);//!small
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break;
              case 'article_type':
                $choices = getArticleTypeChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'article_user':
                $choices = getArticleUserChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'article_image':
                $choices = getImageChoice('2');
                $vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break;
              case 'category_parent':
                $choices = getCategoryParentChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'category_type':
                $choices = getCategoryTypeChoice();
                $result .= '<td>'.$choices[$value].'</td>';
                break;
              case 'category_image':
                $choices = getImageChoice('6');
                $vs = explode(',', $choices[$value]);//!small
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break;
              case 'product_image':
                $choices = getImageChoice('4');
                $vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break;
              case 'general_image':
                $choices = getImageChoice('0');
                $vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break; 
              case 'label_image':
                $choices = getImageChoice('7');
                $vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$vs[1].'" target="_blank"><img src="'.$vs[1].'" style="width:50px;" /></a></td>';
                break;
              case 'style_pic':
                //$choices = getImageChoice('7');
                //$vs = explode(',', $choices[$value]);
                $result .= '<td><a href="'.$value.'" target="_blank"><img src="'.$value.'" style="width:50px;" /></a></td>';
                break;
              default:
                # code...
                break;
            }
          } else {
            if ($keys[$j] === 'status') {  
                if ($obj->Properties[$keys[$j]] === '1') {
                    $result .= '<td>'.'是'.'</td>';
                  }else{
                    $result .= '<td>'.'否'.'</td>';
                  }    
              }else{         
                  $result .= '<td>'.$obj->Properties[$keys[$j]].'</td>';
              }
          }
        }

        $result .='<script type="text/javascript">        
          $(document).ready(function () {             
            $("#t'.$count.'row'.$ri.'").click(function(){              
              var editstr =  $(this).find("input").val();   
              var delstr =  $(this).find("p").text();   
              var pubstr =  $(this).find("span").html();      
              var ishve = typeof($(this).attr("selected"));
              if(ishve=="undefined") {
                $(this).css("background-color", "#DDDDFF").attr("selected", "");
                $(this).siblings().css("background-color", "").removeAttr("selected");

                $("#tb'.$count.'").children("#delete'.$count.'").attr("onclick", delstr);
                $("#tb'.$count.'").children("#edit'.$count.'").attr("onclick", editstr); 
                $("#tb'.$count.'").children("#pub'.$count.'").attr("onclick", pubstr);   
              }else{
                $(this).css("background-color", "").removeAttr("selected");
                $("#tb'.$count.'").children("#delete'.$count.'").removeAttr("onclick");
                $("#tb'.$count.'").children("#edit'.$count.'").removeAttr("onclick"); 
                $("#tb'.$count.'").children("#pub'.$count.'").removeAttr("onclick"); 
              }

              $(this).attr("ondblclick", editstr);
            });           
          }); </script>';

 
        $objstr = json_encode($obj);
        $objstr2 = str_replace('"','',$objstr);
        $objstr = str_replace('"','||',$objstr); 
        $datas = GetDatabyID($obj->Type,$obj->ID);
        $tablename = $obj->Type;
        $datastr = base64_encode(json_encode($datas));    


        $result .= '<td   style="display:none" id="data_'.$count.'"><input type="text" name="edit" id="editdata_'.$count.'" value="para=\''.$objstr.'\';total_tabs++;addtab(total_tabs,\'Edit\',\'edit-'.$obj->ID.'\');"  />';

        $result .= '<p>para=\''.$obj->ID.','.$obj->Type.'\';$(\'#dialogDel\').dialog(\'open\');$(\'#confirmInfo\').html(\''.$objstr2.'\');</p> ';

        $result .= '<span>pubdata(\''.$datastr.'\',\''.$tablename.'\');</span> </td>';

        $result .= '<td style="display:none"> <button class="ui-button ui-widget ui-state-default ui-button-text-only" id="edit-'.$obj->ID.'" onclick="para=\''.$objstr.'\';total_tabs++;addtab(total_tabs,\'Edit\',\'edit-'.$obj->ID.'\');" > <span class="ui-button-text">Edit</span> </button> <button class="ui-button ui-widget ui-state-default ui-button-text-only" id="del-'.$obj->ID.'" onclick="para=\''.$obj->ID.','.$obj->Type.'\';$(\'#dialogDel\').dialog(\'open\');$(\'#confirmInfo\').html(\''.$objstr2.'\');" > <span class="ui-button-text">Del</span> </button> <button class="ui-button ui-widget ui-state-default ui-button-text-only"  id="publish-'.$obj->ID.'" onclick="pubdata(\''.$datastr.'\',\''.$tablename.'\');"> <span class="ui-button-text">Pub</span></button></td>';
        $result .= '</tr>';
      }
    }
    return $result;
  }

  private static function genToolbarHTMLCode($tableName,$id) {  
    $res = '<div class="toolbar" id="tb'.$id.'" data-count="'.$id.'" style="border:1px solid #CDCDCD;margin:4px 1px;padding:3px;background-color:white;position:relative;display:block;">';
    
    $res .= '<img src="images/toolbar/Add.png" width="35px" height="35px" id="add" style="cursor:pointer;" id="add'.$id.'" title="新增" onclick="para=\''.$tableName.'\';total_tabs++;addtab(total_tabs,para,\''.add.'\');" />';
    
    $res .= '<img src="images/toolbar/delete.png" width="35px" height="35px" id ="delete'.$id.'" style="cursor:pointer;" title="删除"  />';
    $res .= '<img src="images/toolbar/edit_64.png" width="35px" height="35px" id="edit'.$id.'" style="cursor:pointer;" title="编辑" />'; 
        //$res .= '<img src="images/toolbar/diskette.png" width="35px" height="35px" style="cursor:pointer;" />';
    $res .= '<img src="images/toolbar/checkmark.png" width="35px" height="35px" style="cursor:pointer;" title="审核" id="pub'.$id.'" />';
    $res .= '<img src="images/toolbar/update5.png" width="35px" height="35px" style="cursor:pointer;" id="reload-'.$id.'"  title="刷新" alt="reloadicon" onclick="reloadTab('.$id.');" />';

    
    
/**/
    $res .='<script type="text/javascript"> 
      $("#tb'.$id.' *").click(function(e){
        if(e.target == $("#edit'.$id.'")[0]){ 
          var editisclick = typeof($("#edit'.$id.'").attr("onclick"));  
          if (editisclick=="undefined") {
            alert("请选择需要编辑的数据");
          }
        }else if(e.target == $("#delete'.$id.'")[0]){
          var deleteisclick = typeof($("#delete'.$id.'").attr("onclick")); 
          if (deleteisclick=="undefined") {
            alert("请选择需要删除的数据");
          }
        }else if(e.target == $("#pub'.$id.'")[0]){
          var pubisclick = typeof($("#pub'.$id.'").attr("onclick")); 
          if (pubisclick=="undefined") {
            alert("请选择需要审核的数据");
          }
        }
      });
     </script>';

    $res .= '<img src="images/toolbar/printer.png" width="35px" height="35px" style="cursor:pointer;"  />';
    $res .= '<img src="images/toolbar/Synchronization.png" width="35px" height="35px" style="cursor:pointer;"  />';
    if ($tableName === 'Shop_Category') {
      $res .= '<img src="images/toolbar/SCG_pub.png" width="35px" height="35px" style="cursor:pointer;" id="scgpub-'.$id.'"  title="发布" onclick="pubdata(\''.$id.'\',\''.$tableName.'\');"/>';
    }else{
      $res .= '<img src="images/toolbar/SCG_pub.png" width="35px" height="35px" style="cursor:pointer;" id="scgpub-'.$id.'"  title="发布"/>';
    }
    
    return $res;
  }
 
}

function GetConditionStringByTableNameKeyword($tableName,$keyword) {
  $condition = '';
  if(!($keyword === '' || $keyword === null)) {
    if(strpos($keyword, ' = ') > 0) {
      $condition = $keyword;
    } else {
      $colList = getSearchCondtionColumnListByTableName($tableName);
      if(count($colList) > 0) {
        foreach ($colList as $col) {
          $condition .= "`".$col."` like '%".$keyword."%' or ";
        }
        $condition = substr($condition, 0, -4);
      }      
    }
  }
  return $condition;
}