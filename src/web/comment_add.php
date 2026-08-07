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
$show_title="댓글 추가 - AlgoWiki";
$sqls = "SELECT comment_id FROM comment order by comment_id desc limit 1";
$result = pdo_query($sqls);
$comment_id= $result[0][0]+1;
$post_id=intval($_GET["id"]);
$parent_id = $_POST['parent_id'];
$writer = $_SESSION[$OJ_NAME.'_'.'user_id'];
$content = $_POST['content'];
$content = trim($content);
if(false){
  $content = stripslashes($content);
}
$content = str_replace("<p>", "", $content);
$content = str_replace("</p>", "<br />", $content);
$content = str_replace(",", "&#44;", $content);
$sql = "INSERT INTO comment VALUES(?,?,?,?,?,now())";
$result = pdo_query($sql,$comment_id,$post_id,$parent_id,$writer,$content);
if($result==NULL){/* INSERT 실패 시 중단 */
echo "<script>alert('댓글 입력에 실패하였습니다.');</script>";
}
echo "<script>window.location.href='post_view.php?id=" . $post_id . "';</script>";
include("template/$OJ_TEMPLATE/footer.php");
?>

