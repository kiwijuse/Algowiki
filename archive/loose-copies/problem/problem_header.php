<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/memcache.php');
require_once('./include/setlang.php');
require_once('./include/bbcode.php');

$sorting = isset($_GET['sorting']) ? $_GET['sorting'] : '전체';
$filter1 = isset($_GET['filter1']) ? $_GET['filter1'] : '';
$filter2 = isset($_GET['filter2']) ? $_GET['filter2'] : '';
$filter3 = isset($_GET['filter3']) ? $_GET['filter3'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : '1';
$pageset = isset($_GET['pageset']) ? $_GET['pageset'] : '1';
$show_tag = isset($_GET['show_tag']) ? $_GET['show_tag'] : '0';
$show_problem = isset($_GET['show_problem']) ? $_GET['show_problem'] : '1';
if(!isset($_SESSION[$OJ_NAME.'_'.'user_id']))$show_problem = 1;//로그인 안하면 무조건 맞은문제 표시 켜기
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem Sorting Interface</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./problem/problem.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>

    </style>
</head>

<body>

    <div class="container mx-auto px-4 py-8 rounded-lg shadow"
        style="background:white; width:98%;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none">
        <div class="flex justify-between items-center">
            <div class="space-x-4">
                <h3>
                    <button class="relative sorting-criteria" onclick="setActive(this)" style="margin:10px;">◆ 전체<span 
			    class="underline-indicator"></span></button>
                    <button class="relative sorting-criteria" onclick="setActive(this)" style="margin:10px;">◆ 인기<span
                            class="underline-indicator"></span></button>
                    <button class="relative sorting-criteria" onclick="setActive(this)" style="margin:10px;">◆ 최신<span
                            class="underline-indicator"></span></button>
                    <!--<button class="relative sorting-criteria" onclick="setActive(this)" style="margin:10px;">◆ 쉬움<span
                            class="underline-indicator"></span></button>
                    <button class="relative sorting-criteria" onclick="setActive(this)" style="margin:10px;">◆ 어려움<span
                            class="underline-indicator"></span></button>-->
            </div>
            </h3>
            
        </div>
        <div class="flex justify-between items-center">
        <div class="filter-box">
            <button onclick="toggleDropdown()">
                <h3>Filter ▼ </h3>
            </button>
            <div id="dropdown" class="dropdown-content">
                <div class="dropdown-scrollable">
                    <?php
          $sql = "select tag_name from tag order by tag_name asc";
          $result = pdo_query($sql);
          foreach ($result as $row) {
            echo '<a onclick="addFilter(\'' . str_replace('_', ' ', $row["tag_name"]) . '\')">' . str_replace('_', ' ', $row["tag_name"]) . '</a>';
          }
          ?>

                </div>
            </div>
            <div class="selected-filters" id="selectedFilters" onclick="removeFilter(event)"></div>
        </div>

	<div><center>
	<h3>태그 표시
	<div class="toggle-switch2" style="float:right; margin-right: 15px; margin-left: 15px;" id="toggleSwitch2" onclick="this.classList.toggle('active');change_tag()">
  		<div class="toggle-circle2" style="float:left;"></div>
	    </div>
	<?php 
	if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])){//로그인 했을때만 맞은문제표시가 뜸 ?>
	<div style="padding: 10px;"></div>
	맞은 문제 표시
	<div class="toggle-switch" style="float:right; margin-right: 15px; margin-left: 15px;" id="toggleSwitch" onclick="this.classList.toggle('active');change_problem()">
  		<div class="toggle-circle" style="float:right;"></div>
	</div>
	<?php } ?>
	</center></h3>

	</div>
    </div>
</div>

    <input type="hidden" name="Sorting" id="Sorting" value="">
    <input type="hidden" name="Filter1" id="Filter1" value="">
    <input type="hidden" name="Filter2" id="Filter2" value="">
    <input type="hidden" name="Filter3" id="Filter3" value="">
    <input type="hidden" name="Page" id="Page" value="1">
    <input type="hidden" name="pageset" id="pageset" value="1">
    <input type="hidden" name="show_tag" id="show_tag" value="0">
    <input type="hidden" name="show_problem" id="show_problem" value="1">
    <input type="hidden" name="search" id="search" value="">

    <div id="problemList"></div>

   <div class="search-box" style="position: absolute; bottom: 20px; left: 0; right: 0; margin-left: auto; margin-right: auto;">
   <input type="text" class="search-txt" id="searchInput" placeholder="제목+내용 검색" value="<?php echo $search; ?>" onKeypress="enter_check(event)">
   <a class="search-btn" href="javascript:void(0);" onclick="performSearch()" >
   <i class="fas fa-search"></i>
   </a>
   </div>

<script>
//뒤로가기 등 페이지 이동시에도 값 기억
document.getElementById('search').value='<?php echo $search; ?>';
document.getElementById('Page').value='<?php echo $page; ?>';
document.getElementById('pageset').value='<?php echo $pageset; ?>';
document.getElementById('Sorting').value='<?php echo $sorting; ?>';
if(document.getElementById('Sorting').value=='전체') i=0;
else if(document.getElementById('Sorting').value=='인기') i=1;
else if(document.getElementById('Sorting').value=='최신') i=2;
f_show_tag = '<?php echo $show_tag; ?>';
f_filter1 = '<?php echo $filter1; ?>';
f_filter2 = '<?php echo $filter2; ?>';
f_filter3 = '<?php echo $filter3; ?>';
if(f_show_tag == '1'){
    var toggleSwitchElement2 = document.getElementById('toggleSwitch2');
    toggleSwitchElement2.classList.toggle('active');
    document.getElementById('show_tag').value=1;
}
f_show_problem = '<?php echo $show_problem; ?>';
if(f_show_problem == '0'){
    var toggleSwitchElement = document.getElementById('toggleSwitch');
    toggleSwitchElement.classList.toggle('active');
    document.getElementById('show_problem').value=0;
}
//filter 변수가 falsy 한 값(null, undefined, 0, 빈 문자열 등) 이 아니라면 필터 추가
l=0;
if(!!f_filter1){
addFilter(f_filter1);
toggleDropdown();
	if(!!f_filter2){
	addFilter(f_filter2);
	toggleDropdown();
		if(!!f_filter3){
		addFilter(f_filter3);
		toggleDropdown();
		}

	}

}
l=1;
    window.onload = function() {
        // 초기로드 시 setActive 함수 호출
        setActive(document.querySelectorAll('.sorting-criteria')[i]);
    };

    //검색시
    function performSearch() {
        // 입력된 값 가져오기
        var searchValue = document.getElementById("searchInput").value;
	document.getElementById('search').value=searchValue;
	applyFilter();
    }
    function enter_check(e){
	if(e.keyCode == 13){
	performSearch();
	}
    }

    //태그 표시 바꿀시
    function change_tag(){
	if(document.getElementById('show_tag').value==0){
		document.getElementById('show_tag').value=1;	
	}
	else{
		document.getElementById('show_tag').value=0;		
	}
	applyFilter();
    }

    //맞은 문제 표시 바꿀시
    function change_problem(){
	if(document.getElementById('show_problem').value==0){
		document.getElementById('show_problem').value=1;
	}
	else{
		document.getElementById('show_problem').value=0;
	}
	document.getElementById('pageset').value=1;
	document.getElementById('Page').value=1;
	applyFilter();	
    }

    var $sorting = document.getElementById('Sorting'); // hidden input 요소를 가져옴
    var $page = document.getElementById('Page'); // hidden input 요소를 가져옴
    j =0; //초기 로드시에 $page.value 와 pageset 값을 바꾸지 않기위해 초기값 설정을 위해 j변수 선언
    //위 정렬기준 바꾸는것
    function setActive(element) {
        document.querySelectorAll('.sorting-criteria').forEach((el) => {
            el.classList.remove('active');
        });
        element.classList.add('active');
        var buttonText = element.textContent.trim().slice(2); // 버튼 내부의 텍스트를 가져옴
        $sorting.value = buttonText; // $sorting의 value 값을 버튼 텍스트로 설정
	if(j!=0){
        $page.value = 1;
        document.getElementById('pageset').value = 1;
	}
	j+=1;
        applyFilter();
    }

    //드랍다운 보여주는것
    function toggleDropdown() {
        var dropdown = document.getElementById("dropdown");
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
    }

    //필터 추가
    function addFilter(filter) {
	//필터에 선택한 값이 있을경우 또 선택해도 아무일도 발생안함
	var filterInput_all = document.querySelectorAll('[id^="Filter"]');
        var asd = 0;
	filterInput_all.forEach(function(inputs) {
    	if (inputs.value === filter)asd = 1;
	});	
 	if(asd==1){
	toggleDropdown();
	return;
	}

        
        // 최대 3개의 필터만 허용
	var selectedFilters = document.getElementById("selectedFilters");
        if (selectedFilters.children.length >= 3) {
            alert("최대 3개의 필터만 선택할 수 있습니다.");
	    toggleDropdown();
            return;
        }
	
        var filterElement = document.createElement("div");
        filterElement.className = "selected-filter";
        filterElement.innerHTML = filter;
        filterElement.onclick = function() {
            removeFilterElement(filterElement);
        };
        selectedFilters.appendChild(filterElement);
        // 선택된 필터를 필터 박스에서 숨김
        var dropdownItems = document.getElementById("dropdown").getElementsByTagName("a");
        for (var i = 0; i < dropdownItems.length; i++) {
            if (dropdownItems[i].innerHTML === filter) {
                dropdownItems[i].classList.add("hidden");
            }
        }
        // Update hidden input values
        updateHiddenInputValues();
        // 필터를 클릭하여 추가하면 드랍다운을 닫음
        toggleDropdown();
	
    }

    //필터 제거
    function removeFilterElement(filterElement) {
        filterElement.remove();

        // 삭제된 필터를 필터 박스에서 다시 보임
        var dropdownItems = document.getElementById("dropdown").getElementsByTagName("a");
        for (var i = 0; i < dropdownItems.length; i++) {
            if (dropdownItems[i].innerHTML === filterElement.innerHTML) {
                dropdownItems[i].classList.remove("hidden");
            }
        }
        // Update hidden input values
        updateHiddenInputValues();
    }

    //ajax 전송할 filter 정보들 변환
    function updateHiddenInputValues() {
        var selectedFilters = document.getElementsByClassName("selected-filter");
        var filterInputs = document.querySelectorAll('[id^="Filter"]');

        // Reset all filter values
        filterInputs.forEach(function(input) {
            input.value = "";
        });

        // Update filter values based on selected filters
        for (var i = 0; i < selectedFilters.length; i++) {
            var filterValue = selectedFilters[i].innerHTML;
            filterInputs[i].value = filterValue;
        }
	if(l!=0){
        document.getElementById('Page').value = 1;
        document.getElementById('pageset').value = 1;
	applyFilter();
	}
	
        
    }

    document.addEventListener("click", function(event) {
        var dropdown = document.getElementById("dropdown");
        if (!event.target.closest(".filter-box") && !event.target.closest(".selected-filter")) {
            dropdown.style.display = "none";
        }
    });

    // 필터 적용 코드
    function updateFilterResults() {
        $.post('problem_db.php', {
            action: 'getCountResult'
        }, function(data) {
            // 서버에서 가져온 값으로 페이지 내의 메뉴 등 업데이트
            var countResult = JSON.parse(data);
        });
	/*$.ajax({
    	type: 'POST',
    	url: 'problem_db.php',
    	data: {
        action: 'getCountResult'
    	},
   	async: false,
    	success: function(data) {
        // 서버에서 가져온 값으로 페이지 내의 메뉴 등 업데이트
        var countResult = JSON.parse(data);
    	}
	});*/

    }

    // applyFilter 함수 정의
    function applyFilter() {

	var sorting = $('#Sorting').val();
        var filter1 = $('#Filter1').val();
        var filter2 = $('#Filter2').val();
        var filter3 = $('#Filter3').val();
        var page = $('#Page').val();
        var pageset = $('#pageset').val();
	var show_tag = $('#show_tag').val();
	var show_problem = $('#show_problem').val();
	var search = $('#search').val();
        // jQuery를 사용하여 서버에 요청 및 결과 갱신
        /*$.post('./problem/problem_db.php', {
            sorting: sorting,
            filter1: filter1,
            filter2: filter2,
            filter3: filter3,
            page: page,
            pageset: pageset,
	    show_tag: show_tag,
	    show_problem: show_problem,
	      search : search
        }, function(data) {
            $('#problemList').html(data);
            updateFilterResults();
        });*/
	$.ajax({
    	type: 'POST',
    	url: './problem/problem_db.php',
    	data: {
            sorting: sorting,
            filter1: filter1,
            filter2: filter2,
            filter3: filter3,
            page: page,
            pageset: pageset,
            show_tag: show_tag,
            show_problem: show_problem,
            search: search
    	},
   	//async: false,
    	success: function (data) {
        	$('#problemList').html(data);
        	updateFilterResults();
    		}
	});

	var newURL = "problem_list.php?sorting=" + sorting + "&filter1=" + filter1 + "&filter2=" + filter2 + "&filter3=" + filter3 + "&search=" + search + "&page=" + page + "&pageset=" + pageset + "&show_tag=" + show_tag + "&show_problem=" + show_problem ;
        history.pushState(null, null, newURL);
    }


    </script>
</body>

</html>