
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
$quest_id = isset($_POST['quest_id']) ? $_POST['quest_id'] : '';

$sql = "select user_prog,quest_end_prog,quest_rec_rewards from progress where user_id = '$user' and quest_id = '$quest_id'";
$result = pdo_query($sql);
$user_prog = $result[0][0];
$quest_end_prog = $result[0][1];
$quest_rec_rewards = $result[0][2];

if($user_prog >= $quest_end_prog && $quest_rec_rewards == 0){
$sql = "select quest_comp_coin, quest_comp_exp from quests where quest_id ='$quest_id'";
$result = pdo_query($sql);
$comp_coin = $result[0][0];
$comp_exp = $result[0][1];

$sql = "select acc_exp,coin from uinfo where user_id = '$user'";
$result = pdo_query($sql);
$exp = $result[0][0] + $comp_exp;
$coin = $result[0][1] + $comp_coin;
$sql = "update uinfo set acc_exp = '$exp',coin = '$coin' where user_id = '$user'";
pdo_query($sql);
$sql = "update progress set quest_rec_rewards = 1 where user_id = '$user' and quest_id = '$quest_id'";
pdo_query($sql);
$sql = "update progress set quest_sort_weight = -1 where user_id = '$user' and quest_id = '$quest_id'";
pdo_query($sql);

}
?>
<script>
</script>
