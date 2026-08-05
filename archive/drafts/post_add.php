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
$sqls = "SELECT post_id FROM post order by post_id desc limit 1";
$result = pdo_query($sqls);
$post_id= 0;
foreach ($result as $row) {
    	$post_id = $row["post_id"]+1;
}
$title = $_POST['title'];
$writer = $_SESSION[$OJ_NAME.'_'.'user_id'];
$content = $_POST['content'];
$category = $_POST['category'];
$problem_id = $_POST['problem_id'];
if(empty($problem_id)){
	$problem_id =NULL;
}
if(false){
  $title = stripslashes($title);
  $content = stripslashes($content);
}
$content = str_replace("<p>", "", $content);
$content = str_replace("</p>", "<br />", $content);
$content = str_replace(",", "&#44;", $content);
$sql = "INSERT INTO post VALUES(?,?,?,?,?,?,now())";
pdo_query($sql,$post_id,$title,$writer,$content,$category,$problem_id);
echo "<script>window.location.href=\"board.php\";</script>";
include("template/$OJ_TEMPLATE/footer.php");

?>
