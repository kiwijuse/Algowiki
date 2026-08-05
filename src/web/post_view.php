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
$post_id=intval($_GET["id"]);
$sql = "SELECT * FROM `post` where post_id = '$post_id' ";
$result = pdo_query($sql);
$show_title = $result[0][1]. " - AlgoWiki";
include("template/$OJ_TEMPLATE/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="board.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
	.space1 {/*여분 공간 크기 설정*/
       margin-bottom: 10px; 
}
.space2 {/*여분 공간 크기 설정*/
       margin-top: 50px; 
}
.space3 {/*여분 공간 크기 설정*/
       margin-top: 30px; 
}

.bg-custom-edit {
	background-color: #1CC88A;
}

.hover\:bg-custom-edit:hover {
	background-color: #289e73;
}
.content-container {
 	display: flex;
	justify-content: space-between;
	align-items: center;
}

.content-wrapper {
	flex: 1;
	margin-right: 10px;
}

.delete-button {
	background-color: #ff0000;
    	color: #ffffff;
    	border: none;
    	padding: 5px 10px;
    	cursor: pointer;
}
</style>
</head>
<body class="bg-gray-100">
<?php 
echo '<div class="container mx-auto p-4">';
echo '<div class="bt_wrap left">
                <a href="./board.php" class="on">전체</a>
		<a href="./board.php?data=free" class="on">자유</a>
		<a href="./board.php?data=question" class="on">질문</a>
		<a href="./board.php?data=report" class="on">제보</a>
	</div><div class="space3"></div>';

echo '<div class="bg-white shadow-md rounded p-6 mb-4" style="border: 2px solid #F2F2F2;">';/*게시글 크게 두르는 박스*/
if($result[0][2]==$_SESSION[$OJ_NAME.'_user_id']||isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
	echo '<div style="text-align: right;">	
	<button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="post_delete(' . $post_id . ')" style="float:right;">삭제</button>
   	<button class="bg-custom-edit hover:bg-custom-edit text-white font-bold py-2 px-4 rounded" onclick="post_edit(' . $post_id . ')" style="float:right;">수정</button>
	</div>';
}
echo '<h1 class="text-xl font-semibold" style="font-size: 24px;">['.$result[0][4].'] '.$result[0][1].'</h1>';
if ($result[0][5] !== null) {
   	 echo '<a href="./problem.php?id=' . $result[0][5] . '" class="text-sm text-gray-500" style="font-size: 17px;">문제 번호 - ' . $result[0][5] . '</a>';
	echo '<div class = "space1"></div>';
}
echo '<span style="display: inline-block; width: 50%;">' . 
  	'<a href="./userinfo.php?user=' . $result[0][2] . '" class="text-sm text-gray-500" style="font-size: 17px;">' . $result[0][2] . '</a>' . 
     	'</span>' .
     	'<span style="display: inline-block; width: 50%; text-align: right;">' .
        '<h1 class="text-sm text-gray-500" style="font-size: 17px;">'.$result[0][6].'</h1>' .
   	'</span>';

echo '<div class = "space1"></div>';
echo '<div class="border-t pt-4">';
echo'<div class="my-8">';
echo '<p class="text-gray-700">'.nl2br($result[0][3]).'</p></div>';
echo '<div class="border-t pt-4">';
echo '<h2 class="text-lg font-semibold mb-2">댓글</h2>';
	
   	$sqlc = "SELECT * FROM `comment` where post_id = '$post_id' && parent_id = 0 order by 'create_time' desc";
	$resultc = pdo_query($sqlc);
	foreach($resultc as $row){/*댓글 표시*/
        	echo '<div class="bg-gray-50 p-3" style="border-radius:15px;">';				
		echo '<span style="display: inline-block; width: 50%;">' . 
        	'<a href="./userinfo.php?user=' . $row["writer"] . '" class="text-sm text-gray-500" style="font-size: 17px;">' . $row["writer"] . '</a>' . 
     		'</span>' .
     		'<span style="display: inline-block; width: 50%; text-align: right;">' .
        	'<h1 class="text-sm text-gray-500" style="font-size: 17px;">'.$row["create_time"].'</h1>' .
 		'</span>';
		echo '<div class = "space1"></div>';
            	echo '<div class="content-container">';
		echo '<div class="content-wrapper" >';
		echo '<p class="text-gray-600">'.nl2br($row["content"]).'</p></div>';
		if($row["writer"]==$_SESSION[$OJ_NAME.'_user_id']||isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
			echo '<button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="comment_delete('.$row["comment_id"].')" style="float:right; margin-top: auto;">삭제</button>';
		}
		echo '</div>';

		$sqlcc = "SELECT * FROM `comment` where post_id = '$post_id' && parent_id = ".$row["comment_id"]." order by 'create_time' desc";
		$resultcc = pdo_query($sqlcc);
		foreach($resultcc as $rowc){/*대댓글 표시*/
			echo '<div class = "space1"></div>';			
			echo '<div class="border-t pt-2 ml-10"></div>';
			echo '<span style="display: inline-block; width: 50%;">' . 
        		'<a href="./userinfo.php?user=' . $rowc["writer"] . '" class="text-sm text-gray-500 ml-10" style="font-size: 17px;">' . $rowc["writer"] . '</a>' . 
     			'</span>' .
     			'<span style="display: inline-block; width: 50%; text-align: right;">' .
        		'<h1 class="text-sm text-gray-500" style="font-size: 17px;">'.$rowc["create_time"].'</h1>' .
   			'</span>';
			echo '<div class = "space1"></div>';
            		echo '<div class="content-container ml-10">';
			echo '<div class="content-wrapper">';
			echo '<p class="text-gray-600">'.nl2br($rowc["content"]).'</p></div>';
			if($rowc["writer"]==$_SESSION[$OJ_NAME.'_user_id']||isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
			echo '<button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="comment_delete('.$rowc["comment_id"].')" style="float:right; margin-top: auto;">삭제</button>';
			}
			echo '</div>';
		}
		echo '</div>';

		if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){/*로그인 했을 시 대댓글 달기 가능*/
		$commentId = $row["comment_id"];	
		if($row["writer"]!='<del>Unknown</del>'){/*삭제된 댓글이면 답글 기능 X*/
			echo '<div class = "space1"></div>';
			echo '<button id="commentButton_' . $commentId . '" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="showCommentBox(' . $commentId . ')" style="float: right;">답글</button>';
   			echo '<div class = "space2"></div>';
			echo '<div id="commentBox_' . $commentId . '" class="hidden mt-4">';
			echo '<form method=POST action=comment_add.php?id='.$post_id.'>';
   			echo '<textarea class="w-full p-2 border rounded" placeholder="답글 입력" name="content"></textarea>';
			echo '<input type = "hidden" value = "' . $row["comment_id"] . '" name="parent_id">';
   			echo '<input type = submit value= "저장" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2" type="button" style="float: right;">';
   			echo '</form></div>';
			echo '<div class = "space2"></div>';			
			}		
		}		
		echo '<div class = "space3"></div>';
		if (next($resultc)) {/*다음이 있으면 줄과 공간 생성*/
            		echo '<div class="border-t pt-4"></div>';
			echo '<div class = "space1"></div>';
       		}
		
	}

if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){/*로그인 했을시 댓글 달기 가능*/
echo '<h2 class="text-lg font-semibold">댓글 달기</h2>
<div id="commentBoxs" class="mt-4"><!--댓글 달기-->
		<form method=POST action=comment_add.php?id='.$post_id.'>
		<input type = "hidden" value = 0 name="parent_id">
		<textarea class="w-full p-2 border rounded" placeholder="댓글 입력" name="content"></textarea>
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

	function post_delete(post_id) {
       	 var confirmDelete = confirm('정말 삭제하시겠습니까?');
	 if (confirmDelete) {
		window.location.href = 'post_delete.php?id=' + post_id;
   		}   	
	}

	function post_edit(post_id) {
       	 window.location.href = 'post_edit.php?id=' + post_id;
	}
	function comment_delete(comment_id) {
       	var confirmDelete = confirm('정말 삭제하시겠습니까?');
	var post_id = <?php echo $post_id; ?>;
	 if (confirmDelete) {
		window.location.href = 'comment_delete.php?cid=' + comment_id + '&pid=' + post_id;
   		}   	
	}

    </script>
</body>
</html>
<?php
include("template/$OJ_TEMPLATE/footer.php");
?>
