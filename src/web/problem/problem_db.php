
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
$sorting = isset($_POST['sorting']) ? $_POST['sorting'] : '전체';
$filter1 = isset($_POST['filter1']) ? $_POST['filter1'] : 'none';
$filter2 = isset($_POST['filter2']) ? $_POST['filter2'] : 'none';
$filter3 = isset($_POST['filter3']) ? $_POST['filter3'] : 'none';
$search = isset($_POST['search']) ? $_POST['search'] : '';
$page = isset($_POST['page']) ? $_POST['page'] : '1';
$pageset = isset($_POST['pageset']) ? $_POST['pageset'] : '1';
$show_problem = isset($_POST['show_problem']) ? $_POST['show_problem'] : '1';
$paged = ($page - 1) * 9;
$user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];
$show_tag = pdo_query("select show_tag from uinfo where user_id = '$user_id'")[0][0];
$show_difficulty = pdo_query("select show_difficulty from uinfo where user_id = '$user_id'")[0][0];

// 태그명은 화면에서 공백을 쓰지만 DB에는 _ 로 저장되어 있어 치환 필요
$filter1 = str_replace(' ', '_', $filter1);
$filter2 = str_replace(' ', '_', $filter2);
$filter3 = str_replace(' ', '_', $filter3);

$filter1 = str_replace("Mo's", "Mos", $filter1);
$filter2 = str_replace("Mo's", "Mos", $filter2);
$filter3 = str_replace("Mo's", "Mos", $filter3);

$search = str_replace( ' ', '', $search);

// 정렬 기준
if ($sorting == '전체')
  $orderby = "problem_id asc limit $paged, 9";
else if ($sorting == '인기')
  $orderby = "submit desc limit $paged, 9";
else if ($sorting == '쉬움')
  $orderby = "problem_id asc limit $paged, 9";
else if ($sorting == '어려움')
  $orderby = "problem_id asc limit $paged, 9";
else
  $orderby = "problem_id desc limit $paged, 9";

// 필터 미선택
if ($filter1 == NULL) {
  if($show_problem==1){// '맞은 문제 표시' 켬
	if(!$search){// 검색어 없음
	$sql = "select * from problem where defunct = 'N' order by $orderby";
	$count_sql = "SELECT COUNT(*) FROM problem where defunct = 'N'";
	}
	else {
	$sql = "select * from problem where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and defunct = 'N' order by $orderby";
	$count_sql = "select count(*) from problem where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and defunct = 'N'";
	}
  }
  else{// '맞은 문제 표시' 끔
	if(!$search){
	$sql ="select problem.* from problem left join accept on problem.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and defunct = 'N' order by $orderby";
  	$count_sql = "select count(*) from problem left join accept on problem.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and defunct = 'N'";
	}
	else{
	$sql ="select problem.* from problem left join accept on problem.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and defunct = 'N' and (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) order by $orderby";
  	$count_sql = "select count(*) from problem left join accept on problem.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and defunct = 'N' and (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0)";

	}
  }
}
// 필터 1개 선택
else if ($filter1 != NULL && $filter2 == NULL) {
  if($show_problem==1){// '맞은 문제 표시' 켬
	if(!$search){
	$sql = "select * from $filter1 where defunct = 'N' order by $orderby";
	$count_sql = "SELECT COUNT(*) FROM $filter1 where defunct = 'N'";
	}
	else{
	$sql = "select * from $filter1 where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and defunct = 'N' order by $orderby";
	$count_sql = "SELECT COUNT(*) FROM $filter1 where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and defunct = 'N'";
	}
  }
  else {// '맞은 문제 표시' 끔
	if(!$search){
	$sql ="select $filter1.* from $filter1 left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and defunct = 'N' order by $orderby";
  	$count_sql = "select count(*) from $filter1 left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and defunct = 'N'";
	}
	else{
	$sql ="select $filter1.* from $filter1 left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and accept.problem_id is null and defunct = 'N' order by $orderby";
  	$count_sql = "select count(*) from $filter1 left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and accept.problem_id is null and defunct = 'N'";
	}
  }   
}
// 필터 2개 선택
else if ($filter1 != NULL && $filter2 != NULL && $filter3 == NULL) {
  if($show_problem==1){// '맞은 문제 표시' 켬
	if(!$search){
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id where $filter1.defunct = 'N' order by $orderby";
	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id where $filter1.defunct = 'N'";
  	}
	else{
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and $filter1.defunct = 'N' order by $orderby";
	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and $filter1.defunct = 'N'";
	}
  }
  else {// '맞은 문제 표시' 끔
	if(!$search){
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and $filter1.defunct = 'N' order by $orderby";
	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and $filter1.defunct = 'N'";
  	}
	else{
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and accept.problem_id is null and $filter1.defunct = 'N' order by $orderby";
	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and accept.problem_id is null and $filter1.defunct = 'N'";
	}
  }
}
// 필터 3개 선택
else {
  if($show_problem==1){// '맞은 문제 표시' 켬
	if(!$search){
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id where $filter1.defunct = 'N' order by $orderby";
 	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id where $filter1.defunct = 'N'";
  	}
	else{
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and $filter1.defunct = 'N' order by $orderby";
 	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and $filter1.defunct = 'N'";
	}
  }
  else {// '맞은 문제 표시' 끔
	if(!$search){
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and $filter1.defunct = 'N' order by $orderby";
  	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where accept.problem_id is null and $filter1.defunct = 'N'";
  	}
	else{
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and accept.problem_id is null and $filter1.defunct = 'N' order by $orderby";
  	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id left join accept on $filter1.problem_id = accept.problem_id and accept.user_id = '$user_id' where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and accept.problem_id is null and $filter1.defunct = 'N'";
	}
  }
}
if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])){// 운영자에게는 숨김 처리된 문제도 표시
$sql = str_replace("where defunct = 'N'", '', $sql);
$sql = str_replace("and defunct = 'N'", '', $sql);
$sql = str_replace("where $filter1.defunct = 'N'", '', $sql);
$sql = str_replace("and $filter1.defunct = 'N'", '', $sql);
$count_sql = str_replace("where defunct = 'N'", '', $count_sql);
$count_sql = str_replace("and defunct = 'N'", '', $count_sql);
$count_sql = str_replace("where $filter1.defunct = 'N'", '', $count_sql);
$count_sql = str_replace("and $filter1.defunct = 'N'", '', $count_sql);
}
$result = pdo_query($sql);
$count_result = pdo_query($count_sql);
$i = 0;
foreach ($result as $row) {
$card = '<div class="question-card bg-white p-4" style = " position:relative; border-radius: 10px;border: 1px solid #E2E3E4; height:300px; cursor: pointer;" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
  if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){// 로그인 상태면 맞은 문제 / 틀린 문제를 구분해 표시
	$sql = "select distinct user_id from solution where problem_id = ".$row["problem_id"];
  	$problem_solve = pdo_query($sql);
  	foreach ($problem_solve as $solve_row){// 틀린 이력이 있는지 확인
		if($solve_row["user_id"] == $_SESSION[$OJ_NAME.'_'.'user_id']){
		$card = '<div class="question-card bg-white p-4" style="position:relative; border-radius: 10px; height:300px; cursor: pointer; box-shadow: 0 4px 8px rgba(251, 125, 125, 0.2), 0 0 10px rgba(251, 125, 125, 0.1); border: 1px solid rgba(251, 125, 125, 0.3);" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
		break;
		}
	}
	$sql = "select distinct user_id from solution where result =4 and problem_id = ".$row["problem_id"];
  	$problem_solve = pdo_query($sql);
  	foreach ($problem_solve as $solve_row){// 맞힌 이력이 있는지 확인
		if($solve_row["user_id"] == $_SESSION[$OJ_NAME.'_'.'user_id']){
		$card = '<div class="question-card bg-white p-4" style="position:relative; border-radius: 10px; height:300px; cursor: pointer; box-shadow: 0 4px 8px rgba(125, 251, 125, 0.2), 0 0 10px rgba(125, 251, 125, 0.1); border: 1px solid rgba(100, 251, 100, 0.4);" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
		break;
		}
	}	
  }
  if ($i % 3 == 0) echo '<div style="display: flex;margin:0 auto;">'; // 한 줄에 카드 3개
  $description = strip_tags($row["description"]); // 미리보기용 — HTML 태그 제거
  $max_length = 230; // 미리보기 최대 길이
  $difficulty = $diff_class[$row["difficulty"]];
  $difficulty_color = $diff_color[$row["difficulty"]];
  $difficulty_src = $diff_src_s[$row["difficulty"]];
  if (mb_strlen($description) > $max_length) { // 최대 길이를 넘으면 말줄임 처리
    $description = mb_substr($description, 0, $max_length) . ' ...';
  }
  echo '<div class="p_box">';  
    echo $card;
    echo '<h3 class="font-semibold mb-1">' . $row["title"] . '</h3>';
  if($show_tag==1){
  echo '<div class="flex text-sm mb-1" style="flex-wrap:wrap;gap:5px;max-height:54px;overflow:hidden;">';
  $d=0;
  if($row["defunct"] != 'N'){
    echo '<div class="px-2 py-1 bg-red-300 rounded-lg">숨겨짐</div>';
  }
  $tags = explode(' ', $row["source"]);
  foreach ($tags as $tag) {
    $tag_name = str_replace('_', ' ', $tag);
    $tag_name = str_replace("Mos", "Mo's", $tag_name);
    if($tag_name=="Mo's")echo '<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'Mos\'); event.stopPropagation();">' . $tag_name . '</div>';
    else echo '<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'' . $tag_name . '\'); event.stopPropagation();">' . $tag_name . '</div>';
  }
  
  echo '</div>';
  }
  $problem_id = $row["problem_id"];  
  if($show_tag==2 && pdo_query("select count(*) from accept where user_id='$user_id' and problem_id='$problem_id'")[0][0] > 0){
  echo '<div class="flex text-sm mb-1" style="flex-wrap:wrap;gap:5px;max-height:54px;overflow:hidden;">';
  if($row["defunct"] != 'N'){
    echo '<div class="px-2 py-1 bg-red-300 rounded-lg">숨겨짐</div>';
  }
  $tags = explode(' ', $row["source"]);
  foreach ($tags as $tag) {
    $tag_name = str_replace('_', ' ', $tag);
    $tag_name = str_replace("Mos", "Mo's", $tag_name);
    if($tag_name=="Mo's")echo '<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'Mos\'); event.stopPropagation();">' . $tag_name . '</div>';
    else echo '<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'' . $tag_name . '\'); event.stopPropagation();">' . $tag_name . '</div>';
      }
  
  echo '</div>';
  }

	$sql = "select accepted, submit from problem where problem_id = ?";
	$res_ = pdo_query($sql, $row["problem_id"]);

  echo '<div class="flex space-x-1 text-sm des-text">
      	<p class="text-sm mb-4" style="overflow-wrap: break-word;">' . $description . '</p>
	</div>
	<div class="border-t pt-4" style="width :90%;position:absolute; bottom:30px;"></div>
        <div class="flex items-center text-gray-600" style="position:absolute; bottom:15px;width:100%;">	
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">' . $res_[0][0] . '</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">' . $res_[0][1] . '</span>';

        if($show_difficulty == 1){
	echo '<div style="position:absolute;right:11%;color:'.$difficulty_color.';">'.$difficulty.'</div>';
      	}else if($show_difficulty == 2 && pdo_query("select count(*) from accept where user_id='$user_id' and problem_id='$problem_id'")[0][0] > 0){
	echo '<div style="position:absolute;right:11%;color:'.$difficulty_color.';">'.$difficulty.'</div>';
      	}
	echo '</div></div></div>';
  if ($i % 3 == 2)
    echo '</div>';
  $i++;
}
while($i%3!=0){
   echo '<div class="p_box_hidden"></div>';
   $i++;
}
echo '</div>';


// 전체 문제 수로 페이지 수 계산
$problem_count = $count_result[0][0];
$max_problems_per_page = 9;
$max_pages = 5;
$total_pages = min($max_pages, ceil($problem_count / $max_problems_per_page));
$start_page = max(1, $total_pages - $max_pages + 1);

echo '<div style="position: absolute; bottom: 65px; left: 0; right: 0; margin-left: auto; margin-right: auto; width:350px; border-radius:20px; border: 1px solid #E2E3E4;height:40px;display: flex; flex-direction: column;">
    <div class="pagination-container">
    <div id="doubleLeft" class="arrow"><center>&laquo;</center></div>
    <div id="singleLeft" class="arrow"><center>&lsaquo;</center></div>';

for ($i = $start_page; $i <= $total_pages; $i++) {
  $selected_class = ($i == 1) ? 'selected' : ''; // 첫 페이지를 선택 상태로 지정
  echo '<div id="page' . $i . '" class="pagination-item ' . $selected_class . '" >' . $i . '</div>';
}

echo '<div id="singleRight" class="arrow"><center>&rsaquo;</center></div>
    	<div id="doubleRight" class="arrow"><center>&raquo;</center></div></div></div>';
?>

<script>
function redirectToAddress(tag) {
    addFilter(tag);
    toggleDropdown();
}

function ToAddress(tag) {
    window.location.href = 'problem.php?id=' + tag;
}

currentPage = <?php echo $page; ?>;
totalPages = <?php echo ceil($count_result[0][0] / 9); ?>;
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