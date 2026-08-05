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
$post_id=intval($_GET["pid"]);
$comment_id=intval($_GET["cid"]);
$sql = "UPDATE comment set 
	content = '<del>삭제된 댓글입니다</del>',
	writer ='<del>Unknown</del>',
	create_time = now()
	where comment_id = ".$comment_id.""; 
$result = pdo_query($sql);
echo "<script>window.location.href='post_view.php?id=".$post_id."';</script>";
include("template/$OJ_TEMPLATE/footer.php");
?>
