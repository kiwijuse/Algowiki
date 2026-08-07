
<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once('../include/cache_start.php');
require_once('../include/db_info.inc.php');
require_once('../include/my_func.inc.php');
require_once('../include/memcache.php');
require_once('../include/setlang.php');
require_once('../include/bbcode.php');

// 요청 파라미터
$user = isset($_POST['user']) ? $_POST['user'] : '';
$sql = "select quest_class, count(*) from quests group by quest_class";
$result = pdo_query($sql);
$daily_count = $result[0][1];
$weekly_count = $result[1][1];
$main_count = $result[2][1] + $result[3][1]; // 히든 + 메인 퀘스트 합산
$sql = "SELECT qc.quest_class, IFNULL(COUNT(p.quest_class), 0) AS count FROM ( SELECT 1 AS quest_class UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) qc LEFT JOIN progress p ON qc.quest_class = p.quest_class AND p.user_id = '$user' AND p.user_prog = p.quest_end_prog AND p.quest_rec_rewards = 1 GROUP BY qc.quest_class";
$result = pdo_query($sql);
$daily_clear_count = $result[0][1];
$weekly_clear_count = $result[1][1];
$main_clear_count = $result[2][1] + $result[3][1]; // 히든 + 메인 퀘스트 합산
$quest = isset($_POST['quest']) ? $_POST['quest'] : '일일';
if($quest =='일일') {
    $quest_class=1;
    $progress_percent = $daily_clear_count / $daily_count * 100;
    $clear_count = $daily_clear_count;
    $quest_count = $daily_count;
    $sql = "select q.*, p.* from progress p, quests q where p.user_id = '$user' and q.quest_class = $quest_class and  p.quest_id = q.quest_id order by p.quest_sort_weight desc";
}
else if($quest=='주간') {
    $quest_class=2;
    $progress_percent = $weekly_clear_count / $weekly_count * 100;
    $clear_count = $weekly_clear_count;
    $quest_count = $weekly_count;
    $sql = "select q.*, p.* from progress p, quests q where p.user_id = '$user' and q.quest_class = $quest_class and  p.quest_id = q.quest_id order by p.quest_sort_weight desc";
}
else {
    $quest_class=3;
    $progress_percent = $main_clear_count / $main_count * 100;
    $clear_count = $main_clear_count;
    $quest_count = $main_count;
    $sql = "select q.*, p.* from progress p, quests q where p.user_id = '$user' and (q.quest_class = $quest_class or q.quest_class = 4) and  p.quest_id = q.quest_id order by p.quest_sort_weight desc";
}
// 퀘스트 진행바
echo '<div style="position:relative;width:100%;height:20px;margin-bottom:25px;"><div id="progressContainer_quest">
    <div id="progressBar_quest" style="width:'.$progress_percent.'%;"></div>
    <div id="progressText_quest">'.$clear_count.'/'.$quest_count.'</div>		    		    
</div></div>';
// 퀘스트 목록
$quest_list=pdo_query($sql);

foreach($quest_list as $row){
$quest_name = $row["quest_name"];
if (mb_strlen($quest_name, 'UTF-8') > 25) {
    $quest_name = mb_substr($quest_name, 0, 25, 'UTF-8') . "...";
}
echo '<div style="position:relative;width:100%;height:132px;margin-bottom:30px;">';
if($row["quest_rec_rewards"] == 0){ // 보상 미수령 상태
	if($row["quest_class"]==4 && $row["user_prog"] < $row["quest_end_prog"]){// 히든 퀘스트 · 미완료 상태
	echo '
	<div class="quest-box flex-box">

    	<div class="quest-border">
            <div class="outer-hexagon">
                <div class="inner-hexagon"><img class="quest-icon-hidden" src="/quest/image/question_mark.png"></div>    
            </div>
    	</div>

    	<div class="quest-info">
	    <div class="quest-info-title">???</div>
	    <div class="quest-info-content">???</div>	    
	    <div class="quest-info-progress">'.$row["user_prog"].'/'.$row["quest_end_prog"].'</div>
        </div>

        <div class="clear-info">
            <div class="exp-pos"><img class="exp-image" src="/image/exp.png"></div>
            <div class="exp-text">'.$row["quest_comp_exp"].'</div>
	  
	    <div class="coin-pos"><img class="coin-image" src="/image/algo_coin.png"></div>
            <div class="coin-text">'.$row["quest_comp_coin"].'</div>

        </div></div>';
        }
	else {
	echo '
	<div class="quest-box flex-box">

    	<div class="quest-border">
            <div class="outer-hexagon">
                <div class="inner-hexagon">';
			if($row["quest_id"] >= 64 && $row["quest_id"] <=66){
			echo '<img class="quest-icon" src="'.$row["quest_image_path"].'" style="height:60px;width:auto;">';
			}else{
			echo '<img class="quest-icon" src="'.$row["quest_image_path"].'">';
			}
		echo '</div>    
            </div>
    	</div>

    	<div class="quest-info">
	    <div class="quest-info-title">'.$quest_name.'</div>';
	    if($row["quest_id"] == 23){
	        echo '<div class="quest-info-content"><a href="problem_list.php?filter1='.str_replace('_', ' ', $row["sub_content"]).'" class = "tag">'.str_replace('_', ' ', $row["sub_content"]).'</a> 태그 한 문제 해결하기</div>';
	    }else if($row["quest_id"] >= 64 && $row["quest_id"] <=66){
	        echo '<div class="quest-info-content"><a href="problem.php?id='.$row["sub_content"].'" class = "tag">'.$row["sub_content"].'</a> 번 문제 해결하기</div>';
	    }else{
	        echo '<div class="quest-info-content">'.$row["content"].'</div>';
	    }
	
	    echo '
	    <div class="quest-info-progress">'.$row["user_prog"].'/'.$row["quest_end_prog"].'</div>
        </div>';

	if($row["user_prog"] >= $row["quest_end_prog"]){
        echo '<div class="clear-info-c">
            <div class="exp-pos"><img class="exp-image" src="/image/exp.png"></div>
            <div class="exp-text">'.$row["quest_comp_exp"].'</div>	  
	    <div class="coin-pos"><img class="coin-image" src="/image/algo_coin.png"></div>
            <div class="coin-text">'.$row["quest_comp_coin"].'</div>
        </div>';	
	}else{
	echo '<div class="clear-info">
            <div class="exp-pos"><img class="exp-image" src="/image/exp.png"></div>
            <div class="exp-text">'.$row["quest_comp_exp"].'</div>	  
	    <div class="coin-pos"><img class="coin-image" src="/image/algo_coin.png"></div>
            <div class="coin-text">'.$row["quest_comp_coin"].'</div>
        </div>';
	}

        if($row["user_prog"] >= $row["quest_end_prog"]){
        echo '
        <div class="receive-button" onclick="get_reward_refresh('.$row["quest_id"].')">
	    <div class="receive-text">보상 수령</div>
        </div>';
        }
     echo '</div>';
    }
}else{// 보상 수령 완료

echo '
<div class="quest-box flex-box">
<div class="clear_quest">
    <div class="quest-border">
        <div class="outer-hexagon">
            <div class="inner-hexagon">';
		if($row["quest_id"] >= 64 && $row["quest_id"] <=66){
			echo '<img class="quest-icon" src="'.$row["quest_image_path"].'" style="height:60px;width:auto;">';
			}else{
			echo '<img class="quest-icon" src="'.$row["quest_image_path"].'">';
			}
	    echo '</div>    
        </div>
	
    </div>

    <div class="quest-info">
	<div class="quest-info-title">'.$quest_name.'</div>';
	if($row["quest_id"] == 23){
	        echo '<div class="quest-info-content"><a href="problem_list.php?filter1='.str_replace('_', ' ', $row["sub_content"]).'" class = "tag">'.str_replace('_', ' ', $row["sub_content"]).'</a> 태그 한 문제 해결하기</div>';
	    }else if($row["quest_id"] >= 64 && $row["quest_id"] <=66){
	        echo '<div class="quest-info-content"><a href="problem.php?id='.$row["sub_content"].'" class = "tag">'.$row["sub_content"].'</a> 번 문제 해결하기</div>';
	    }else{
	        echo '<div class="quest-info-content">'.$row["content"].'</div>';
	    }
	
	echo '
	<div class="quest-info-progress">'.$row["user_prog"].'/'.$row["quest_end_prog"].'</div>
    </div>

    <div class="clear-info">
        <div class="exp-pos"><img class="exp-image" src="/image/exp.png"></div>
        <div class="exp-text">'.$row["quest_comp_exp"].'</div>
	
	<div class="coin-pos"><img class="coin-image" src="/image/algo_coin.png"></div>
        <div class="coin-text">'.$row["quest_comp_coin"].'</div>
	
    </div>
</div><img class="completed" src = "/image/completed.png">
</div>';
}
echo '</div>';
}

?>
<script>
</script>
