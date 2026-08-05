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
$title = $_POST['title'];
$content = $_POST['content'];
$category = $_POST['category'];
$problem_id = $_POST['problem_id'];
$writer = $_POST['writer'];
$title = trim($title);
$content = trim($content);
if(false){
  $title = stripslashes($title);
  $content = stripslashes($content);
}
$content = str_replace("<p>", "", $content);
$content = str_replace("</p>", "<br />", $content);
$content = str_replace(",", "&#44;", $content);
if(empty($problem_id)){
	$sql = "UPDATE post SET title ='$title', content='$content', category ='$category', problem_id=NULL, created_time = now() where post_id = '$post_id' ";
}
else{
	$sql2 = "select count(*) from problem where problem_id = '$problem_id' and defunct = 'N'";
	$result2 = pdo_query($sql2);
	if($result2[0][0] == 0){/*문제 번호가 존재하는 문제가 아닐경우 컷*/
	echo "<script>alert('게시글 수정에 실패했습니다.\\n게시글 제목 - 4글자 이상\\n문제번호 - 현재 존재하는 문제번호만 가능\\n내용 - 4글자 이상');</script>";
	echo "<script>window.location.href='board.php';</script>";
	exit(0);
	}
	$sql = "UPDATE post SET title ='$title', content='$content', category ='$category', problem_id = '$problem_id', created_time = now() where post_id = '$post_id' ";

}
$sqlx = "select created_time from post where writer = '$writer' order by created_time desc";
$last_time = pdo_query($sqlx);
$sqlx = "select now()";
$current_time = pdo_query($sqlx);
$time_last = new DateTime($last_time[0][0]);
$time_current = new DateTime($current_time[0][0]);
$time_difference = $time_current->getTimestamp() - $time_last->getTimestamp();
if($time_difference < 20){/*마지막 게시글 작성,수정 시간과 20초 이상 차이가 안날경우 컷*/
	echo "<script>alert('너무 빠릅니다.\\n잠시후 다시 시도해주세요.');</script>";
	echo "<script>window.location.href='board.php';</script>";
	exit(0);
}
$result = pdo_query($sql);
if($result==NULL){/*db insert 시도 실패시 컷*/
echo "<script>alert('게시글 수정에 실패했습니다.\\n게시글 제목 - 4글자 이상\\n문제번호 - 현재 존재하는 문제번호만 가능\\n내용 - 4글자 이상');</script>";
echo "<script>window.location.href='board.php?';";
}
else{
echo "<script>window.location.href='post_view.php?id=".$post_id."';";
}
echo '</script>';
include("template/$OJ_TEMPLATE/footer.php");
?>
