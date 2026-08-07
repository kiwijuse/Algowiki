<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
$view_title = "Welcome To Online Judge";

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$problem_id = isset($_GET['problem_id']) ? $_GET['problem_id'] : '';
$grading_result = isset($_GET['grading_result']) ? $_GET['grading_result'] : '';
$compile_language = isset($_GET['compile_language']) ? $_GET['compile_language'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : '1';
$pageset = isset($_GET['pageset']) ? $_GET['pageset'] : '1';
$count_search = 0;
if(!!$user_id) $count_search += 1;
if(!!$problem_id) $count_search += 1;
if(!!$grading_result) $count_search += 1;
if(!!$complile_language) $count_search += 1;
?>
<?php $show_title="채점 현황 - AlgoWiki"; ?>
<?php include("template/$OJ_TEMPLATE/header.php");?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <link rel="stylesheet" href="./problem/status.css">
    <style>
    </style>
</head>
<body>
<div class="flex-box" style="margin-bottom:10px; white-space: nowrap;display:flex;align-items: center;-webkit-box-align: center;font-size: .78571429rem;width:100%;">
      <label style="font-size: 1.2em; margin-right: 1px;line-height:34px; ">문제ID：</label>
      <div class="field"><input name="problem_id" id="problem_id_input" class="user_id_input" type="text" value="<?php echo $problem_id;?>"></div>
        <label class="user_id">사용자ID：</label>
        <div class="field"><input name="user_id" id="user_id_input" class="user_id_input" type="text" value="<?php echo $user_id;?>"></div>

        <label class="language">제출언어：</label>
        <select class="form-control" size="1" name="language" style="width: 110px;font-size:1em;">
          <option value="">모든 언어</option>
          <option value="0">
            C
            </option><option value="1">
            C++
            </option><option value="2">
            Pascal
            </option><option value="3">
            Java
            </option><option value="6">
            Python
            </option>       
        </select>
        <label class="grading_result">채점결과：</label>
        <select class="form-control" size="1" name="jresult" style="width: 130px;font-size:1em;">
          <option value="" selected="">모든 결과</option><option value="4">정답</option><option value="5">출력형식 다름</option><option value="6">틀림</option><option value="7">시간초과</option><option value="8">메모리 초과</option><option value="9">출력 초과</option><option value="10">런타임 에러</option><option value="11">컴파일 에러</option></select>
   <button class="ui labeled icon mini green button button_s" type="submit" onclick="search()">
        <i class="search icon"></i>검색
   </button>
    </div>

	<input type="hidden" id="compile_language" value="<?php echo $compile_language;?>">
	<input type="hidden" id="grading_result" value="<?php echo $grading_result;?>">
	<input type="hidden" id="problem_id" value="<?php echo $problem_id;?>">
	<input type="hidden" id="user_id" value="<?php echo $user_id;?>">
	<input type="hidden" id="page" value="<?php echo $page;?>">
	<input type="hidden" id="pageset" value="<?php echo $pageset;?>">


	<div id="status_list"></div>
	<div id="page_list" style="display:flex;justify-content:center;" ></div>
	<div id="status_lists"></div>
	<?php
	
	if($count_search == 0){
	$sql = "select solution_id,result from solution order by solution_id desc limit 1";	
	}else if(!!$problem_id){
	$sql = "select solution_id,result from solution where problem_id = '$problem_id' and user_id = '$user_id' and language = '$compile_language' and result = '$grading_result' order by solution_id desc limit 1";
	}else if(!!$user_id){
	$sql = "select solution_id,result from solution where user_id = '$user_id' and language = '$compile_language' and result = '$grading_result' order by solution_id desc limit 1";
	}else if(!!$compile_language){
	$sql = "select solution_id,result from solution where language = '$compile_language' and result = '$grading_result' order by solution_id desc limit 1";
	}else{
	$sql = "select solution_id,result from solution where result = '$grading_result' order by solution_id desc limit 1";
	}
	
	if(!$user_id){
	$sql = str_replace("and user_id = '$user_id'", "", $sql);
	}
	if(!$compile_language){
	$sql = str_replace("and language = '$compile_language'", "", $sql);
	}
	if(!$grading_result){
	$sql = str_replace("and result = '$grading_result'", "", $sql);
	}
	$check_result=pdo_query($sql);
	$check_id = $check_result[0][0];
	$check_result = $check_result[0][1];
	?>

</body>
</hyml>
<?php
include("template/$OJ_TEMPLATE/footer.php");
?>
<script>

solution_id_check = '<?php echo $check_id; ?>';
solution_result_check = '<?php echo $check_result; ?>';
document.getElementById('problem_id').value = '<?php echo $problem_id;?>';
document.getElementById('user_id').value = '<?php echo $user_id;?>';
document.getElementById('compile_language').value = '<?php echo $compile_language;?>';
document.getElementById('grading_result').value = '<?php echo $grading_result;?>';
document.getElementById('page').value = '<?php echo $page;?>';
document.getElementById('pageset').value = '<?php echo $pageset;?>';
document.querySelector('select[name="language"]').value = '<?php echo $compile_language;?>';
document.querySelector('select[name="jresult"]').value = '<?php echo $grading_result;?>';
function search(){
	var jresultSelect = document.querySelector('select[name="jresult"]');
	var languageSelect = document.querySelector('select[name="language"]');
    	var jresultValue = jresultSelect.value;
	var languageValue = languageSelect.value;
	document.getElementById('problem_id').value = document.getElementById('problem_id_input').value;
	document.getElementById('user_id').value = document.getElementById('user_id_input').value;
	document.getElementById('grading_result').value = jresultValue;
	document.getElementById('compile_language').value = languageValue;
	document.getElementById('page').value = 1;
	document.getElementById('pageset').value = 1;
	status_ajax();
	page_ajax();
}
function status_ajax() {
	var problem_id = $('#problem_id').val();
	var user_id = $('#user_id').val();
	var compile_language = $('#compile_language').val();
	var grading_result = $('#grading_result').val();
        var page = $('#page').val();
	var pageset = $('#pageset').val();
        // 서버에서 조각 HTML을 받아 갱신
	$.ajax({
    	type: 'POST',
    	url: './problem/status_db.php',
    	data: {
	    problem_id: problem_id,
	    user_id: user_id,
	    compile_language: compile_language,
	    grading_result: grading_result,
	    page: page,
	    pageset: pageset,
    	},
    	success: function (data) {
        	$('#status_list').html(data);
    		}
	});
    change_address();
}
function page_ajax() {
	var problem_id = $('#problem_id').val();
	var user_id = $('#user_id').val();
	var compile_language = $('#compile_language').val();
	var grading_result = $('#grading_result').val();
        var page = $('#page').val();
	var pageset = $('#pageset').val();
        // 서버에서 조각 HTML을 받아 갱신
	$.ajax({
    	type: 'POST',
    	url: './problem/status_page_db.php',
    	data: {
	    problem_id: problem_id,
	    user_id: user_id,
	    compile_language: compile_language,
	    grading_result: grading_result,
	    page: page,
	    pageset: pageset,
    	},
    	success: function (data) {
        	$('#page_list').html(data);
    		}
	});
    }

function change_check_ajax() {   
    var problem_id = $('#problem_id').val();
    var user_id = $('#user_id').val();
    var compile_language = $('#compile_language').val();
    var grading_result = $('#grading_result').val();
    var page = $('#page').val();
    // 서버에서 조각 HTML을 받아 갱신
    $.ajax({
        type: 'POST',
        url: './problem/status_change_check.php',
        // 캐시 비활성화
        cache: false,
        data: {
            problem_id: problem_id,
            user_id: user_id,
            compile_language: compile_language,
            grading_result: grading_result,
	    page: page,
        },
        success: function (data) {
            $('#status_lists').html(data);
        }
    }); 
}
function change_address(){
    var newURL = "status.php?";
    if ($('#problem_id').val()) {
    	newURL += "problem_id=" + $('#problem_id').val() + "&";
    }
    if ($('#user_id').val()) {
    	    newURL += "user_id=" + $('#user_id').val() + "&";
    }
    if ($('#compile_language').val()) {
    	newURL += "compile_language=" + $('#compile_language').val() + "&";
    }
    if ($('#grading_result').val()) {
    	newURL += "grading_result=" + $('#grading_result').val() + "&";
    }
    if ($('#page').val()) {
    	newURL += "page=" + $('#page').val() + "&";
    }
    if ($('#pageset').val()) {
    	newURL += "pageset=" + $('#pageset').val() + "&";
    }
    newURL = newURL.slice(0, -1);   
    history.pushState(null, null, newURL);
}
$('.user_id_input').keypress(function(event) {
        if (event.which == 13) {
            search();
        }
    });
status_ajax();
page_ajax();
setInterval(change_check_ajax, 150);
</script>

