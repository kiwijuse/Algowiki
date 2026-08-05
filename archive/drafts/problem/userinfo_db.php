
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
$category = isset($_POST['category']) ? $_POST['category'] : '채점현황';
$page = isset($_POST['page']) ? $_POST['page'] : '1';
$pageset = isset($_POST['pageset']) ? $_POST['pageset'] : '1';
$show_tag = isset($_POST['show_tag']) ? $_POST['show_tag'] : '0';
$paged = ($page - 1) * 9;
$user = isset($_POST['user']) ? $_POST['user'] : '';
$view_user = isset($_POST['view_user']) ? $_POST['view_user'] : '';
$is_admin = isset($_POST['is_admin']) ? $_POST['is_admin'] : '';
//정렬 기준
if ($category == "채점 현황"){
echo '<div style="position: relative;">';
echo '<table class="table table-borderd table-stroped">
        		<thead>
        			<tr>
        				<th style="width: 7.5%; font-size:1rem;"><center>채점 번호</center></th>
					<th style="width: 11%; font-size:1rem;"><center>아이디</center></th>
					<th style="width: 4.5%; font-size:1rem;"><center>문제</center></th>
        				<th style="width: 16%; font-size:1rem;"><center>채점결과</center></th>
					<th style="width: 8.5%; font-size:1rem;"><center>메모리</center></th>
					<th style="width: 11%; font-size:1rem;"><center>시간</center></th>
					<th style="width: 8%; font-size:1rem;"><center>제출 언어</center></th>
					<th style="width: 14%; font-size:1rem;"><center>코드 길이</center></th>
					<th style="width: 100%; font-size:1rem;"><center>제출 시간</center></th>

        	    		</tr>
        		</thead>
        		<tbody>';
$sql = "select nick_color from users where user_id = '$user'";
$result = pdo_query($sql);
$nick_color = $result[0][0];
$paged = ($page - 1) * 50;
$sql = "select * from solution where user_id = '$user' order by solution_id desc limit $paged,50";
$count_sql = "select count(*) from solution where user_id = '$user'";
$result = pdo_query($sql);
$count_result = pdo_query($count_sql);

foreach ($result as $row){
	echo '<tr style="font-weight:bolder;;"><td style="vertical-align:middle; height:48px;"><center>'.$row["solution_id"].'</center></td>';
	echo '<td style="vertical-align:middle;"><center><a class ="underline-on-hover" href="userinfo.php?user='.$row["user_id"].'" style="color:'.$nick_color.';">'.$row["user_id"].'</a></center></td>';
	echo '<td style="vertical-align:middle;"><center><a href="problem.php?id='.$row["problem_id"].'">'.$row["problem_id"].'</a></center></td>';
	if($row["result"] == 4){
	echo '<td style="vertical-align:middle;"><center><a href="reinfo.php?sid='.$row["solution_id"].'" class="label label-success" title="모두 정답을 출력했습니다.">모두 맞음</a></center></td>';
	}else if($row["result"] == 5){
	echo '<td style="vertical-align:middle;"><center><a href="reinfo.php?sid='.$row["solution_id"].'" class="label label-danger" title="출력된 형식이 다릅니다. 출력형식/공백/줄바꿈 등을 다시 확인해주세요.">출력형식 다름</a></center></td>';
	}else if($row["result"] == 6){
	echo '<td style="vertical-align:middle;"><center><a href="reinfo.php?sid='.$row["solution_id"].'" class="label label-danger" title="틀린 답을 출력했습니다. 입력되는 입력데이터의 범위, 출력되어야 할 결과 등을 다시 확인해주세요.">틀림</a></center></td>';
	}else if($row["result"] == 7){
	echo '<td style="vertical-align:middle;"><center><a href="reinfo.php?sid='.$row["solution_id"].'" class="label label-warning" title="실행 제한시간을 초과하였습니다.">시간 초과</a></center></td>';
	}else if($row["result"] == 8){
	echo '<td style="vertical-align:middle;"><center><a href="reinfo.php?sid='.$row["solution_id"].'" class="label label-warning" title="메모리사용 제한용량을 초과하였습니다.">메모리 초과</a></center></td>';
	}else if($row["result"] == 9){
	echo '<td style="vertical-align:middle;"><center><a href="reinfo.php?sid='.$row["solution_id"].'" class="label label-warning" title="너무 많이 출력되었습니다. 무한 반복 출력 등.">출력 초과</a></center></td>';
	}else if($row["result"] == 10){
	echo '<td style="vertical-align:middle;"><center><a href="reinfo.php?sid='.$row["solution_id"].'" class="label label-warning" title="프로그램이 실행되는 도중에 에러가 발생하였습니다.">런타임 에러</a></center></td>';
	}else if($row["result"] == 11){
	echo '<td style="vertical-align:middle;"><center><a href="ceinfo.php?sid='.$row["solution_id"].'" class="label label-warning" title="제출한 코드를 컴파일러가 실행파일로 번역하지 못 했습니다. 링크를 눌러 컴파일 에러를 확인해주세요.">컴파일 에러</a></center></td>';
	}





	if($row["result"] == 4){
	echo '<td style="vertical-align:middle;"><center>'.$row["memory"].' KiB</center></td>';
	echo '<td style="vertical-align:middle;"><center>'.$row["time"].' ms</center></td>';
	}else{
	echo '<td style="vertical-align:middle;"><center>---</center></td>';
	echo '<td style="vertical-align:middle;"><center>---</center></td>';
	}
	$sql ="select count(*) from accept where user_id ='$view_user' and problem_id='".$row["problem_id"]."'";
	$user_accept = pdo_query($sql);
	echo '<td style="vertical-align:middle;"><center>';
	if($user_accept[0][0] > 0 || $view_user == $user || $is_admin =='True'){
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
	echo '<td style="vertical-align:middle;"><center>'.$row["code_length"].' bytes</center></td>';
	echo '<td style="width: 5%;vertical-align:middle;"><center>'.$row["judgetime"].'</center></td>';

	echo '</tr>';
	}
echo '</tbody></table>';

$problem_count = $count_result[0][0];
$max_problems_per_page = 50;
$max_pages = 5;
$total_pages = min($max_pages, ceil($problem_count / $max_problems_per_page));
$start_page = max(1, $total_pages - $max_pages + 1);

echo '<div style="position: absolute; bottom: -50px; left: 33.7%; background-color:#f3f3f5; width:350px; border-radius:20px; height:40px;display: flex; flex-direction: column;">
    <div class="pagination-container" style="width:320px;margin-left: auto; margin-right: auto;margin-top:auto; margin-bottom:auto;">
    <div id="doubleLeft" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&laquo;</center></div>
    <div id="singleLeft" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&lsaquo;</center></div>';

for ($i = $start_page; $i <= $total_pages; $i++) {
  $selected_class = ($i == 1) ? 'selected' : ''; // 첫 번째 페이지를 선택 상태로 지정
  echo '<div id="page' . $i . '" class="pagination-item ' . $selected_class . '" >' . $i . '</div>';
}
echo '<div id="singleRight" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&rsaquo;</center></div>
    	<div id="doubleRight" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&raquo;</center></div></div></div></div>';
}


// -----------------------------맞은 문제------------------------------------------

else if($category == "맞은 문제"){
    $sql = "select * from problem p inner join accept a on p.problem_id = a.problem_id where a.user_id = '$user' and p.defunct ='N' order by p.problem_id asc limit $paged,9";
    $count_sql  = "select count(*) from problem p inner join accept a on p.problem_id = a.problem_id where a.user_id = '$user' and p.defunct ='N'";
    $result = pdo_query($sql);
    $count_result = pdo_query($count_sql);
    $i = 0;

foreach ($result as $row) {
$card = '<div class="question-card bg-white p-4" style = " position:relative; border-radius: 10px;border: 1px solid #E2E3E4; height:300px; cursor: pointer;" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
    if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){//로그인 했으면 틀린문제 맞은문제 표시
	$sql = "select distinct user_id from solution where problem_id = ".$row["problem_id"];
  	$problem_solve = pdo_query($sql);
  	foreach ($problem_solve as $solve_row){//틀린문제 있는지 확인
		if($solve_row["user_id"] == $_SESSION[$OJ_NAME.'_'.'user_id']){
		$card = '<div class="question-card bg-white p-4" style="position:relative; border-radius: 10px; height:300px; cursor: pointer; box-shadow: 0 4px 8px rgba(251, 125, 125, 0.2), 0 0 10px rgba(251, 125, 125, 0.1); border: 1px solid rgba(251, 125, 125, 0.3);" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
		break;
		}
	}
	$sql = "select distinct user_id from solution where result =4 and problem_id = ".$row["problem_id"];
  	$problem_solve = pdo_query($sql);
  	foreach ($problem_solve as $solve_row){//맞은 문제 있는지 확인
		if($solve_row["user_id"] == $_SESSION[$OJ_NAME.'_'.'user_id']){
		$card = '<div class="question-card bg-white p-4" style="position:relative; border-radius: 10px; height:300px; cursor: pointer; box-shadow: 0 4px 8px rgba(125, 251, 125, 0.2), 0 0 10px rgba(125, 251, 125, 0.1); border: 1px solid rgba(100, 251, 100, 0.4);" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
		break;
		}
	}	
  }
  
if ($i % 3 == 0) echo '<div style="display:flex;margin-left:7px;">'; // 3개씩 한 라인에 보여줌
  $description = strip_tags($row["description"]); // HTML 태그 제거
  $max_length = 200; // 최대 길이 설정
  if (mb_strlen($description) > $max_length) { //최대 길이 넘어가면 ... 으로 뒷내용 숨기기
    $description = mb_substr($description, 0, $max_length) . ' ...';
  }
  echo '<div style="margin:10px; width:335px;">';  
    echo $card;
    echo '<h3 class="font-semibold mb-1">' . $row["title"] . '</h3>';
    if($show_tag==1){
  echo '<div class="flex space-x-1 text-sm mb-2">';
  $d=0;
  if($row["defunct"] != 'N'){
    echo '<div class="px-2 py-1 bg-red-300 rounded-lg">숨겨짐</div>';
    $d=1;
  }
  $tags = explode(' ', $row["source"]);
  foreach ($tags as $tag) {
    $tag_name = str_replace('_', ' ', $tag);
    echo '<div class="tag px-2 py-1 bg-gray-100 rounded-lg" style="-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;" onclick="redirectToAddress(\'' . $tag_name . '\'); event.stopPropagation();">' . $tag_name . '</div>';
    if($d%4==3) echo '</div><div class="flex space-x-1 text-sm mb-2">';
    $d+=1;
  }
  echo '</div>';
  }
	
  echo '<div class="flex space-x-1 text-sm" style="overflow-y: auto;overflow-x: hidden; height:130px;" >
      	<p class="text-sm mb-4" style="overflow-wrap: break-word;">' . $description . '</p>
	</div>
	<div class="border-t pt-4" style="width :90%;position:absolute; bottom:30px;"></div>
        <div class="flex items-center text-gray-600" style="position:absolute; bottom:15px;">	
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">' . $row["accepted"] . '</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">' . $row["submit"] . '</span>
      	</div></div></div>';
  if ($i % 3 == 2)
    echo '</div>';
  $i += 1;
}
echo '</div><div style="padding: 20px;"></div>';

$problem_count = $count_result[0][0];
$max_problems_per_page = 9;
$max_pages = 5;
$total_pages = min($max_pages, ceil($problem_count / $max_problems_per_page));
$start_page = max(1, $total_pages - $max_pages + 1);

echo '<div class="row" style="position:relative;"><div style="position: absolute; bottom: -20px; left: 33.7%; background-color:#f3f3f5; width:350px; border-radius:20px; height:40px;display: flex; flex-direction: column;">
    <div class="pagination-container" style="width:320px;margin-left: auto; margin-right: auto;margin-top:auto; margin-bottom:auto;">
    <div id="doubleLeft" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&laquo;</center></div>
    <div id="singleLeft" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&lsaquo;</center></div>';

for ($i = $start_page; $i <= $total_pages; $i++) {
  $selected_class = ($i == 1) ? 'selected' : ''; // 첫 번째 페이지를 선택 상태로 지정
  echo '<div id="page' . $i . '" class="pagination-item ' . $selected_class . '" >' . $i . '</div>';
}

echo '<div id="singleRight" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&rsaquo;</center></div>
    	<div id="doubleRight" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&raquo;</center></div></div></div></div>';

}



else if($category == "기여한 문제"){
echo "기여한문제";
}





else{
    $sql = "select * from problem p inner join privilege v on p.problem_id = substring(v.rightstr, 2) where v.user_id = '$user' and p.defunct ='N' order by p.problem_id asc limit $paged,9";
    $count_sql = "select count(*) from problem p inner join privilege v on p.problem_id = substring(v.rightstr, 2) where v.user_id = '$user' and p.defunct ='N' ";
    $result = pdo_query($sql);
    $count_result = pdo_query($count_sql);
    $i = 0;

foreach ($result as $row) {
$card = '<div class="question-card bg-white p-4" style = " position:relative; border-radius: 10px;border: 1px solid #E2E3E4; height:300px; cursor: pointer;" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
  if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){//로그인 했으면 틀린문제 맞은문제 표시
	$sql = "select distinct user_id from solution where problem_id = ".$row["problem_id"];
  	$problem_solve = pdo_query($sql);
  	foreach ($problem_solve as $solve_row){//틀린문제 있는지 확인
		if($solve_row["user_id"] == $_SESSION[$OJ_NAME.'_'.'user_id']){
		$card = '<div class="question-card bg-white p-4" style="position:relative; border-radius: 10px; height:300px; cursor: pointer; box-shadow: 0 4px 8px rgba(251, 125, 125, 0.2), 0 0 10px rgba(251, 125, 125, 0.1); border: 1px solid rgba(251, 125, 125, 0.3);" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
		break;
		}
	}
	$sql = "select distinct user_id from solution where result =4 and problem_id = ".$row["problem_id"];
  	$problem_solve = pdo_query($sql);
  	foreach ($problem_solve as $solve_row){//맞은 문제 있는지 확인
		if($solve_row["user_id"] == $_SESSION[$OJ_NAME.'_'.'user_id']){
		$card = '<div class="question-card bg-white p-4" style="position:relative; border-radius: 10px; height:300px; cursor: pointer; box-shadow: 0 4px 8px rgba(125, 251, 125, 0.2), 0 0 10px rgba(125, 251, 125, 0.1); border: 1px solid rgba(100, 251, 100, 0.4);" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
		break;
		}
	}	
  }

if ($i % 3 == 0) echo '<div style="display:flex;margin-left:7px;">'; // 3개씩 한 라인에 보여줌
  $description = strip_tags($row["description"]); // HTML 태그 제거
  $max_length = 200; // 최대 길이 설정
  if (mb_strlen($description) > $max_length) { //최대 길이 넘어가면 ... 으로 뒷내용 숨기기
    $description = mb_substr($description, 0, $max_length) . ' ...';
  }
  echo '<div style="margin:10px; width:335px;">';  
    echo $card;
    echo '<h3 class="font-semibold mb-1">' . $row["title"] . '</h3>';
    if($show_tag==1){
  echo '<div class="flex space-x-1 text-sm mb-2">';
  $d=0;
  if($row["defunct"] != 'N'){
    echo '<div class="px-2 py-1 bg-red-300 rounded-lg">숨겨짐</div>';
    $d=1;
  }
  $tags = explode(' ', $row["source"]);
  foreach ($tags as $tag) {
    $tag_name = str_replace('_', ' ', $tag);
    echo '<div class="tag px-2 py-1 bg-gray-100 rounded-lg" style="-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;" onclick="redirectToAddress(\'' . $tag_name . '\'); event.stopPropagation();">' . $tag_name . '</div>';
    if($d%4==3) echo '</div><div class="flex space-x-1 text-sm mb-2">';
    $d+=1;
  }
  echo '</div>';
  }
	
  echo '<div class="flex space-x-1 text-sm" style="overflow-y: auto;overflow-x: hidden; height:130px;" >
      	<p class="text-sm mb-4" style="overflow-wrap: break-word;">' . $description . '</p>
	</div>
	<div class="border-t pt-4" style="width :90%;position:absolute; bottom:30px;"></div>
        <div class="flex items-center text-gray-600" style="position:absolute; bottom:15px;">	
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">' . $row["accepted"] . '</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">' . $row["submit"] . '</span>
      	</div></div></div>';
  if ($i % 3 == 2)
    echo '</div>';
  $i += 1;
}
echo '</div><div style="padding: 20px;"></div>';

//문제수를 이용해 페이지수 계산
$problem_count = $count_result[0][0];
$max_problems_per_page = 9;
$max_pages = 5;
$total_pages = min($max_pages, ceil($problem_count / $max_problems_per_page));
$start_page = max(1, $total_pages - $max_pages + 1);

echo '<div class="row" style="position:relative;"><div style="position: absolute; bottom: -20px; left: 33.7%; background-color:#f3f3f5; width:350px; border-radius:20px; height:40px;display: flex; flex-direction: column;">
    <div class="pagination-container" style="width:320px;margin-left: auto; margin-right: auto;margin-top:auto; margin-bottom:auto;">
    <div id="doubleLeft" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&laquo;</center></div>
    <div id="singleLeft" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&lsaquo;</center></div>';

for ($i = $start_page; $i <= $total_pages; $i++) {
  $selected_class = ($i == 1) ? 'selected' : ''; // 첫 번째 페이지를 선택 상태로 지정
  echo '<div id="page' . $i . '" class="pagination-item ' . $selected_class . '" >' . $i . '</div>';
}

echo '<div id="singleRight" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&rsaquo;</center></div>
    	<div id="doubleRight" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&raquo;</center></div></div></div></div>';
}
?>
   


<script>
function redirectToAddress(tag) {
    addFilter(tag);
    toggleDropdown();
}

function ToAddress(tag) {
    window.location.href = 'problem.php?id=' + tag;
}
var category = "<?php echo $category; ?>";
currentPage = <?php echo $page; ?>;
if(category === "채점 현황"){
totalPages = <?php echo ceil($count_result[0][0] / 50); ?>;
}else{
totalPages = <?php echo ceil($count_result[0][0] / 9); ?>;
}
maxPages = <?php echo $max_pages; ?>;
pageSet = <?php echo $pageset; ?>;

document.getElementById('singleLeft').addEventListener('click', () => handleArrowClick('left'));
document.getElementById('singleRight').addEventListener('click', () => handleArrowClick('right'));
document.getElementById('doubleLeft').addEventListener('click', () => handleArrowClick('doubleLeft'));
document.getElementById('doubleRight').addEventListener('click', () => handleArrowClick('doubleRight'));

for (let i = 1; i <= 5; i++) {
    	document.getElementById(`page${i}`).addEventListener('click', function() {
        currentPage = parseInt(this.textContent, 10);
        document.getElementById('Page').value = currentPage;
	updatePagination();
        applyFilter();
    });
    updatePagination();
}
updatePagination();

function updatePagination() {
    for (let i = 1; i <= maxPages; i++) {
        const pageNum = (pageSet - 1) * maxPages + i;
        const pageElement = document.getElementById(`page${i}`);
        if (pageElement) {
            if (pageNum <= totalPages) {
                pageElement.textContent = pageNum;
                pageElement.classList.remove('selected', 'bg-black', 'text-white');
                if (pageNum === currentPage) {
                    pageElement.classList.add('selected', 'bg-black', 'text-white');
                }
                pageElement.style.display = 'inline-block';
            } else {
                pageElement.style.display = 'none';
            }
        }
    }
}
function handleArrowClick(direction) {
    if (direction === 'left' && currentPage > 1) {
        if ((currentPage - 1) % maxPages === 0 && pageSet > 1) {
            pageSet--;
        }
        currentPage--;
    } else if (direction === 'right' && currentPage < totalPages) {
        currentPage++;
        if (currentPage % maxPages === 1) {
            pageSet++;
        }
    } else if (direction === 'doubleLeft') {
        currentPage = 1;
        pageSet = 1;
    } else if (direction === 'doubleRight') {
        currentPage = totalPages;
        pageSet = Math.ceil(totalPages / maxPages);
    }

    document.getElementById('Page').value = currentPage;
    document.getElementById('pageset').value = pageSet;
    applyFilter();
    updatePagination();
}

</script>
<?php if (isset($OJ_MATHJAX) && $OJ_MATHJAX) { ?>

<script>
MathJax = {
    tex: {
        inlineMath: [
            ['$', '$'],
            ['\\(', '\\)']
        ]
    }
};
</script>
<script id="MathJax-script" async src="../template/bs3/tex-chtml.js"></script>
<style>
.jumbotron1 {
    font-size: 18px;
}
</style>
<?php } ?>