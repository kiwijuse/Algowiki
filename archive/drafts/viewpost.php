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
	.space1 {/*여분 공간 크기 설정*/
       margin-bottom: 10px; 
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
   	echo '<h1 class="text-xl font-semibold" style="font-size: 24px;">'.$row["title"].'</h1>';
	if ($row["problem_id"] !== null) {
   	 echo '<a href="./problem.php?id=' . $row["problem_id"] . '" class="text-sm text-gray-500" style="font-size: 17px;">문제 번호 - ' . $row["problem_id"] . '</a>';
	echo '<div class = "space1"></div>';
	}
	 echo '<a href="./userinfo.php?user=' . $row["writer"] . '" class="text-sm text-gray-500" style="font-size: 17px;">작성자 - ' . $row["writer"] . '</a>';
	echo '<div class = "space1"></div>';
	echo '<div class="border-t pt-4">';
	echo'<div class="mb-4">';
	echo '<p class="text-gray-700">'.nl2br($row["content"]).'</p></div>';
	echo '<div class="border-t pt-4">';
	echo '<h2 class="text-lg font-semibold mb-2">댓글</h2>';}
   
?>

<div class="space-y-2">
        <div class="bg-gray-50 p-3 rounded">
            <p class="text-gray-600">This is a comment from a user.</p>
        </div>
    </div>
</div>
<div class="mt-4">
                <button id="commentButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="showCommentBox()">Add Comment</button>
                <div id="commentBox" class="hidden mt-4">
                    <textarea class="w-full p-2 border rounded" placeholder="Enter your comment here..."></textarea>
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showCommentBox() {
            var commentBox = document.getElementById('commentBox');
            commentBox.classList.toggle('hidden');
        }
    </script>

</body>
</html>
<?php
include("template/$OJ_TEMPLATE/footer.php");
?>
