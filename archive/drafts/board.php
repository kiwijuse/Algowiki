<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
$view_title = "Welcome To Online Judge";
$result = false;
?>
<?php include("template/$OJ_TEMPLATE/header.php");

if(isset($_GET['data'])){
	require("./board/".$_GET['data'].".php");
}else{
	require("./board/board.php");
}

include("template/$OJ_TEMPLATE/footer.php");

?>
