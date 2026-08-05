<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once('./include/my_func.inc.php');
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
include("template/$OJ_TEMPLATE/header.php");
$post_id=intval($_GET["id"]);
$sqls = "Delete FROM post where post_id = ".$post_id.""; 
$result = pdo_query($sqls);
$sqls = "Delete FROM comment where post_id = ".$post_id.""; 
$result = pdo_query($sqls);
echo "<script>window.location.href=\"board.php\";</script>";
include("template/$OJ_TEMPLATE/footer.php");
?>
