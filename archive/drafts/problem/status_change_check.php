
<?php
// 정보들 가져오기
require_once('../include/cache_start.php');
require_once('../include/db_info.inc.php');
require_once('../include/my_func.inc.php');
require_once('../include/memcache.php');
require_once('../include/setlang.php');
require_once('../include/bbcode.php');

$page = isset($_POST['page']) ? $_POST['page'] : '1';
$pageset = isset($_POST['pageset']) ? $_POST['pageset'] : '1';
$problem_id = isset($_POST['problem_id']) ? $_POST['problem_id'] : '';
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$compile_language = isset($_POST['compile_language']) ? $_POST['compile_language'] : '';
$grading_result = isset($_POST['grading_result']) ? $_POST['grading_result'] : '';
$count_search = 0;
if(!!$user_id) $count_search += 1;
if(!!$problem_id) $count_search += 1;
if(!!$grading_result) $count_search += 1;
if($compile_language == 0 || $compile_language == 1 || $compile_language == 2 || $compile_language == 3 || $compile_language == 6) $count_search += 1;

if($count_search == 0){
	$sql = "select solution_id,result from solution order by solution_id desc limit 1";	
	}else if(!!$problem_id){
	$sql = "select solution_id,result from solution where problem_id = '$problem_id' and user_id = '$user_id' and language = '$compile_language' and result = '$grading_result' order by solution_id desc limit 1";
	}else if(!!$user_id){
	$sql = "select solution_id,result from solution where user_id = '$user_id' and language = '$compile_language' and result = '$grading_result' order by solution_id desc limit 1";
	}else if(!!$compile_language || $compile_language == 0){
	$sql = "select solution_id,result from solution where language = '$compile_language' and result = '$grading_result' order by solution_id desc limit 1";
	}else{
	$sql = "select solution_id,result from solution where result = '$grading_result' order by solution_id desc limit 1";
	}	
	if(!$user_id){
	$sql = str_replace("and user_id = '$user_id'", "", $sql);
	}
	if(!$compile_language && $compile_language != 0){
	$sql = str_replace("and language = '$compile_language'", "", $sql);
	}
	if(!$grading_result){
	$sql = str_replace("and result = '$grading_result'", "", $sql);
	}
	$check_result=pdo_query($sql);
	$check_ids = $check_result[0][0];
	$check_results = $check_result[0][1];

?> 
<script>
if(solution_id_check != <?php echo $check_ids; ?> || solution_result_check != <?php echo $check_results; ?> || <?php echo $check_results; ?> == 0){
	solution_id_check = <?php echo $check_ids; ?>;
	solution_result_check = <?php echo $check_results; ?>;
	status_ajax();
}
</script>