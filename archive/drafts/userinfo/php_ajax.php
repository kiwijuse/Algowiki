
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
$source = isset($_POST['source']) ? $_POST['source'] : '';
echo $difficulty;

if($difficulty!='' && $tag !='' && $source !=''){
$sql = "update uinfo set show_difficulty = '$difficulty', show_tag = '$tag', show_source = '$source' where user_id = '$user'";
pdo_query($sql);
}


?>
<script>
</script>