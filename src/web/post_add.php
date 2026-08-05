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
$post_id= $result[0][0] + 1;
$title = $_POST['title'];
$title = trim($title);
$writer = $_SESSION[$OJ_NAME.'_'.'user_id'];
$content = $_POST['content'];
$content = trim($content);
$category = $_POST['category'];
$problem_id = $_POST['problem_id'];
if(empty($problem_id)){
	$problem_id =NULL;
}
else{
	$sql2 = "select count(*) from problem where problem_id = '$problem_id' and defunct = 'N'";
	$result2 = pdo_query($sql2);
	if($result2[0][0] == 0){
	echo "<script>alert('게시글 작성에 실패했습니다.\\n게시글 제목 - 4글자 이상\\n문제번호 - 현재 존재하는 문제번호만 가능\\n내용 - 4글자 이상');</script>";
	echo "<script>window.location.href='board.php';</script>";
	exit(0);
	}
}
$sql = "select created_time from post where writer = '$writer' order by created_time desc";
$last_time = pdo_query($sql);
$sql = "select now()";
$current_time = pdo_query($sql);
$time_last = new DateTime($last_time[0][0]);
$time_current = new DateTime($current_time[0][0]);
$time_difference = $time_current->getTimestamp() - $time_last->getTimestamp();
if($time_difference < 20){
	echo "<script>alert('너무 빠릅니다.\\n잠시후 다시 시도해주세요.');</script>";
	echo "<script>window.location.href='board.php';</script>";
	exit(0);
}
if(false){
  $title = stripslashes($title);
  $content = stripslashes($content);
}
$content = str_replace("<p>", "", $content);
$content = str_replace("</p>", "<br />", $content);
$content = str_replace(",", "&#44;", $content);

$sql = "INSERT INTO post VALUES(?,?,?,?,?,?,now())";
$result = pdo_query($sql,$post_id,$title,$writer,$content,$category,$problem_id);
if($result==NULL){
echo "<script>alert('게시글 작성에 실패했습니다.\\n게시글 제목 - 4글자 이상\\n문제번호 - 현재 존재하는 문제번호만 가능\\n내용 - 4글자 이상');</script>";
echo "<script>window.location.href='board.php?';";
}
else{
echo "<script>window.location.href='post_view.php?id=".$post_id."';";
}

echo '</script>';
include("template/$OJ_TEMPLATE/footer.php");

?>
