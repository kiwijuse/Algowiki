
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

if($count_search == 0){
	$sql = "select count(*) from solution";	
	}else if(!!$problem_id){
	$sql = "select count(*) from solution where problem_id = '$problem_id' and user_id = '$user_id' and language = '$compile_language' and result = '$grading_result'";
	}else if(!!$user_id){
	$sql = "select count(*) from solution where user_id = '$user_id' and language = '$compile_language' and result = '$grading_result'";
	}else if(!!$compile_language || $compile_language == 0){
	$sql = "select count(*) from solution where language = '$compile_language' and result = '$grading_result'";
	}else{
	$sql = "select count(*) from solution where result = '$grading_result'";
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



$paged = ($page - 1) * 50;
$count_result = pdo_query($sql);
$problem_count = $count_result[0][0];
$max_problems_per_page = 50;
$max_pages = 5;
$total_pages = min($max_pages, ceil($problem_count / $max_problems_per_page));
$start_page = max(1, $total_pages - $max_pages + 1);

echo '<div style="width:350px; border-radius:20px; height:40px;display: flex; flex-direction: column;border: 1px solid #E2E3E4;">
    <div class="pagination-container" style="width:320px;margin-left: auto; margin-right: auto;margin-top:auto; margin-bottom:auto;">
    <div id="doubleLeft" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&laquo;</center></div>
    <div id="singleLeft" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&lsaquo;</center></div>';

for ($i = $start_page; $i <= $total_pages; $i++) {
  $selected_class = ($i == 1) ? 'selected' : ''; // 첫 번째 페이지를 선택 상태로 지정
  echo '<div id="page' . $i . '" class="pagination-item ' . $selected_class . '" >' . $i . '</div>';
}
echo '<div id="singleRight" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&rsaquo;</center></div>
    	<div id="doubleRight" class="arrow" style="display: block; margin-bottom: 8px; font-size:2.5rem;"><center>&raquo;</center></div></div></div></div>';

?>

<script>

currentPage = <?php echo $page; ?>;
totalPages = <?php echo ceil($count_result[0][0] / 50); ?>;
maxPages = <?php echo $max_pages; ?>;
pageSet = <?php echo $pageset; ?>;

document.getElementById('singleLeft').addEventListener('click', () => handleArrowClick('left'));
document.getElementById('singleRight').addEventListener('click', () => handleArrowClick('right'));
document.getElementById('doubleLeft').addEventListener('click', () => handleArrowClick('doubleLeft'));
document.getElementById('doubleRight').addEventListener('click', () => handleArrowClick('doubleRight'));

for (let i = 1; i <= 5; i++) {
    	document.getElementById(`page${i}`).addEventListener('click', function() {
        currentPage = parseInt(this.textContent, 10);
        document.getElementById('page').value = currentPage;
	updatePagination();
        status_ajax();
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

    document.getElementById('page').value = currentPage;
    document.getElementById('pageset').value = pageSet;
    status_ajax();
    updatePagination();
}
</script>