function addBlock() {
	//alert(row);
	$('#addingdiv').html('<button id="'+row+'-1" style="width:50%;">'+row+'-1</button><button id="'+row+'-2" style="width:50%;position:absolute;left:50%;top:0px;">'+row+'-2</button>');
	$('#addingdiv button').bind("click",function(){
    	selectGrid($(this).attr('id'));
    });
	$('#addingdiv').attr('id','addeddiv');
	row++;
    $('.gbinlist').append('<li id="row'+row+'"><div id="addingdiv" style="position:relative;"><button id="addbutton" onclick="addBlock();">+</button></div></li>');
    ReCalHeight();
}
function selectGrid(id) {
	var para = id.split('-');
	var selrow = para[0];
	var selcol = para[1];
	id = '#'+id;
	pid = $(id).parent('li').attr('id');
	if($(id).hasClass('inarea')) {
		$('.select-grid').each(function(){
			if($(this).hasClass('inarea')) {
				if($(this).parent('li').attr('id') != pid) {
					$(this).removeClass('select-grid');
				}
			} else {
				$(this).removeClass('select-grid');
			}
		});
	} else {
		$('.inarea').removeClass('select-grid');
	}
	if($(id).hasClass('select-grid')) {
		//$(id).removeClass('select-grid');
		removeSelectGrid(id);
	} else {
		if($(id).hasClass('candidate-grid')) {
			$(id).removeClass('candidate-grid');
			$(id).addClass('select-grid');
		} else {
			//$('.select-grid').removeClass('select-grid');
			removeSelectGrid('.select-grid');
			$(id).addClass('select-grid');
		}
		setCandidate(selrow,selcol);
	}
}
function removeSelectGrid(selector) {
	$(selector).removeClass('select-grid');
	$(selector).addClass('candidate-grid');
	var count = $(selector).length;
	if(count == 1) {
		var id = $(selector).attr('id');
		var para = id.split('-');
    	var selrow = para[0];
    	var selcol = para[1];
    	selrow = parseInt(selrow);
    	selcol = parseInt(selcol);
    	if(!($('#'+selrow+'-1').hasClass('select-grid')||$('#'+selrow+'-2').hasClass('select-grid'))) {
    		for(var i = selrow + 1; i < row; i++) {
    			$('#'+i+'-1').removeClass('select-grid');
    			$('#'+i+'-2').removeClass('select-grid');
    		}
    	} else {
    		for(var i = selrow + 1; i < row; i++) {
    			$('#'+i+'-'+selcol).removeClass('select-grid');
    		}
    	}
	}
	$('.candidate-grid').each(function() {
		var id = $(this).attr('id');
		var para = id.split('-');
    	var selrow = para[0];
    	var selcol = para[1];
    	selrow = parseInt(selrow);
    	selcol = parseInt(selcol);
    	var ids = getNeighbours(selrow,selcol);
    	var removeCandidate = true;
    	for(var i in ids) {
    		var nid = ids[i];
    		if($(nid).hasClass('select-grid')) {
    			removeCandidate = false;
    			break;
    		}
    	}
    	if(removeCandidate) {
    		$(this).removeClass('candidate-grid');
    	}
    });
}
function setCandidate(selrow,selcol) {
	selrow = parseInt(selrow);
	selcol = parseInt(selcol);
	var ids = getNeighbours(selrow,selcol);
	for(var i in ids) {
		var id = ids[i];
		if(!$(id).hasClass('select-grid') && !$(id).hasClass('candidate-grid')) {
			$(id).addClass('candidate-grid');
		}
	}
}
function getNeighbours(selrow,selcol) {	    	
	var ids = ['#'+(selrow-1)+'-'+selcol,'#'+selrow+'-'+(selcol+1),'#'+(selrow+1)+'-'+selcol,'#'+selrow+'-'+(selcol-1)];
	return ids;
}
function getMinimalSquare() {
	var matrix = new Array();
	for(var i = 1;i < row;i++) {
		matrix[i-1] = [0,0];
	}
	$('.select-grid').each(function(){
		var id = $(this).attr('id');
		var para = id.split('-');
    	var selrow = para[0];
    	var selcol = para[1];
    	selrow = parseInt(selrow);
    	selcol = parseInt(selcol);
    	matrix[selrow-1][selcol-1] = 1;
	});
	var startRow = 0;
	var endRow = 0;
	var width = 0;
	var left = 0;
	for(var i in matrix) {
		i = parseInt(i);
		sel1 = matrix[i][0];
		sel2 = matrix[i][1];
		if(sel1 + sel2 == 0) {
			if(startRow == 0) {
				continue;
			} else {
				break;
			}
		} else if(sel1 + sel2 == 2) {
			if(startRow == 0) {
				startRow = i+1;
    			endRow = i+1;
    			width = 2;
    			left = 1;
			} else {
				endRow = i+1;
    			if(endRow-startRow==2) {
    				break;
    			}
			}
		} else {
			if(startRow == 0) {
				startRow = i+1;
				endRow = i+1;
				width = 1;
				left = sel1 == 1 ? 1 : 2;
			} else if(width == 1 && left == (sel1 == 1 ? 1 : 2)) {
				endRow = i+1;
    			if(endRow-startRow==2) {
    				break;
    			}
			} else {
				break;
			}
		}
	}
	var ret = [startRow,endRow,width,left];
	//alert(startRow+','+endRow+','+width+','+left);
	$('.gbinlist button').removeClass('select-grid');
	$('.gbinlist button').removeClass('candidate-grid');
	//width = left+width;
	for(var i = startRow;i <= endRow;i++) {
		for(var j = left;j < left+width;j++) {
			$('#'+i+'-'+j).addClass('select-grid');
		}
	}
	return ret;
}
var delRow = new Object();
function initEditForm(selector) {
	if($('.select-button').data('formpara')) {
		var para = $('.select-button').data('formpara');
		$(selector+' #productid').val(para.productid);
		$(selector+' #block').val(para.block);
		$(selector+' #shape').val(para.shape);
		if(para.type == 'label') {
			$(selector+' #content').val(para.content);
			$(selector+' #link').val(para.link);
		} else if(para.type == 'text') {
			$(selector+' #context').val(para.context);
		} else if(para.type == 'image') {
			$(selector+' #imgsrc').val(para.imgsrc);
			$(selector+' #imgalt').val(para.imgalt);
			$(selector+' #imgurl').val(para.imgurl);
			if($('#showimage img').length > 0) {
				$('#showimage img').attr('src',para.imgsrc);
			} else {
				$('#showimage').html('<img src="'+para.imgsrc+'" style="width:95%;" />')
			}
		} else if(para.type == 'slide') {
			var imglist = para.imglist;
			var imgurls = imglist.split('|');
			$(selector+' #imglist').val(imglist);
			$(selector+' #linklist').val(para.linklist);
			$(selector+' #altlist').val(para.altlist);
			$('#slideshowimage div').remove();
			$('#slidefinalshow img').remove();
			for (var i = 0; i < imgurls.length; i++) {
				if(i == 0) {
					$('#slidefinalshow').append('<img id="firstSlideImage" src="'+imgurls[i]+'" style="width:28%;margin:2px;" onclick="editSlideImageSetting(\''+imgurls[i]+'\')" />');
				} else {
					$('#slidefinalshow').append('<img src="'+imgurls[i]+'" style="width:28%;margin:2px;" onclick="editSlideImageSetting(\''+imgurls[i]+'\')" />');
				}
			}
		}
	} else {
		$(selector+" #shape").val($('.select-button').data('shape')?$('.select-button').data('shape'):'1,a');
		$(selector+" #content").val('');
		$(selector+" #link").val('');
		$(selector+" #context").val('');
		$(selector+" #imgsrc").val('');
		$(selector+" #imgalt").val('');
		$(selector+" #imgurl").val('');
		$(selector+" #imglist").val('');
		$(selector+" #linklist").val('');
		$(selector+" #altlist").val('');
		//$('#showimage').html($('#picarea').html());
		$("#imageEdit #file").val('');
		$("#showimage").html('');
		$('#slideshowimage').html('');
		$('#slidefinalshow').html('');
	}
}
function editAreaButton() {
	if($('.select-button').parent('li').hasClass('LabelArea')) {
		$( "#labelEdit" ).dialog( "open" );
		$('#labelEdit #block').val($('.select-area').attr('id'));
		initEditForm('#labelEdit');
	} else if($('.select-button').parent('li').hasClass('TextArea')) {
		$( "#textEdit" ).dialog( "open" );
		$('#textEdit #block').val($('.select-area').attr('id'));
		initEditForm('#textEdit');
	} else if($('.select-button').parent('li').hasClass('ImageArea')) {
		$( "#imageEdit" ).dialog( "open" );
		$('#imageEdit #block').val($('.select-area').attr('id'));
		initEditForm('#imageEdit');
	} else if($('.select-button').parent('li').hasClass('SlideArea')) {
		$( "#slideEdit" ).dialog( "open" );
		$('#slideEdit #block').val($('.select-area').attr('id'));
		initEditForm('#slideEdit');
	} 
	
}
function ReCalHeight() {
	var height = Math.max(parseInt($('.gbinlist').css('height')),parseInt($('.gbinlist2').css('height'))) + 20;
	$('#detailLayout').css('height',height+'px');
	height += 150;
	//alert(height);
	$('#detailarea').css('height',height+'px');
}
function deleteArea() {
	$('.select-area').remove();
    ReCalHeight();
}
function setLabelArea() {
	var ret = getMinimalSquare();
	var startRow = ret[0];
	var shouldMove = true;
	$('.select-grid').removeClass('select-grid');
	if($('#row'+startRow).length == 0) {
		return false;
	} else {
		var height = 50;
		$('#row'+startRow).html('<button id="area'+areaSN+'" onclick="areaClick(this);" style="width:100%;height:'+height+'px;">'+areaSN+'</button>');
		$('#row'+startRow).addClass('area');
		areaSN++;
	}
	if(shouldMove) {
		var area = $('#row'+startRow).clone();
    	$('#row'+startRow).remove();
    	$('.gbinlist2').append(area);
    	ReCalHeight();
	}
	$('.gbinlist2').sortable().bind('sortupdate', function(){
    	//$('.gbinlist li').attr('draggable','false');
    	//$('.gbinlist .area').attr('draggable','true');
    });
	$('#area'+(areaSN-1)).append(',L');
    $('#row'+startRow).addClass('LabelArea');
	return "area"+areaSN;
}
function setSlideArea() {
	var ret = getMinimalSquare();
	var startRow = ret[0];
	var shouldMove = true;
	$('.select-grid').removeClass('select-grid');
	if($('#row'+startRow).length == 0) {
		return false;
	} else {
		var height = 50;
		$('#row'+startRow).html('<button id="area'+areaSN+'" onclick="areaClick(this);" style="width:100%;height:'+height+'px;">'+areaSN+'</button>');
		$('#row'+startRow).addClass('area');
		areaSN++;
	}
	if(shouldMove) {
		var area = $('#row'+startRow).clone();
    	$('#row'+startRow).remove();
    	$('.gbinlist2').append(area);
    	ReCalHeight();
	}
    $('.gbinlist2').sortable().bind('sortupdate', function(){
    	//$('.gbinlist li').attr('draggable','false');
    	//$('.gbinlist .area').attr('draggable','true');
    });
	$('#area'+(areaSN-1)).append(',S');
    $('#row'+startRow).addClass('SlideArea');
	return "area"+areaSN;
}
function setTextArea() {
	var ret = getMinimalSquare();
	var startRow = ret[0];
	var shouldMove = true;
	$('.select-grid').removeClass('select-grid');
	if($('#row'+startRow).length == 0) {
		return false;
	} else {
		var height = 100;
    	$('#row'+startRow).html('<button id="area'+areaSN+'" onclick="areaClick(this);" style="width:100%;height:'+height+'px;">'+areaSN+'</button>');
		$('#row'+startRow).addClass('area');
		areaSN++;
	}
	if(shouldMove) {
		var area = $('#row'+startRow).clone();
    	$('#row'+startRow).remove();
    	$('.gbinlist2').append(area);
    	ReCalHeight();
	}
	$('.gbinlist2').sortable().bind('sortupdate', function(){
    	//$('.gbinlist li').attr('draggable','false');
    	//$('.gbinlist .area').attr('draggable','true');
    });
	$('#area'+(areaSN-1)).append(',T');
    $('#row'+startRow).addClass('TextArea');
	return "area"+areaSN;
}
function setImageArea() {
	var id = setArea();
    $('.gbinlist2').sortable().bind('sortupdate', function(){
    	//$('.gbinlist li').attr('draggable','false');
    	//$('.gbinlist .area').attr('draggable','true');
    });
    ReCalHeight();
}
function setArea() {
	var ret = getMinimalSquare();
	var startRow = ret[0];
	var endRow = ret[1];
	var width = ret[2];
	var left = ret[3];
	if(startRow == 0) return false;
	//alert(startRow+','+endRow+','+width+','+left);
	var height = 50*(endRow-startRow+1);
	var shouldMove = false;
	if($('#row'+startRow).length == 0) {
		rowIndex=delRow[startRow];
	} else {
		rowIndex=startRow;
	}
	if(width == 2) {
		$('#row'+rowIndex).html('<button id="area'+areaSN+'" onclick="areaClick(this);" style="width:100%;height:'+height+'px;">'+areaSN+'</button>');
		$('#row'+rowIndex).addClass('area');
		areaSN++;
		shouldMove = true;
	} else if(left == 1) {
		if($('#row'+rowIndex).hasClass('area')) {
			var top = $('#'+startRow+'-'+left).css('top');
			var locleft = $('#'+startRow+'-'+left).css('left');
			$('#row'+rowIndex).append('<button id="area'+areaSN+'" onclick="areaClick(this);" style="position:absolute;width:50%;height:'+height+'px;top:'+top+';left:'+locleft+'">'+areaSN+'</button>');
			for(var i=startRow;i<=endRow;i++) {
    			$('#'+i+'-1').remove();
    		}
    		areaSN++;
		} else {
			$('#row'+rowIndex).html('<button id="area'+areaSN+'" onclick="areaClick(this);" style="width:50%;height:'+height+'px;">'+areaSN+'</button>');
    		areaSN++;
    		$('#row'+rowIndex).addClass('area');
			for(var i=startRow;i<=endRow;i++) {
    			var top = (i-startRow)*50;
    			$('#row'+rowIndex).append('<button id="'+i+'-2" class="inarea" onclick="selClick(this);" style="width:50%;position:absolute;left:50%;top:'+top+'px;">'+i+'-2</button>');
    		}
    		shouldMove = true;
		}	    		
	} else if(left == 2) {
		if($('#row'+rowIndex).hasClass('area')) {
			var top = $('#'+startRow+'-'+left).css('top');
			var locleft = $('#'+startRow+'-'+left).css('left');
			$('#row'+rowIndex).append('<button id="area'+areaSN+'" onclick="areaClick(this);" style="position:absolute;width:50%;height:'+height+'px;top:'+top+';left:'+locleft+'">'+areaSN+'</button>');
			for(var i=startRow;i<=endRow;i++) {
    			$('#'+i+'-2').remove();
    		}
    		areaSN++;
		} else {
			$('#row'+rowIndex).html('<button id="area'+areaSN+'" onclick="areaClick(this);" style="position:relative;width:50%;height:'+height+'px;left:50%;top:0px">'+areaSN+'</button>');
    		areaSN++;
    		$('#row'+rowIndex).addClass('area');
			for(var i=startRow;i<=endRow;i++) {
    			var top = (i-startRow)*50;
    			$('#row'+rowIndex).append('<button id="'+i+'-1" class="inarea" onclick="selClick(this);" style="width:50%;position:absolute;left:0px;top:'+top+'px;">'+i+'-1</button>');
    		}
    		shouldMove = true;
		}
	}
	for(var i=startRow+1;i<=endRow;i++) {
		$('#row'+i).remove();
    	delRow[i]=startRow;
	}
	if(shouldMove) {
		var area = $('#row'+rowIndex).clone();
    	$('#row'+rowIndex).remove();
    	$('.gbinlist2').append(area);
		$('#row'+rowIndex).data('rows',endRow-startRow+1);
		$('#row'+rowIndex).data('srow',startRow);
		$('#row'+rowIndex).data('erow',endRow);
	}
	var prows = $('#row'+rowIndex).data('rows');
	var psrow = $('#row'+rowIndex).data('srow');
	var perow = $('#row'+rowIndex).data('erow');
	var rows = endRow-startRow+1;
	//alert(prows+','+psrow+','+perow+','+rows+','+width+','+left+','+startRow+','+endRow);
	if(width == 2) {
		$('#area'+(areaSN-1)).data('shape',rows+',a');
	} else if(rows == prows) {
		if(left == 1) {
			$('#area'+(areaSN-1)).data('shape',rows+',b');
		} else {
			$('#area'+(areaSN-1)).data('shape',rows+',c');
		}
	} else if(rows < prows) {
		if(left == 1 && startRow == psrow) {
			$('#area'+(areaSN-1)).data('shape',rows+',f,'+prows);
		} else if(left == 1 && endRow == perow) {
			$('#area'+(areaSN-1)).data('shape',rows+',g');
		} else if(left == 1) {
			$('#area'+(areaSN-1)).data('shape',rows+',h');
		} else if(left == 2 && startRow == psrow) {
			$('#area'+(areaSN-1)).data('shape',rows+',d');
		} else if(left == 2 && endRow == perow) {
			$('#area'+(areaSN-1)).data('shape',rows+',e');
		} else if(left == 2) {
			$('#area'+(areaSN-1)).data('shape',rows+',h');
		}
	}
	//$('#area'+(areaSN-1)).html($('#area'+(areaSN-1)).data('shape'));
	$('#area'+(areaSN-1)).append(',P');
    $('#row'+rowIndex).addClass('ImageArea');
	return "area"+areaSN;
}
function areaClick(button) {
	if($('#'+button.id).parent('.area').hasClass('select-area') && $('#'+button.id).hasClass('select-button')) {
		$('#'+button.id).parent('.area').removeClass('select-area');
		$('#'+button.id).removeClass('select-button');
	} else {
		$('.select-area').removeClass('select-area');
		$('#'+button.id).parent('.area').addClass('select-area');
		$('.select-button').removeClass('select-button');
		$('#'+button.id).addClass('select-button');
	}
}
function selClick(button) {
	//alert(button.id);
	selectGrid(button.id);
}
function submitDetail() {
	var rows = getDetailBlockPostPara();
	var para = new Object();
	para.productid = productid;
	para.rows = rows;
	$.post("soap/saveproductdetail.php",para,function(data){
		alert(data);
		$('.select-area').removeClass('select-area');
		$('.select-button').removeClass('select-button');
		$('.select-grid').removeClass('select-grid');

	});
}
function submitHomepageDetail() {
	var rows = getDetailBlockPostPara();
	var para = new Object();
	para.rows = rows;
	$.post("soap/savehomepagedetail.php",para,function(data){
		alert(data);
		$('.select-area').removeClass('select-area');
		$('.select-button').removeClass('select-button');
		$('.select-grid').removeClass('select-grid');

	});
}
function getDetailBlockPostPara() {
	var i = 0;
	var rows = new Array();
	$('.gbinlist2 li').each(function(){
		var id = $(this).attr('id');
		if($(this).hasClass('LabelArea') || $(this).hasClass('TextArea') || $(this).hasClass('SlideArea')) {
			rows[i] = $('#'+id+' button').data('formpara');
			i++;
		} else if($(this).hasClass('ImageArea')) {
			//alert('image');
			var imgs = new Array();
			var j = 0;
			$('#'+id+' button').each(function(){
				imgs[j] = $(this).data('formpara');
				j++;
			});
			//alert(JSON.stringify(imgs));
			imgs = arrangeImageAreaOrder(imgs);
			for(var k in imgs) {
				rows[i] = imgs[k];
				i++;
			}
		}
	});
	//alert(JSON.stringify(rows));
	return rows;
}
function arrangeImageAreaOrder(imgs) {
	var ret = new Object();
	var res = new Array();
	for(var i in imgs) {
		//alert(imgs[i].shape);
		if(imgs[i].shape.indexOf('a')>0) {
			return imgs;
		} else if(imgs[i].shape.indexOf('b')>0 || imgs[i].shape.indexOf('f')>0) {
			ret.head = i;
		} else if(imgs[i].shape.indexOf('c')>0 || imgs[i].shape.indexOf('e')>0) {
			ret.tail = i;
		} else if(imgs[i].shape.indexOf('d')>0) {
			ret.a = i;
		} else if(imgs[i].shape.indexOf('h')>0) {
			ret.b = i;
		} else if(imgs[i].shape.indexOf('g')>0) {
			ret.c = i;
		}
	}
	//alert(JSON.stringify(ret));
	var j = 0;
	res[j] = imgs[ret.head];
	j++;
	res[j] = ret.a ? imgs[ret.a] : false;
	if(res[j]) j++;
	res[j] = ret.b ? imgs[ret.b] : false;
	if(res[j]) j++;
	res[j] = ret.c ? imgs[ret.c] : false;
	if(res[j]) j++;
	res[j] = imgs[ret.tail];
	return res;
}
function viewProduct() {
	$('#preview').html('<iframe src="http://acms.colored-stone.com.cn/product.showdetail.php?id='+productid+'" width="300" height="500"></iframe>');
	$('#preview').dialog( "open" );
}
function publishProduct() {
	// var url = 'http://cmenu.sinaapp.com/admin/soap/publishproduct.php?domain='+domain+'&productid='+productid;
	// $.get(url,function(result){
	// 	alert(result);
	// });
}