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
$filter1 = str_replace("Mo's", "Mos", $filter1);
$filter2 = str_replace("Mo's", "Mos", $filter2);
$filter3 = str_replace("Mo's", "Mos", $filter3);

$search = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : '1';
$pageset = isset($_GET['pageset']) ? $_GET['pageset'] : '1';
$show_tag = isset($_GET['show_tag']) ? $_GET['show_tag'] : '0';
$show_problem = isset($_GET['show_problem']) ? $_GET['show_problem'] : '1';
if(!isset($_SESSION[$OJ_NAME.'_'.'user_id']))$show_problem = 1;// 비로그인 상태에서는 '맞은 문제 표시'를 기본값으로 고정
?>
<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <link rel="stylesheet" href="./problem/problem.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
    </style>
</head>
<body>
    <div class="big_h_box">
    <div class="h_box">
        <div class="flex justify-between items-center">
            <div class="space-x-4" style="font-size:18px;font-weight:normal;">
                
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
            
            
        </div>
        <div class="flex justify-between items-center">
        <div class="filter-box">
            <button onclick="toggleDropdown()">
                <h3>Filter ▼ </h3>
            </button>
            <div id="dropdown" class="dropdown-content">
                <div class="dropdown-scrollable">
                    <?php
          $sql = "SELECT tag_name FROM tag ORDER BY CASE WHEN tag_name REGEXP '^[A-Za-z]' THEN 1 ELSE 0 END, tag_name ASC";
          $result = pdo_query($sql);
          foreach ($result as $row) {
	    if($row["tag_name"]=='Mos'){echo '<a onclick="addFilter(\'Mos\')">Mo\'s</a>';}
            else {
	    echo '<a onclick="addFilter(\'' . str_replace('_', ' ', $row["tag_name"]) . '\')">' . str_replace('_', ' ', $row["tag_name"]) . '</a>';
            }
	  }
          ?>

                </div>
            </div>
            <div class="selected-filters" id="selectedFilters" onclick="removeFilter(event)"></div>
        </div>

	<center>	
	<?php 
	if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])){// 로그인 상태에서만 '맞은 문제 표시' 토글을 노출 ?>	
	<h3>맞은 문제 표시
	<div class="toggle-switch" style="float:right; margin-right: 15px; margin-left: 15px;" id="toggleSwitch" onclick="this.classList.toggle('active');change_problem()">
  		<div class="toggle-circle" style="float:right;"></div>
	</div></h3>
	<?php } ?>
	</center>

	
    </div>
</div>
</div>
    <input type="hidden" name="Sorting" id="Sorting" value="">
    <input type="hidden" name="Filter1" id="Filter1" value="">
    <input type="hidden" name="Filter2" id="Filter2" value="">
    <input type="hidden" name="Filter3" id="Filter3" value="">
    <input type="hidden" name="Page" id="Page" value="1">
    <input type="hidden" name="pageset" id="pageset" value="1">
    <input type="hidden" name="show_problem" id="show_problem" value="1">
    <input type="hidden" name="search" id="search" value="">

    <div id="problemList" style="justify-content: center;display:flex;flex-wrap:wrap;min-width:639px;margin-top:10px;"></div>

   <div class="search-box" style="position: absolute; bottom: 10px; left: 0; right: 0; margin-left: auto; margin-right: auto;">
   <input type="text" class="search-txt" id="searchInput" placeholder="제목+내용 검색" value="<?php echo $search; ?>" onKeypress="enter_check(event)">
   <a class="search-btn" href="javascript:void(0);" onclick="performSearch()" >
   <i class="fas fa-search"></i>
   </a>
   </div>

<script>
// 뒤로가기 등으로 돌아와도 선택한 옵션을 유지
var currentUrl = window.location.href;
var baseUrl = currentUrl.split('?')[0];
window.history.replaceState({}, document.title, baseUrl);

document.getElementById('search').value='<?php echo $search; ?>';
document.getElementById('Page').value='<?php echo $page; ?>';
document.getElementById('pageset').value='<?php echo $pageset; ?>';
document.getElementById('Sorting').value='<?php echo $sorting; ?>';
if(document.getElementById('Sorting').value=='전체') i=0;
else if(document.getElementById('Sorting').value=='인기') i=1;
else if(document.getElementById('Sorting').value=='최신') i=2;
f_filter1 = '<?php echo $filter1; ?>';
f_filter2 = '<?php echo $filter2; ?>';
f_filter3 = '<?php echo $filter3; ?>';
f_show_problem = '<?php echo $show_problem; ?>';
if(f_show_problem == '0'){
    var toggleSwitchElement = document.getElementById('toggleSwitch');
    toggleSwitchElement.classList.toggle('active');
    document.getElementById('show_problem').value=0;
}
// filter 값이 비어 있지 않을 때만 추가
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
        setActive(document.querySelectorAll('.sorting-criteria')[i]);
    };

    // 검색 실행
    function performSearch() {
        var searchValue = document.getElementById("searchInput").value;
	document.getElementById('search').value=searchValue;
	document.getElementById('pageset').value=1;
	document.getElementById('Page').value=1;
	applyFilter();
    }
    function enter_check(e){
	if(e.keyCode == 13){
	performSearch();
	}
    }

    // '맞은 문제 표시' 토글 변경
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

    var $sorting = document.getElementById('Sorting'); 
    var $page = document.getElementById('Page'); 
    j =0; // 초기 로드 시에는 page·pageset 을 건드리지 않기 위한 플래그
    // 정렬 기준 변경
    function setActive(element) {
        document.querySelectorAll('.sorting-criteria').forEach((el) => {
            el.classList.remove('active');
	    el.classList.remove('w-up');
        });
        element.classList.add('active');
	element.classList.add('w-up');
        var buttonText = element.textContent.trim().slice(2); 
        $sorting.value = buttonText; 
	if(j!=0){
        $page.value = 1;
        document.getElementById('pageset').value = 1;
	}
	j+=1;
        applyFilter();
    }

    // 드롭다운 표시
    function toggleDropdown() {
        var dropdown = document.getElementById("dropdown");
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
    }

    // 필터 추가
    function addFilter(filter) {
	// 이미 선택된 필터는 중복 추가하지 않음
	var filterInput_all = document.querySelectorAll('[id^="Filter"]');
        var asd = 0;
	filterInput_all.forEach(function(inputs) {
	if(inputs.value=="Mo's")inputs.value='Mos';
    	if (inputs.value === filter)asd = 1;
	});	
 	if(asd==1){
	toggleDropdown();
	return;
	}
        
        // 필터는 최대 3개까지
	var selectedFilters = document.getElementById("selectedFilters");
        if (selectedFilters.children.length >= 3) {
            alert("최대 3개의 필터만 선택할 수 있습니다.");
	    toggleDropdown();
            return;
        }
	filter = filter.replace("Mos", "Mo's");
        var filterElement = document.createElement("div");
        filterElement.className = "selected-filter";
        filterElement.innerHTML = filter;
        filterElement.onclick = function() {
            removeFilterElement(filterElement);
        };
        selectedFilters.appendChild(filterElement);
        // 선택된 필터는 드롭다운에서 숨김
        var dropdownItems = document.getElementById("dropdown").getElementsByTagName("a");
        for (var i = 0; i < dropdownItems.length; i++) {
            if (dropdownItems[i].innerHTML === filter) {
                dropdownItems[i].classList.add("hidden");
            }
        }
        // Update hidden input values
        updateHiddenInputValues();
        // 필터를 추가하면 드롭다운을 닫음
        toggleDropdown();
	
    }

    // 필터 제거
    function removeFilterElement(filterElement) {
        filterElement.remove();

        // 제거된 필터를 드롭다운에 다시 노출
        var dropdownItems = document.getElementById("dropdown").getElementsByTagName("a");
        for (var i = 0; i < dropdownItems.length; i++) {
            if (dropdownItems[i].innerHTML === filterElement.innerHTML) {
                dropdownItems[i].classList.remove("hidden");
            }
        }
        // Update hidden input values
        updateHiddenInputValues();
    }

    // AJAX 전송용 필터 값 변환
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

    function updateFilterResults() {
        $.post('problem_db.php', {
            action: 'getCountResult'
        }, function(data) {
            var countResult = JSON.parse(data);
        });
    }
    function applyFilter() {
	var sorting = $('#Sorting').val();
        var filter1 = $('#Filter1').val();
        var filter2 = $('#Filter2').val();
        var filter3 = $('#Filter3').val();
        var page = $('#Page').val();
        var pageset = $('#pageset').val();
	var show_problem = $('#show_problem').val();
	var search = $('#search').val();
        // 서버에서 조각 HTML을 받아 갱신
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
            show_problem: show_problem,
            search: search
    	},
    	success: function (data) {
        	$('#problemList').html(data);
        	updateFilterResults();
    		}
	});	
    }
window.onbeforeunload = function() {// 페이지를 떠날 때 현재 주소를 저장
    changeaddress();
};
function changeaddress(){
	var newURL = "problem_list.php?sorting=" + $('#Sorting').val() + "&filter1=" + $('#Filter1').val() + "&filter2=" + $('#Filter2').val() + "&filter3=" + $('#Filter3').val() + "&page=" + $('#Page').val() + "&pageset=" + $('#pageset').val() + "&show_problem=" + $('#show_problem').val() + "&search=" + $('#search').val();
        history.pushState(null, null, newURL);
}
</script>
