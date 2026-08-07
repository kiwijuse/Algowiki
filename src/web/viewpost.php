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
include("template/$OJ_TEMPLATE/header.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
	.space1 {/* 여백 */
       margin-bottom: 10px; 
}
.space2 {/* 여백 */
       margin-top: 50px; 
}

    </style>
</head>
<body class="bg-gray-100">
<?php 
echo '<div class="container mx-auto p-4">';
echo '<div class="bg-white shadow-md rounded p-6 mb-4">';
echo '<div class="border-b pb-4 mb-4">';
$post_id=intval($_GET["id"]);
$sql = "SELECT `problem_id`,`title`,`writer`,`content`,`created_time` FROM `post` where post_id = '$post_id' ";
$result = pdo_query($sql);
foreach($result as $row){
	if($row["writer"]==$_SESSION[$OJ_NAME.'_user_id']){
	echo "대충 내가 작성자면 수정 , 삭제 ㅆㄱㄴ하게 만들기";
		}
   	echo '<h1 class="text-xl font-semibold" style="font-size: 24px;">'.$row["title"].'</h1>';
	if ($row["problem_id"] !== null) {
   	 echo '<a href="./problem.php?id=' . $row["problem_id"] . '" class="text-sm text-gray-500" style="font-size: 17px;">문제 번호 - ' . $row["problem_id"] . '</a>';
	echo '<div class = "space1"></div>';
		}
	echo '<span style="display: inline-block; width: 50%;">' . 
        '<a href="./userinfo.php?user=' . $row["writer"] . '" class="text-sm text-gray-500" style="font-size: 17px;">' . $row["writer"] . '</a>' . 
     	'</span>' .
     	'<span style="display: inline-block; width: 50%; text-align: right;">' .
        '<h1 class="text-sm text-gray-500" style="font-size: 17px;">'.$row["created_time"].'</h1>' .
   	'</span>';

	echo '<div class = "space1"></div>';
	echo '<div class="border-t pt-4">';
	echo'<div class="my-8">';
	echo '<p class="text-gray-700">'.nl2br($row["content"]).'</p></div>';
	echo '<div class="border-t pt-4">';
	echo '<h2 class="text-lg font-semibold mb-2">댓글</h2>';
	}


   	$sqlc = "SELECT * FROM `comment` where post_id = '$post_id' && parent_id = 0 order by 'create_time' desc";
	$resultc = pdo_query($sqlc);
	foreach($resultc as $row){
		echo '<div class="space-y-2">';
        	echo '<div class="bg-gray-50 p-3 rounded">';
		echo '<span style="display: inline-block; width: 50%;">' . 
        	'<a href="./userinfo.php?user=' . $row["writer"] . '" class="text-sm text-gray-500" style="font-size: 17px;">' . $row["writer"] . '</a>' . 
     		'</span>' .
     		'<span style="display: inline-block; width: 50%; text-align: right;">' .
        	'<h1 class="text-sm text-gray-500" style="font-size: 17px;">'.$row["create_time"].'</h1>' .
   		'</span>';
		echo '<div class = "space1"></div>';
            	echo '<p class="text-gray-600">'.nl2br($row["content"]).'</p></div></div>';


		$sqlcc = "SELECT * FROM `comment` where post_id = '$post_id' && parent_id = ".$row["comment_id"]." order by 'create_time' desc";
		$resultcc = pdo_query($sqlcc);
		foreach($resultcc as $rowc){
			echo '<div class="space-y-2">';
        		echo '<div class="bg-gray-50 p-3 rounded">';
			echo '<span style="display: inline-block; width: 50%;">' . 
        		'<a href="./userinfo.php?user=' . $rowc["writer"] . '" class="text-sm text-gray-500" style="font-size: 17px;">' . $rowc["writer"] . '</a>' . 
     			'</span>' .
     			'<span style="display: inline-block; width: 50%; text-align: right;">' .
        		'<h1 class="text-sm text-gray-500" style="font-size: 17px;">'.$rowc["create_time"].'</h1>' .
   			'</span>';
			echo '<div class = "space1"></div>';
            		echo '<p class="text-gray-600">'.nl2br($rowc["content"]).'</p></div></div>';

		}
		if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){/* 로그인 상태에서만 대댓글 작성 가능 */
		$commentId = $row["comment_id"];/* 대댓글 입력 */
		echo '<div class = "space1"></div>';
		echo '<button id="commentButton_' . $commentId . '" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="showCommentBox(' . $commentId . ')" style="float: right;">답글</button>';
   		echo '<div class = "space2"></div>';

		echo '<div id="commentBox_' . $commentId . '" class="hidden mt-4">';
		echo '<form method=POST action=comment_add.php?id='.$post_id.'>';
   		echo '<textarea class="w-full p-2 border rounded" placeholder="답글 입력" name="content"></textarea>';
		echo '<input type = "hidden" value = "' . $row["comment_id"] . '" name="parent_id">';
   		echo '<input type = submit value= "저장" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2" type="button" style="float: right;">';
   		echo '</form></div>';
		
		}		
		echo '<div class = "space2"></div>';
		echo '<div class="border-t pt-4"></div>';
		echo '<div class = "space1"></div>';
	}

if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){/* 로그인 상태에서만 댓글 작성 가능 */
echo '<h2 class="text-lg font-semibold">댓글 달기</h2>
<div id="commentBoxs" class="mt-4"><!-- 댓글 입력 -->
		<form method=POST action=comment_add.php?id='.$post_id.'>
		<input type = "hidden" value = 0 name="parent_id">
		<textarea class="w-full p-2 border rounded" placeholder="답글 입력" name="content"></textarea>
  		<input type = submit value= "저장" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2" type="button" style="float: right;">
		<div class = "space2"></div>
		</form>
</div>';

}
?>
		</div>
        	</div>
    		</div>

    <script>
        function showCommentBox(commentId) {
        var commentBox = document.getElementById('commentBox_' + commentId);
        commentBox.classList.toggle('hidden');
    }
    </script>

</body>
</html>
<?php
include("template/$OJ_TEMPLATE/footer.php");
?>
