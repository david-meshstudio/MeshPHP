function addPicBlock() {
	//alert(row);
	$('#addingdiv2').html('<button id="'+prow+'-1" style="width:100%;">'+prow+'</button>');
	$('#addingdiv2 button').bind("click",function(){
    	selectPicButton($(this).attr('id'));
    });
	$('#addingdiv2').attr('id','addeddiv2');
	prow++;
    $('.gbinlist3').append('<li id="prow'+prow+'"><div id="addingdiv2" style="position:relative;"><button id="addbutton" onclick="addPicBlock();">+</button></div></li>');
    $('.gbinlist3').sortable().bind('sortupdate', function(){});
}
function selectPicButton(id) {
	if($('#'+id).hasClass('select-button')) {
		$('#'+id).removeClass('select-button');
	} else {
		$('.select-button').removeClass('select-button');
		$('#'+id).addClass('select-button');
	}
}
function editPicAreaButton() {
	initPicEditForm('#slideEdit');
	$( "#slideEdit" ).dialog( "open" );
}
function initPicEditForm(selector) {
	if($('.select-button').data('formpara')) {
		var para = $('.select-button').data('formpara');
		$(selector+' #imgsrc').val(para.imgsrc);
		if($('#showslide img').length > 0) {
			$('#showslide img').attr('src','http://cmenui.b0.upaiyun.com/'+domain+'/'+para.imgsrc);
		} else {
			$('#showslide').append('<img src="http://cmenui.b0.upaiyun.com/'+domain+'/'+para.imgsrc+'" style="max-width:95%;max-height:95%;margin:auto;" />')
		}
	} else {
		//alert('hello');
		$(selector+" #imgsrc").val('');
		$("#slideEdit #file").val('');
		$("#showslide").html('');
	}
}
function deletePicArea() {
	$('.select-button').remove();
}
function submitSlide() {
	var rows = getSlidePostPara();
	var para = new Object();
	para.domain = domain;
	para.productid = productid;
	para.rows = rows;
	$.post("soap/saveproductslide.php",para,function(data){
		alert(data);
		$('.select-button').removeClass('select-button');
	});
}
function submitHomepageSlide() {
	var rows = getSlidePostPara();
	var para = new Object();
	para.domain = domain;
	para.rows = rows;
	$.post("soap/savehomepageslide.php",para,function(data){
		alert(data);
		$('.select-button').removeClass('select-button');
	});
}
function getSlidePostPara() {
	var i = 0;
	var rows = new Array();
	$('.gbinlist3 li').each(function(){
		var id = $(this).attr('id');
		rows[i] = $('#'+id+' button').data('formpara');
		i++;
	});
	return rows;
}
/*
function viewProduct() {
	$('#preview').html('<iframe src="http://cmenu.sinaapp.com/admin/productpreview.php?domain='+domain+'&productid='+productid+'" width="300" height="500"></iframe>');
	$('#preview').dialog( "open" );
}
*/