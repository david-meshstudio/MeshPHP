<html>
  <?php include('includes/header.php'); ?>
  <body>
  	<div id='header' style='position:absolute;z-index:3'>
    	<ul class='menu'>
    		<li><a href='#' id='button-menu'><img src="images/menu.png" alt="menu" /></a></li>
    	</ul>
      <div id='menudiv' style='position:absolute;z-index:3'>
        <ul class='menu'>
          <li><a href='#' id="login"><img src="images/logout.png" /></a></li>
        </ul>
      </div>
		<div id='weltext' style='position:absolute;left:130px;top:0px;text-align:right;padding-top:10px;
			font-family: Lucida Grande,Lucida Sans,Arial,sans-serif;font-size: 1.1em;'>
			你好, <span id="accountshow"></span>
		</div>
      
    </div>
    <div id="background" style="position:absolute;display:none;width:100%;left:0px;height:100%;top:0px;z-index:8;background:black;opacity:0.6;"></div>
    <div id="infolist" style="border-width:1px;border-color:black;border-style:solid;width:80%;position:absolute;z-index:9;left:10%;display:none;background:white;overflow:auto;height:80%;top:10%;">
      <div style="position:relative;">
        <div id="content" style="padding:1px;"></div>
        <div style="position:absolute;right:0px;top:0px;"><img src="images/close.png" onclick="closeInfoList();" /></div>
      </div>
    </div>
    <div id='frame' style='position:absolute;z-index:2;top:60px'>
    	<?php include('includes/sidemenu.php'); ?>
    	<div id='tabdiv'>
        <div id='maintab' class="tabs">
          <ul id="tabul">
            <li><a href="#tabwelcome" id="deltab">&times;</a></li>
          </ul>
  
          <div id="tabwelcome" style="padding: 10px;font-family: Lucida Grande,Lucida Sans,Arial,sans-serif;font-size: 1.2em;">
          	<image style='max-width:100%;' src="http://7xjm3j.com1.z0.glb.clouddn.com/welcome.jpg" alt="main" />
          </div>
        </div>
    	</div>
	</div>
    <div id="dialog-form" title="登陆">
      <form>
        <fieldset>
          <label for="account">账号</label>
          <input type="text" name="account" id="account" value="" class="text ui-widget-content ui-corner-all" />
          <label for="password">密码</label>
          <input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />
          <label for="password">验证码</label>
          <input type="text" name="vcode" id="vcode" value="" class="text ui-widget-content ui-corner-all" />
          <img src="images/vcode.jpg" id="vcode_display" onclick="showVCode()" style="height:30px;" />
        </fieldset>
      </form>
    </div>
	<div style="display:none;">
		<label id="para"></label>
	</div>

<!-- ui-dialog -->
<div id="dialogDel" title="Double Confirm">
  <p>请确定是否要删除当前记录</p>
  <p><label id="confirmInfo"></label></p>
</div>

  </body>
</html>
