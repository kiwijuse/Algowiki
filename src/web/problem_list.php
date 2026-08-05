<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once('./include/my_func.inc.php');
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
$show_title="문제 - AlgoWiki";
$view_title = "Welcome To Online Judge";
$result = false;
include("template/$OJ_TEMPLATE/header.php");
if(isset($_SESSION[$OJ_NAME.'_'.'user_id']))echo '<div style="height:1255px;position:relative;">';
else echo '<div style="height:1255px;position:relative">';

require("./problem/problem_header.php");
echo '<div style="padding: 10px;"></div>';
echo '</div>';
include("template/$OJ_TEMPLATE/footer.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=0.5">
<title>Question Page Prototype</title>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<style>
  .question-card {
    transition: transform 0.3s;
  }
  .question-card:hover {
    transform: scale(1.03);
  }
  .tag {
    cursor: pointer;
    -webkit-user-select:none;
    -moz-user-select:none;
    -ms-user-select:none;
    user-select:none;
  }
  .tag:hover{
    background-color:#DEDEDE;
  }
.custom-margin {
margin-bottom: 150px; 
   }
a{
color:black;
}
a:hover{
color:black;
}

</style>
</head>
<body>
</body>
</html>


