<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/memcache.php');
require_once('./include/setlang.php');
require_once('./include/bbcode.php');
$category = isset($_GET['category']) ? $_GET['category'] : "채점 현황";
$page = isset($_GET['page']) ? $_GET['page'] : '1';
$pageset = isset($_GET['pageset']) ? $_GET['pageset'] : '1';
$show_tag = isset($_GET['show_tag']) ? $_GET['show_tag'] : '0';
$sql = "select nick_color from users where user_id = '$user'";
$result = pdo_query($sql);
$nick_color = $result[0][0];
$view_user=$_SESSION[$OJ_NAME.'_'.'user_id'];
if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
$is_admin ='True';
}
if($result[0][0]==NULL){
$nick_color = "#555555";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <link rel="stylesheet" href="./problem/problem.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
.underline-on-hover {
    text-decoration: none;
  }
  .underline-on-hover:hover {
    text-decoration: underline;
    text-underline-position: auto;
  }
a{
color:#144099;
}
a:hover{
color:#508FCD;
}
    </style>
</head>

<body>

    <div class="flex-container" style = "margin-left:1px;width:98.2%;min-width:625px;height:100px;display: flex; border-radius: 10px;border: 1px solid #E2E3E4;margin-bottom: 30px;position:relative;font-size:20px;line-height:1.4;font-weight:700;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;">
        <div class="flex justify-between items-center" style="position:absolute; top:36%; left:2%;">                            
	    <div class="container" style="display:flex;flex-direction:row;width:100%;">
		<a class="underline-on-hover" style ="color:<?php echo $nick_color;?>;" href="userinfo.php?user=<?php echo $user;?>"><?php echo $user; ?></a><b style="margin-left:5px;"></b>의
		<div class="c_box" style="display:flex;">   
		<div id="selected-item-space" style="text-align:center;cursor:pointer;margin-left:7px;" onclick="toggleDropdowns()"><?php echo $category?> </div>
		
		<div style="text-align:center;cursor:pointer;margin-left:3px;" onclick="toggleDropdowns()">▼</div>             
		<div id="classification-dropdown" class="absolute bg-white border rounded z-10" style="width:100px;text-align:center;display:none;position:absolute;right:10%;top:100%;font-size:1rem;">
                    <a class="block text-gray-700 hover:bg-gray-100 sorting-criteria" onclick="toggleDropdown('채점 현황');setActive(this)" style="padding:5px;">채점 현황</>
    		    <a class="block text-gray-700 hover:bg-gray-100 sorting-criteria" onclick="toggleDropdown('맞은 문제');setActive(this)" style="padding:5px;">맞은 문제</a>
    		    <a class="block text-gray-700 hover:bg-gray-100 sorting-criteria" onclick="toggleDropdown('만든 문제');setActive(this)" style="padding:5px;">만든 문제</a>
		</div>
		</div>
            </div>
        </div>

        <div id="show_tag" style="position:absolute;top:36%;right:3%;display:none;">
	태그 표시
	<div class="toggle-switch2" style="float:right; margin-right: 15px; margin-left: 15px;" id="toggleSwitch2" onclick="this.classList.toggle('active');change_tag()">
  		<div class="toggle-circle2" style="float:left;"></div>
	</div>
        </div>
    </div>
    <input type="hidden" name="category" id="category" value="">
    <input type="hidden" name="Page" id="Page" value="1">
    <input type="hidden" name="pageset" id="pageset" value="1">
    <input type="hidden" name="show_tag" id="show_tag" value="0">
    <div class="row" style="width:98%;margin-right:auto;margin-left:auto;">
    <div id="problemList" style="position:relative;"></div>
    </div>
<script>
// 뒤로가기 등으로 돌아와도 선택한 옵션을 유지
document.getElementById('Page').value='<?php echo $page; ?>';
document.getElementById('pageset').value='<?php echo $pageset; ?>';
document.getElementById('category').value='<?php echo $category; ?>';
document.getElementById('show_tag').value='<?php echo $show_tag; ?>';
if(document.getElementById('category').value=="채점 현황") i=0;
else if(document.getElementById('category').value=="맞은 문제") i=1;
else if(document.getElementById('category').value=="만든 문제") i=2;
else if(document.getElementById('category').value=="기여한 문제") i=3;
f_show_tag = '<?php echo $show_tag; ?>';
if(f_show_tag == '1'){
    var toggleSwitchElement2 = document.getElementById('toggleSwitch2');
    toggleSwitchElement2.classList.toggle('active');
    document.getElementById('show_tag').value=1;
}

    window.onload = function() {
        setActive(document.querySelectorAll('.sorting-criteria')[i]);
    };    	
    // '태그 표시' 토글 변경
    function change_tag(){
	if(document.getElementById('show_tag').value==0){
		document.getElementById('show_tag').value=1;	
	}
	else{
		document.getElementById('show_tag').value=0;		
	}
	applyFilter();
    }
	
    var $category = document.getElementById('category'); 
    var $page = document.getElementById('Page'); 
    j =0; // 초기 로드 시에는 page·pageset 을 건드리지 않기 위한 플래그


    function toggleDropdown(selectedItem) {
    var dropdown = document.getElementById('classification-dropdown');
    var links = dropdown.getElementsByTagName('a');
    // Close the dropdown
    dropdown.style.display = 'none';
    // Display the selected item in the designated space
    document.getElementById('selected-item-space').innerText = selectedItem;
    // Reset active state for all links
    for (var i = 0; i < links.length; i++) {
        links[i].classList.remove('active');
        }
    }

    function toggleDropdowns() {
    var dropdown = document.getElementById('classification-dropdown');
    // Toggle the display of the dropdown
    dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    }
    function setActive(element) {
        var buttonText = element.textContent.trim(); 
	var show_tag = document.getElementById('show_tag');
        $category.value = buttonText;
	if(buttonText=="맞은 문제" || buttonText == "만든 문제"){	
	show_tag.style.display= 'block';
	}
	else if(buttonText=="채점 현황"){
	show_tag.style.display= 'none';
	}
	if(j!=0){
        $page.value = 1;
        document.getElementById('pageset').value = 1;
	}
	j+=1;
	document.getElementById('classification-dropdown').style.display = 'none';
        applyFilter();
    } 
    function applyFilter() {
	var category = $('#category').val();
        var page = $('#Page').val();
        var pageset = $('#pageset').val();
	var show_tag = $('#show_tag').val();
	var user = <?php echo json_encode($user); ?>;
	var view_user = <?php echo json_encode($view_user); ?>;
	var is_admin = <?php echo json_encode($is_admin); ?>;
        // 서버에서 조각 HTML을 받아 갱신
	$.ajax({
    	type: 'POST',
    	url: './problem/userinfo_db.php',
    	data: {
            category: category,
            page: page,
            pageset: pageset,
            show_tag: show_tag,
	    user: user,
	    view_user: view_user,
	    is_admin: is_admin,
    	},
    	success: function (data) {
        	$('#problemList').html(data);
    		}
	});	
    }
window.onbeforeunload = function() {// 페이지를 떠날 때 현재 주소를 저장
    changeaddress();
};
function changeaddress(){
	var newURL = "userinfo.php?user=<?php echo $user;?>&tab=problem&category=" + $('#category').val() + "&page=" + $('#Page').val() + "&pageset=" + $('#pageset').val() + "&show_tag=" + $('#show_tag').val();
        history.pushState(null, null, newURL);
}

document.addEventListener("click", function(event) {
        var dropdown = document.getElementById("classification-dropdown");
        if (!event.target.closest(".c_box")) {
            dropdown.style.display = "none";
        }
    });
window.onload = function() {
    // 현재 탭의 라디오 버튼을 선택 상태로 지정
    document.getElementById('tab2').checked = true;
    applyFilter();
};

    </script>
</body>

</html>