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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem Sorting Interface</title>
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

    <div class="flex-container" style = "margin-left:1px;width:1048px;height:100px;display: flex; border-radius: 10px;border: 1px solid #E2E3E4;margin-bottom: 30px;position:relative;font-size:20px;line-height:1.4;font-weight:700;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;">
        <div class="flex justify-between items-center" style="position:absolute; top:36%; left:2%;">
                            
		 <div class="container" style="display:flex;flex-direction:row;width:100%;">
		    <a class="underline-on-hover" style ="color:<?php echo $nick_color;?>;" href="userinfo.php?user=<?php echo $user;?>"><?php echo $user; ?></a><b style="margin-left:5px;"></b>의   
		   <div id="selected-item-space" style="text-align:center;cursor:pointer;margin-left:7px;" onclick="toggleDropdowns()"><?php echo $category?> </div>
		   <div style="text-align:center;cursor:pointer;margin-left:3px;" onclick="toggleDropdowns()">▼</div>             
		   <div id="classification-dropdown" class="absolute bg-white border rounded z-10" style="width:100px;text-align:center;display:none;position:absolute;right:10%;top:100%;font-size:1rem;">
                <a href="#" class="block text-gray-700 hover:bg-gray-100 sorting-criteria" onclick="toggleDropdown('채점 현황');setActive(this)" style="padding:5px;">채점 현황</>
    		<a href="#" class="block text-gray-700 hover:bg-gray-100 sorting-criteria" onclick="toggleDropdown('맞은 문제');setActive(this)" style="padding:5px;">맞은 문제</a>
    		<a href="#" class="block text-gray-700 hover:bg-gray-100 sorting-criteria" onclick="toggleDropdown('만든 문제');setActive(this)" style="padding:5px;">만든 문제</a>

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
    <div class="row">
    <div id="problemList"></div>
</div>
<script>
//뒤로가기 등 페이지 이동시에도 옵션 값 기억
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
        // 초기로드 시 setActive 함수 호출
        setActive(document.querySelectorAll('.sorting-criteria')[i]);
    };    	
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
	
    var $category = document.getElementById('category'); // hidden input 요소를 가져옴
    var $page = document.getElementById('Page'); // hidden input 요소를 가져옴
    j =0; //초기 로드시에 $page.value 와 pageset 값을 바꾸지 않기위해 초기값 설정을 위해 j변수 선언
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
        var buttonText = element.textContent.trim(); // 버튼 내부의 텍스트를 가져옴
	var show_tag = document.getElementById('show_tag');
        $category.value = buttonText; // $category의 value 값을 버튼 텍스트로 설정
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
    // applyFilter 함수 정의
    function applyFilter() {
	var category = $('#category').val();
        var page = $('#Page').val();
        var pageset = $('#pageset').val();
	var show_tag = $('#show_tag').val();
	var user = <?php echo json_encode($user); ?>;
	var view_user = <?php echo json_encode($view_user); ?>;
	var is_admin = <?php echo json_encode($is_admin); ?>;
        // jQuery를 사용하여 서버에 요청 및 결과 갱신
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
window.onbeforeunload = function() {//페이지를 떠날때 주소 저장
    changeaddress();
};
function changeaddress(){
	var newURL = "userinfo.php?user=<?php echo $user;?>&tab=problem&category=" + $('#category').val() + "&page=" + $('#Page').val() + "&pageset=" + $('#pageset').val() + "&show_tag=" + $('#show_tag').val();
        history.pushState(null, null, newURL);
}

    </script>
</body>

</html>