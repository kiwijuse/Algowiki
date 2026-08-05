
<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once('../include/cache_start.php');
require_once('../include/db_info.inc.php');
require_once('../include/my_func.inc.php');
require_once('../include/memcache.php');
require_once('../include/setlang.php');
require_once('../include/bbcode.php');

// 정보들 가져오기
$user = isset($_POST['user']) ? $_POST['user'] : '';

$difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : '';
$tag = isset($_POST['tag']) ? $_POST['tag'] : '';

$git = isset($_POST['git']) ? $_POST['git'] : '';
$blog = isset($_POST['blog']) ? $_POST['blog'] : '';
$discord = isset($_POST['discord']) ? $_POST['discord'] : '';

$comment = isset($_POST['comment']) ? $_POST['comment'] : '';

$show_source = isset($_POST['show_source']) ? $_POST['show_source'] : '';
$show_git = isset($_POST['show_git']) ? $_POST['show_git'] : '';
$show_discord = isset($_POST['show_discord']) ? $_POST['show_discord'] : '';
$show_blog = isset($_POST['show_blog']) ? $_POST['show_blog'] : '';

if($difficulty!='' && $tag !=''){
$sql = "update uinfo set show_difficulty = '$difficulty', show_tag = '$tag' where user_id = '$user'";
pdo_query($sql);
}

if($git!='' || $blog!='' || $discord!=''){
if($blog!='' && !preg_match('/^https?:\/\//', $blog)) exit(0);
if($discord!='' && !preg_match('/^\d{17,18}$/', $discord)) exit(0);
$sql = "update user_link set git_link = '$git',discord_link = '$discord',blog_link = '$blog' where user_id ='$user'";
pdo_query($sql);
}

if($comment!=''){
$sql = "update users set comment = '$comment' where user_id ='$user'";
pdo_query($sql);
}

if($show_source!='' && $show_git !='' && $show_discord !='' && $show_blog !=''){
$sql = "update uinfo set show_source = '$show_source',show_git='$show_git',show_discord='$show_discord',show_blog='$show_blog' where user_id ='$user'";
pdo_query($sql);
} 

?>
<script>
</script>