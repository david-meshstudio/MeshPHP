$(function() {
	var c = 0;
	$(".menu").menu();  
	$(".tabs").tabs();
	$("#accordion").accordion();
	$("#dialog").dialog();
	$("table").footable();

  $( "#dialogDel" ).dialog({
    autoOpen: false,
    width: 400,
    buttons: [
      {
        text: "Ok",
        click: function() {
          var paraArr = para.split(",");
          var urlauth = "soap/deletefromdb.php?code=david&id="+paraArr[0]+"&tableName="+paraArr[1];
          geturlcontent(urlauth, function(restext) {
            if (restext != '') { 
              alert(restext);
            }
          });
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Cancel",
        click: function() {
          $( this ).dialog( "close" );
        }
      }
    ]
  });
  $("a#deltab").bind("dblclick", function() {
      $(".ntabs").remove();
      $(".addtabs").remove();
      //changeSidebarDisplay();
  });
	function runEffect() {
		var options = {};
		$("#menudiv:hidden").show("blind",options,500);
	}
	function fadeEffect() {
		var options = {};
		$("#menudiv:visible").hide("blind",options,500);
	}
	$( "button" )
    .button()
    .click(function( event ) {
      event.preventDefault();
  });
	$("#button-menu").click(function() {
		if(c==0) {
			runEffect();
			c=1;
		} else {
			fadeEffect();
			c=0;
		}
		return false;
	});
	$("#menudiv").mouseleave(function() {
		if(c==1) {
			fadeEffect();
			c=0;
		}
	});
	$("#menudiv").hide();
	$("a.menuitem").click(function(){
		//window.alert($(this).attr("value"));
    //changeSidebarDisplay();
		total_tabs++;
		var tname = $(this).html();
		var tid = $(this).attr("value");
    $(this).attr('id',tid);
		addtab(total_tabs,tname,tid);
	});
	$(".tabs").css("width",$(document).width()-180);
	$("#welcomeimg").css("width",$(document).width()*0.3);
	$("#footer-layer").css("width",$(document).width()-20);
	$("#weltext").css("width",$(document).width()-200);
	//$(".accordion-menu").css("height",$(document).height()-300);
	//$(".tabs").css("height",$("#accordion").height()-8);
	//var customer = $("#customer"),
	 account = $( "#account" ),
   password = $( "#password" ),
   vcode = $("#vcode"),
   //allFields = $( [] ).add(customer).add( account ).add( password );
   allFields = $( [] ).add( account ).add( password );
  
  function checkLength( o, n, min, max ) {
    if ( o.val().length > max || o.val().length < min ) {
      o.addClass( "ui-state-error" );
      updateTips( "Length of " + n + " must be between " +
        min + " and " + max + "." );
      return false;
    } else {
      return true;
    }
  }

  function checkRegexp( o, regexp, n ) {
    if ( !( regexp.test( o.val() ) ) ) {
      o.addClass( "ui-state-error" );
      updateTips( n );
      return false;
    } else {
      return true;
    }
  }
  
  var urlauth = "soap/userauthorization.php?method=session";
  geturlcontent(urlauth, function(restext) {
  	if (restext != '') { 
  		$( "#accountshow" ).text(restext); 
  		$( "#dialog-form" ).dialog( "close" ); 
  	}
  });
  
  $( "#dialog-form" ).dialog({
    autoOpen: true,
    height: 300,
    width: 350,
    modal: true,
    buttons: {
      "Login": function() {
        var bValid = true;
        allFields.removeClass( "ui-state-error" );
        bValid = bValid && checkLength( account, "account", 3, 80 );
        bValid = bValid && checkLength( password, "password", 3, 16 );
        if ( bValid ) {
          urlcheck = "soap/userauthorization.php?method=check&account="+account.val()+"&password="+password.val()+"&vcode="+vcode.val();
        	geturlcontent(urlcheck, function(restext) { 
        		if(restext == '0') {
        			if($("#accountshow").text()=="") {
        			  $( "#accountshow" ).text($( "#account" ).val());
        		  }
        			urllogin = "soap/userauthorization.php?method=login&account=" + $( "#accountshow" ).text();
        			geturlcontent(urllogin, function(restext){});
              $( "#dialog-form" ).dialog( "close" );
              $("#vcode").val('');
              $("#vcode_display").attr('src','images/vcode.jpg');
        		} else if(restext == '1') {
        			alert("No such account");
        		} else if(restext == '2') {
        			alert("Wrong password");
        		}
        	});
        }
      },

      "Cancel": function() {
      	account.val('');
      	password.val('');
        $("#vcode").val('');
        $("vcode_display").attr('src','images/vcode.jpg');
      }
    },
    close: function() {
      allFields.val( "" ).removeClass( "ui-state-error" );
    }
  });
  $('#password,#account,#vcode').on('keydown', function(e) { 
    if (e.keyCode == 13) {
       var bValid = true;
        allFields.removeClass( "ui-state-error" );
        bValid = bValid && checkLength( account, "account", 3, 80 );
        bValid = bValid && checkLength( password, "password", 3, 16 );
        if ( bValid ) {
          urlcheck = "soap/userauthorization.php?method=check&account="+account.val()+"&password="+password.val()+"&vcode="+vcode.val();
          geturlcontent(urlcheck, function(restext) { 
            if(restext == '0') {
              if($("#accountshow").text()=="") {
                $( "#accountshow" ).text($( "#account" ).val());
              }
              urllogin = "soap/userauthorization.php?method=login&account=" + $( "#accountshow" ).text();
              geturlcontent(urllogin, function(restext){});
              $( "#dialog-form" ).dialog( "close" );
              $("#vcode").val('');
              $("#vcode_display").attr('src','images/vcode.jpg');
            } else if(restext == '1') {
              alert("No such account");
            } else if(restext == '2') {
              alert("Wrong password");
            }
          });
        }
    } 
  });
  $( "#login" ).click(function() {
      var urllogout = "soap/userauthorization.php?method=logout";
      geturlcontent(urllogout, function(restext) {
      	$( "#accountshow" ).text("");
        $(".ntabs").remove();
        $(".addtabs").remove();
        $("#deltab").click();
      });
      $( "#dialog-form" ).dialog( "open" );
  });
    
    
	$(".ui-dialog-titlebar-close").remove();
	
  //changeSidebarDisplay();
});
//全局函数，内容生成

  var addtab = function(count,tname,tid) {
  	//点击菜单后创建Tab
  	if($(".ntabs#"+tid).length<=0) {
      $("#tabul").append('<li value="tabs-'+count+'" class="ntabs" id="'+tid+'"><a href="#tabs-'+count+'">'+tname+'</a><img class="cbimg" id="'+count+'" src="./images/closeb.png" /></li>');
      $(".tabs").append('<div id="tabs-'+count+'" class="addtabs" style="padding: 2px;">'+gentabcontent(tid, count)+'</div>');;
      $(".ntabs").bind("dblclick", function() {
          var id=$(this).attr("value");
          $(this).prev().find("a").click();
          $(this).remove();
          $("div#"+id).remove();
      });
      $(".cbimg").click(function() {
      	var count = $(this).attr("id");
      	//$('.ntabs #'+tid).remove();
        $(this).parent().prev().find("a").click();
      	$(this).parent().remove();
      	$('div #tabs-'+count).remove();
      });
      $(".tabs").tabs("refresh");
      $(".ntabs#"+tid+" a").click();
    } else {
    	$(".ntabs#"+tid).find("a").click();
    }
  };
  var createXMLHttpRequest = function () {  
    var xmlHttp;  
    if (window.XMLHttpRequest) {  
        xmlHttp = new XMLHttpRequest();  
        if (xmlHttp.overrideMimeType)  
            xmlHttp.overrideMimeType("text/xml");  
    } else if (window.ActiveXObject) {  
        try {  
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");  
        } catch (e) {  
            try {  
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");  
            } catch (e) {  
            }  
        }  
    }  
    return xmlHttp;  
  };
  var geturlcontent = function (url, callback) {
  	var xmlHttp = createXMLHttpRequest();
    xmlHttp.open("GET", url, true);// 异步处理返回   
    xmlHttp.onreadystatechange =  function() { 
      if (xmlHttp.readyState == 4 && xmlHttp.status  == 200) {
      	callback(xmlHttp.responseText);
      }
    };
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");  
    xmlHttp.send(); 
  };
  var getcontent = function (tableName, count, mobi) {
    var url = "./includes/tabcontent.php?tableName="+tableName+"&currPage=1&method=init&count="+count+"&mobi="+mobi;
  	geturlcontent(url, function(restext) { 
      $("#loading").remove();
      $("#tabs-"+count).append(restext);
  	  $("#tabs-"+count).append('<div id="tabs-'+count+'-bottom"></div>');
	  });
  };
  var getcontentbykeyword = function (tableName, count, mobi, keyword) {
    var url = "./includes/tabcontent.php?tableName="+tableName+"&currPage=1&method=init&count="+count+"&mobi="+mobi+"&keyword="+keyword;
    geturlcontent(url, function(restext) {
      $("#tabs-"+count).append(restext);
      $("#tabs-"+count).append('<div id="tabs-'+count+'-bottom"></div>');
    });
  };
  var getcontentframe = function (tableName, count, mobi) {
    var url = "./includes/tabcontent.php?tableName="+tableName+"&currPage=1&method=frameinit&count="+count+"&mobi="+mobi;
  	geturlcontent(url, function(restext) { 
      $("#loading").remove();
      $("#tabs-"+count).append(restext);
    });
  };
  var getroweditpage = function (row, count, mobi) {
    var url = "./includes/tabcontent.php?method=rowedit&row="+row+"&mobi="+mobi;
  	geturlcontent(url, function(restext) {
      $("#loading").remove();
      $("#tabs-"+count).append(restext);
    });
  };
  var getrowaddpage = function (row, count, mobi) {
    var url = "./includes/tabcontent.php?method=rowadd&row="+row+"&mobi="+mobi;
  	geturlcontent(url, function(restext) {
      $("#loading").remove();
      $("#tabs-"+count).append(restext);
    });
  };
  var gentabcontent = function (tid, count) {
  	var result = '<div id="loading"><img src="images/loading.gif"></div>';
    var menutype = $('#'+tid).data('menutype');
    var tablename = $('#'+tid).data('tablename');
	  tid = tid.split('-')[0];
    var onMobile = $(document).width() < 500 ? 'y' : 'n';
    if(menutype === 'table') {
      getcontent(tablename,count,onMobile);
    } else {
      switch(tid) {
        case "add":
          getrowaddpage(para,count,onMobile);
          break;
        case "edit":
          getroweditpage(para,count,onMobile);
          break;
        default:
          result = tid;
      }
    }
  	return result;
  };
  var closeInfoList = function() {
    $('#background').css("display","none");
    $('#infolist').css("display","none");
  }
  var submitedit = function(obj) {
    $(obj).ajaxSubmit({
      url: "./soap/savetodb.php",
      type: "POST",
      datatype: "text",
      success: function(data){
        alert(data);
      }
    });
    return false;
  };
  
  var submitadd = function(obj) {
    $(obj).ajaxSubmit({
      url: "./soap/inserttodb.php",
      type: "POST",
      datatype: "text",
      success: function(data){
        alert(data);
      }
    });
    return false;
  };
 
  var getSearchResult = function(obj) {
    var tablename = $(obj).data('tablename');
    var count = $(obj).data('count');
    var keyword = $('#searchinput-'+count).val();
    keyword = $.base64().encode(keyword);
    //alert(tablename+','+count+','+keyword);
    var mobi = $(document).width() < 500 ? 'y' : 'n';
    $('#tabs-'+count).html('');
    $('#tabs-'+count).html(getcontentbykeyword(tablename, count, mobi, keyword));
  }

  var showVCode = function() {
    var url = './soap/vcode.php';
    geturlcontent(url, function(restext) { 
        $('#vcode_display').attr("src",restext); 
      });
  }