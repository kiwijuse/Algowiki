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
$sorting = isset($_POST['sorting']) ? $_POST['sorting'] : '전체';
$filter1 = isset($_POST['filter1']) ? $_POST['filter1'] : 'none';
$filter2 = isset($_POST['filter2']) ? $_POST['filter2'] : 'none';
$filter3 = isset($_POST['filter3']) ? $_POST['filter3'] : 'none';
$search = isset($_POST['search']) ? $_POST['search'] : '';
$page = isset($_POST['page']) ? $_POST['page'] : '1';
$pageset = isset($_POST['pageset']) ? $_POST['pageset'] : '1';
$show_tag = isset($_POST['show_tag']) ? $_POST['show_tag'] : '0';
$show_problem = isset($_POST['show_problem']) ? $_POST['show_problem'] : '1';
$paged = ($page - 1) * 9;
$user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];

//필터 정보들은 공백을 포함하지만 db에는 공백 대신 _ 로 저장되어 있으므로 db 검색을 위한 치환
$filter1 = str_replace(' ', '_', $filter1);
$filter2 = str_replace(' ', '_', $filter2);
$filter3 = str_replace(' ', '_', $filter3);
$search = str_replace( ' ', '', $search);
//정렬 기준
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

// 필터 선택 안했을경우
if ($filter1 == NULL) {
  if($show_problem==1){// 맞은 문제 표시를 켠 경우
	if(!$search){//검색한게 있으면
	$sql = "select * from problem where defunct = 'N' order by $orderby";
	$count_sql = "SELECT COUNT(*) FROM problem where defunct = 'N'";
	}
	else {
	$sql = "select * from problem where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and defunct = 'N' order by $orderby";
	$count_sql = "select count(*) from problem where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and defunct = 'N'";
	}
  }
  else{// 맞은 문제 표시를 끈 경우
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
// 필터 선택 1개일때
else if ($filter1 != NULL && $filter2 == NULL) {
  if($show_problem==1){// 맞은 문제 표시를 켠 경우
	if(!$search){
	$sql = "select * from $filter1 where defunct = 'N' order by $orderby";
	$count_sql = "SELECT COUNT(*) FROM $filter1 where defunct = 'N'";
	}
	else{
	$sql = "select * from $filter1 where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and defunct = 'N' order by $orderby";
	$count_sql = "SELECT COUNT(*) FROM $filter1 where (instr(replace(title, ' ', ''), '$search') > 0 or instr(replace(description, ' ', ''), '$search') > 0) and defunct = 'N'";
	}
  }
  else {// 맞은 문제 표시를 끈 경우
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
// 필터 선택 2개일때
else if ($filter1 != NULL && $filter2 != NULL && $filter3 == NULL) {
  if($show_problem==1){// 맞은 문제 표시를 켠 경우
	if(!$search){
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id where $filter1.defunct = 'N' order by $orderby";
	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id where $filter1.defunct = 'N'";
  	}
	else{
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and $filter1.defunct = 'N' order by $orderby";
	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and $filter1.defunct = 'N'";
	}
  }
  else {// 맞은 문제 표시를 끈 경우
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
// 필터 선택 3개일때
else {
  if($show_problem==1){//맞은 문제 표시를 켠 경우
	if(!$search){
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id where $filter1.defunct = 'N' order by $orderby";
 	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id where $filter1.defunct = 'N'";
  	}
	else{
	$sql = "select $filter1.* from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and $filter1.defunct = 'N' order by $orderby";
 	$count_sql = "select COUNT(*) from $filter1 inner join $filter2 on $filter1.problem_id = $filter2.problem_id inner join $filter3 on $filter2.problem_id = $filter3.problem_id where (instr(replace($filter1.title, ' ', ''), '$search') > 0 or instr(replace($filter1.description, ' ', ''), '$search') > 0) and $filter1.defunct = 'N'";
	}
  }
  else {//맞은 문제 표시를 끈 경우
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
if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])){//운영자 일시 숨긴문제 표시
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
echo '<div style="padding: 10px;"></div>';
foreach ($result as $row) {
$card = '<div class="question-card bg-white p-4 shadow" style = " position:relative; border-radius: 10px; height:300px; cursor: pointer;" onclick="ToAddress(\'' . $row["problem_id"] . '\')">';
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
  if ($i % 3 == 0) echo '<div style="display: flex;">'; // 3개씩 한 라인에 보여줌
  $description = strip_tags($row["description"]); // HTML 태그 제거
  $max_length = 230; // 최대 길이 설정
  if (mb_strlen($description) > $max_length) { //최대 길이 넘어가면 ... 으로 뒷내용 숨기기
    $description = mb_substr($description, 0, $max_length) . ' ...';
  }
  echo '<div style="margin:10px; width:335px;">';  
    echo $card;
    echo '<h3 class="font-semibold mb-1">' . $row["title"] . '</h3>';
    if($show_tag==1){
  echo '<div class="flex space-x-1 text-sm mb-2">';
  if($row["defunct"] != 'N'){
    echo '<div class="px-2 py-1 bg-red-300 rounded-lg">숨겨짐</div>';
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
echo '</div><div style="padding: 10px;"></div>';

//문제수를 이용해 페이지수 계산
$problem_count = $count_result[0][0];
$max_problems_per_page = 9;
$max_pages = 5;
$total_pages = min($max_pages, ceil($problem_count / $max_problems_per_page));
$start_page = max(1, $total_pages - $max_pages + 1);

echo '<div class="pagination-container" style="position: absolute; bottom: 75px; left: 0; right: 0; margin-left: auto; margin-right: auto;">
    <div id="doubleLeft" class="arrow">&laquo;</div>
    <div id="singleLeft" class="arrow">&lsaquo;</div>';

for ($i = $start_page; $i <= $total_pages; $i++) {
  $selected_class = ($i == 1) ? 'selected' : ''; // 첫 번째 페이지를 선택 상태로 지정
  echo '<div id="page' . $i . '" class="pagination-item ' . $selected_class . '">' . $i . '</div>';
}

echo '<div id="singleRight" class="arrow">&rsaquo;</div>
    	<div id="doubleRight" class="arrow">&raquo;</div></div>';
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
<script id="MathJax-script" async src="template/bs3/tex-chtml.js"></script>
<style>
.jumbotron1 {
    font-size: 18px;
}
</style>
<?php } ?>