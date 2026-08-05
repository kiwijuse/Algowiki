
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
$sql = "select quest_class, count(*) from quests group by quest_class";
$result = pdo_query($sql);
$daily_count = $result[1][1];
$weekly_count = $result[2][1];
$main_count = $result[3][1] + $result[4][1]; // 히든 + 메인
$sql = "SELECT qc.quest_class, IFNULL(COUNT(p.quest_class), 0) AS count FROM ( SELECT 1 AS quest_class UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) qc LEFT JOIN progress p ON qc.quest_class = p.quest_class AND p.user_id = '$user' AND p.user_prog = p.quest_end_prog GROUP BY qc.quest_class";
$result = pdo_query($sql);
$daily_clear_count = $result[0][1];
$weekly_clear_count = $result[1][1];
$main_clear_count = $result[2][1] + $result[3][1]; // 히든 + 메인


echo '<div class="flex-box" style="display:flex;margin-top:15px;justify-content: center;min-width:544;">
<div class="quest_tab selected" name="일일" onclick="selectTab(this)">일일 퀘스트<br><div style="font-size:0.8rem;foint-weight:300;margin-top:3px;">'.$daily_clear_count.'/'.$daily_count.'</div></div>
<div class="quest_tab" name="주간" onclick="selectTab(this)">주간 퀘스트<br><div style="font-size:0.8rem;foint-weight:300;margin-top:3px;">'.$weekly_clear_count.'/'.$weekly_count.'</div></div>
<div class="quest_tab" name="메인" onclick="selectTab(this)">메인 퀘스트<br><div style="font-size:0.8rem;foint-weight:300;margin-top:3px;">'.$main_clear_count.'/'.$main_count.'</div></div>
</div>';

?>
<script>
</script>
