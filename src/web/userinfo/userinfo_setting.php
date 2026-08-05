<?php $show_title="$OJ_NAME"; ?>
<?php
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
if($user!=$_SESSION[$OJ_NAME.'_'.'user_id'] ){//운영자이거나, 본인 아니면 못봄
if(!isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
echo "<script>location.href='userinfo.php?user=".$user."'</script>";
}
}
$user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];
?>
<?php include("template/$OJ_TEMPLATE/header.php");?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <style>

        h1 {
            padding: 50px 0;
            font-weight: 400;
            text-align: center;}

        p {
            margin: 0 0 20px;
            line-height: 1.5;}

        .main {
            min-width: 320px;
            max-width: 1200px;
            padding: 15px;
            background: #ffffff;}

        section {
            display: none;
            padding: 20px 0 0;
            border-top: 1px solid #ddd;}

        /*라디오버튼 숨김*/
          input {
              display: none;}

        label {
            display: inline-block;
            margin: 0 0 -1px;
            padding: 15px 25px;
            font-weight: 600;
            text-align: center;
            color: #bbb;
            border: 1px solid transparent;
	}

        label:hover {
            color: #2e9cdf;
            cursor: pointer;}

        /*input 클릭시, label 스타일*/
        input:checked + label {
              color: #555;
              border: 1px solid #ddd;
              border-top: 2px solid #2e9cdf;
              border-bottom: 1px solid #ffffff;}

        #tab1:checked ~ #content1,
        #tab2:checked ~ #content2,
        #tab3:checked ~ #content3,
        #tab4:checked ~ #content4 {
            display: block;}

.setting_big{
    border-bottom:1px solid #ddd;
    font-size:22px;
    font-weight:600;

}
.setting_small{
    padding:5px 15px;
    font-size:16px;
    font-weight:500;
    cursor:pointer;
    margin-left:15px;
}
.setting_small:hover{
    background-color: #F3F4F6;

}
.setting_small.selected{
    background-color: #DEDEDE;
}
.save_button{
    float:right;    
    font-size:16px;
    font-weight:600;
    padding:10px 15px;
    border-radius:10px;
    border: 1px solid #ccc;
    cursor:pointer;
}
.save_button:hover{
    background-color: #F3F4F6;

}
.m_check_button{
    float:right;    
    font-size:14px;
    font-weight:600;
    padding:5px 10px;
    border-radius:4px;
    border: 1px solid #ccc;
    cursor:pointer;
    margin-left:10px;
}
.m_check_button:hover{
    background-color: #F3F4F6;
}
.setting-title{
    font-size:26px;
    font-weight:600;
    margin-top:5px;
    margin-bottom:50px;
    
}
.setting-sub{
    font-size:16px;
    width:25%;
    border-right:1px solid #ddd;
    line-height:34px;
}
#save_message {
	position:absolute;
	top:270px;
	left:50%;
        text-align: center;
        font-size: 24px;
        display: none;
	transform: translateX(-50%);
	padding:10px;
	border-radius:10px;
	color:white;
	background-color: #83F780;
    }
.form-control{
	width:300px;
}
@media (max-width: 768px) and (pointer: coarse) {
	.form-control{
	width:200px;
	}
	.setting-sub{
	width:35%;
	}
}
.help-discord{
margin-left:5px;
color:white;
background-color:#DEDEDE;
border-radius:50%;
border:1px solid #DEDEDE;
padding :0px 6.7px;
height:22px;
line-height:20px;
cursor:pointer;
}
.help-discord:hover{
background-color:gray;
border:1px solid gray;
}
    </style>
    <title>Document</title>
</head>
<div class="main" >
    <input id="tab1" type="radio" name="tabs">
    <label for="tab1" onclick="changeTab('profile')">프로필</label>

    <input id="tab2" type="radio" name="tabs">
    <label for="tab2" onclick="changeTab('problem')">기록</label>
    <?php 
    if($user==$_SESSION[$OJ_NAME.'_'.'user_id'] || isset($_SESSION[$OJ_NAME.'_'.'administrator'])){//운영자이거나, 본인일 경우에만 보임
    ?>
    <input id="tab3" type="radio" name="tabs">
    <label for="tab3" onclick="changeTab('quest')">퀘스트</label>

    <input id="tab4" type="radio" name="tabs">
    <label for="tab4" onclick="changeTab('shop')">상점</label>

    <input id="tab5" type="radio" name="tabs">
    <label for="tab5" onclick="changeTab('inventory')">인벤토리</label>

    <input id="tab6" type="radio" name="tabs" checked>
    <label for="tab6" onclick="changeTab('setting')">설정</label>
   <?php } ?>

    
    <div style="margin:50px;width:100%;"></div>
    <div style="display:flex;width:100%;margin-top:20px;">

	<div style="width:200px;">
	    <div class="setting_big" style="border-top:1px solid #ddd;">
		<div style="padding:15px 5px;">내 정보</div>
		<div class="setting_small selected" onclick="ajax(1);toggleSelection(1)">비밀 번호 변경</div>
		<div class="setting_small" onclick="ajax(2);toggleSelection(2)">이메일 주소 변경</div>
		<div class="setting_small" onclick="ajax(3);toggleSelection(3)">한줄 소개 변경</div>
		<div class="setting_small" onclick="ajax(4);toggleSelection(4)">연결 정보 관리</div>		
		<div class="setting_small" onclick="ajax(5);toggleSelection(5)">회원 탈퇴</div>
		<div style="height:10px;width:1px;"></div>
	    </div>
	    <div class="setting_big">
		<div style="padding:15px 5px;">설정</div>
		<div class="setting_small" onclick="ajax(6);toggleSelection(6)">표시</div>
		<div class="setting_small" onclick="ajax(7);toggleSelection(7)">공개</div>
		<div style="height:10px;width:1px;"></div>
	    </div>
	</div>
	
	<div style="margin-left:40px;width:80%;position:relative;" id="ajax"></div>	
	

    </div>
    <div id="test"></div>

</div>
<?php include("template/$OJ_TEMPLATE/footer.php");?>
<script>
window.onload = function() {
    // 프로필 탭에 대한 라디오 버튼을 찾아서 checked 속성을 추가
    document.getElementById('tab6').checked = true;
};

function changeTab(tabId) {
    window.location.href = "userinfo.php?user=<?php echo $user;?>&tab=" + tabId;
}
function toggleSelection(index) {
    var elements = document.querySelectorAll('.setting_small');
    elements.forEach(function(element) {
        element.classList.remove('selected');
    });
    elements[index - 1].classList.add('selected');
}
function ajax(num) {
	var user = '<?php echo $user;?>';
	$.ajax({
    	type: 'POST',
    	url: './userinfo/setting_ajax.php',
    	data: {
	num: num,
	user: user,
    	},
    	success: function (data) {
        	$('#ajax').html(data);
    		}
	});	
    }

function display(){	
	var user = '<?php echo $user;?>';
	var difficulty = document.getElementById('difficulty').value;	
	var tag = document.getElementById('tag').value;
	$.ajax({
    	type: 'POST',
    	url: './userinfo/php_ajax.php',
    	data: {
	difficulty: difficulty,
	tag: tag,
	user: user,	
    	},
    	success: function (data) {			        	
    		}
	});
}
function link(){	
	var user = '<?php echo $user;?>';
	var git = document.getElementById('new_git').value;
	var discord = document.getElementById('new_discord').value;
	var blog = document.getElementById('new_blog').value;
	$.ajax({
    	type: 'POST',
    	url: './userinfo/php_ajax.php',
    	data: {
	git: git,
	blog: blog,
	discord: discord,
	user: user,
    	},
    	success: function (data) {			        
    		}
	});
}

function comment(){	
	var user = '<?php echo $user;?>';
	var comment = document.getElementById('new_comment').value;	
	$.ajax({
    	type: 'POST',
    	url: './userinfo/php_ajax.php',
    	data: {
	comment: comment,
	user: user,	
    	},
    	success: function (data) {			        	
    		}
	});
}

function opens(){	
	var user = '<?php echo $user;?>';
	var show_source = document.getElementById('show_source').value;
	var show_discord = document.getElementById('show_discord').value;
	var show_blog = document.getElementById('show_blog').value;
	var show_git = document.getElementById('show_git').value;
	$.ajax({
    	type: 'POST',
    	url: './userinfo/php_ajax.php',
    	data: {
	show_git: show_git,
	show_blog: show_blog,
	show_discord: show_discord,
	show_source: show_source,
	user: user,
    	},
    	success: function (data) {			        
    		}
	});
}

var saveInProgress = false;
function save(){
    if (saveInProgress) {
        return;
    }
    saveInProgress = true;
    var saveButton = document.querySelector('.save_button');
    saveButton.innerHTML = '<span style="color: #83F780;">&#10004;</span>';
    saveButton.style.padding = '10px 24.5px';
    setTimeout(function() {
        saveButton.textContent = '저장';
	saveButton.style.padding = '';
	saveInProgress = false;
    }, 2000);
}

ajax(1);

</script>

