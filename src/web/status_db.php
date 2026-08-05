
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


$view_user_id = isset($_SESSION[$OJ_NAME.'_'.'user_id']) ? $_SESSION[$OJ_NAME.'_'.'user_id'] : '';
$is_admin = isset($_SESSION[$OJ_NAME.'_'.'administrator']) ? 'True' : 'False';

echo '<div style="position: relative;">';
echo '<table class="table table-borderd table-stroped">
        		<thead>
        			<tr>
        				<th style="width: 7%; font-size:1rem;">채점 번호</center></th>
					<th style="width: 12%; font-size:1rem;">아이디</center></th>
					<th style="width: 9%; font-size:1rem;">문제</center></th>
        				<th style="width: 12%; font-size:1rem;">채점결과</center></th>
					<th style="width: 12%; font-size:1rem;">메모리</center></th>
					<th style="width: 9%; font-size:1rem;">시간</center></th>
					<th style="width: 11%; font-size:1rem;">제출 언어</center></th>
					<th style="width: 11%; font-size:1rem;">코드 길이</center></th>
					<th style="width: 18%; font-size:1rem;">제출 시간</center></th>

        	    		</tr>
        		</thead>
        		<tbody>';
$paged = ($page - 1) * 50;

if($count_search == 0){
	$sql = "select * from solution order by solution_id desc limit $paged,50";	
	}else if(!!$problem_id){
	$sql = "select * from solution where problem_id = '$problem_id' and user_id = '$user_id' and language = '$compile_language' and result = '$grading_result' order by solution_id desc limit $paged,50";
	}else if(!!$user_id){
	$sql = "select * from solution where user_id = '$user_id' and language = '$compile_language' and result = '$grading_result' order by solution_id desc limit $paged,50";
	}else if(!!$compile_language || $compile_language == 0){
	$sql = "select * from solution where language = '$compile_language' and result = '$grading_result' order by solution_id desc limit $paged,50";
	}else{
	$sql = "select * from solution where result = '$grading_result' order by solution_id desc limit $paged,50";
	}
	
	if(!$user_id){
	$sql = str_replace("and user_id = '$user_id'", "", $sql);
	}
	if(!$compile_language && $compile_language != 0 ){
	$sql = str_replace("and language = '$compile_language'", "", $sql);
	}
	if(!$grading_result){
	$sql = str_replace("and result = '$grading_result'", "", $sql);
	}

$result = pdo_query($sql);


foreach ($result as $row){
	$user = $row["user_id"];
	$sql = "select nick_color from users where user_id = '$user'";
	$nickcolor=pdo_query($sql);
	$nick_color = $nickcolor[0][0];
	
	echo '<tr style="font-weight:bolder;"><td style="vertical-align:middle; height:48px;">'.$row["solution_id"].'</center></td>';
	echo '<td style="vertical-align:middle;"><a class ="underline-on-hover" href="userinfo.php?user='.$row["user_id"].'" style="color:'.$nick_color.';">'.$row["user_id"].'</a></td>';
	echo '<td style="vertical-align:middle;"><a href="problem.php?id='.$row["problem_id"].'">'.$row["problem_id"].'</a></center></td>';
	if($row["result"] == 0){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#f0ad4e;" title="">채점 대기 중</a></center></td>';
	}else if($row["result"] == 1){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#f0ad4e;" title="">채점 준비 중</a></center></td>';
	}else if($row["result"] == 2){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#f0ad4e;" title="">컴파일 중</a></center></td>';
	}else if($row["result"] == 3){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#f0ad4e;" title="">채점 중</a></center></td>';
	}else if($row["result"] == 4){
	echo '<td style="vertical-align:middle;font-weight:600;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#5cb85c;" title="모두 정답을 출력했습니다.">정답</a></center></td>';
	}else if($row["result"] == 5){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#d9534f;" title="출력된 형식이 다릅니다. 출력형식/공백/줄바꿈 등을 다시 확인해주세요.">출력형식 다름</a></center></td>';
	}else if($row["result"] == 6){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#d9534f;" title="틀린 답을 출력했습니다. 입력되는 입력데이터의 범위, 출력되어야 할 결과 등을 다시 확인해주세요.">틀림</a></center></td>';
	}else if($row["result"] == 7){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#f0ad4e;" title="실행 제한시간을 초과하였습니다.">시간 초과</a></center></td>';
	}else if($row["result"] == 8){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#f0ad4e;" title="메모리사용 제한용량을 초과하였습니다.">메모리 초과</a></center></td>';
	}else if($row["result"] == 9){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#f0ad4e;" title="너무 많이 출력되었습니다. 무한 반복 출력 등.">출력 초과</a></center></td>';
	}else if($row["result"] == 10){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#f0ad4e;" title="프로그램이 실행되는 도중에 에러가 발생하였습니다.">런타임 에러</a></center></td>';
	}else if($row["result"] == 11){
	echo '<td style="vertical-align:middle;"><a href="ceinfo.php?sid='.$row["solution_id"].'" style="color:#f0ad4e;" title="제출한 코드를 컴파일러가 실행파일로 번역하지 못 했습니다. 링크를 눌러 컴파일 에러를 확인해주세요.">컴파일 에러</a></center></td>';
	}else if($row["result"] == 14){
	echo '<td style="vertical-align:middle;"><a href="reinfo.php?sid='.$row["solution_id"].'" style="color:#5cb85c;" title="">수동확인 대기중...</a></center></td>';
	}


	if($row["result"] == 4){
	echo '<td style="vertical-align:middle;">'.$row["memory"].' KiB</center></td>';
	echo '<td style="vertical-align:middle;">'.$row["time"].' ms</center></td>';
	}else{
	echo '<td style="vertical-align:middle;"></td>';
	echo '<td style="vertical-align:middle;"></td>';
	}
	$sql ="select count(*) from accept where user_id ='$view_user_id' and problem_id='".$row["problem_id"]."'";
	$user_accept = pdo_query($sql);
	echo '<td style="vertical-align:middle;">';
	if($user_accept[0][0] > 0 || $view_user_id == $user || $is_admin =='True'){
	echo '<a href="showsource.php?id='.$row["solution_id"].'">';
	}
	if($row["language"]==0){$language='C';}
	else if($row["language"]==1){$language='C++';}
	else if($row["language"]==2){$language='Pascal';}
	else if($row["language"]==3){$language='Java';}
	else if($row["language"]==6){$language='Python';}
	echo $language;
	if($user_accept[0][0] > 0 || $view_user == $user || $is_admin =='True'){
	echo '</a>';
	}
	echo '</center></td>';
	echo '<td style="vertical-align:middle;">'.$row["code_length"].' bytes</center></td>';
	echo '<td style="width: 5%;vertical-align:middle;">'.$row["judgetime"].'</center></td>';

	echo '</tr>';
	}
echo '</tbody></table>';
?>
