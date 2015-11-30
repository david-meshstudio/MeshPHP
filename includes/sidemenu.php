<?php
$menuList = getMenuList();
echo '<div id="accordion">';
for($i = 0;$i < count($menuList);$i++) {
	$menu = $menuList[$i];
	echo '<h3>'.$menu->Name.'</h3><div class="accordion-menu"><ul class="menu">';
	for($j = 0;$j < count($menu->SubMenuList);$j++) {
		$submenu = $menu->SubMenuList[$j];
		echo '<li><a class="menuitem" value="'.$submenu->ID.'" href="#" data-tablename="'.$submenu->TableName.'" data-menutype="'.$submenu->Type.'">'.$submenu->Name.'</a></li>';
	}
	echo '</ul></div>';
}
echo '</div>';
?>