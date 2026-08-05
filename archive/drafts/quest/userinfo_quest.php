<?php $show_title="$OJ_NAME"; ?>
<?php
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
if($user!=$_SESSION[$OJ_NAME.'_'.'user_id'] ){//운영자이거나 본인 아니면 못봄
if(!isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
echo "<script>location.href='userinfo.php?user=".$user."'</script>";
}
}

?>
<?php include("template/$OJ_TEMPLATE/header.php");?>
<head>
    <meta charset="UTF-8">
    <style>
body {
            color: #555;
            background: #eeeeee;
            margin:0;
            padding: 0;
            box-sizing: border-box;}

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
	progress {
        width: 150px; /* 프로그래스 바의 폭을 조절할 수 있습니다. */
        height: 20px; /* 프로그래스 바의 높이를 조절할 수 있습니다. */
    }

    #progressContainer {
      position: absolute;
      width: 300px;
      height: 15px;
      left:50%;
      background-color: #eee;
      cursor: pointer;
      border-radius:15px;
      transform: translateX(-50%);
    }

    #progressBar {
      position: absolute;
      top: 0;
      left: 0;
      border-radius:15px;
      height: 100%;
      background-color: #4caf50;
    }

    #progressText {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #fff;
      font-size:0.8rem;
    }

    #tooltip {
      display: none;
      position: absolute;
      background-color: #333;
      color: #fff;
      padding: 5px;
      border-radius: 3px;
      bottom: -35px;
      left: 50%;
      transform: translateX(-50%);
    }
   .quest_tab{
    min-width:136px;
    width:250px;
    height:65px;
    padding:10px;
    font-weight:600;
    font-size:1.2rem;
    border: 1px solid #E2E3E4;
    border-radius:15px;
    background-color:#F5F6FA;
    text-align:center;
    margin:0 20px;
    }
    .quest_tab:hover{   
    background-color:#EEEFF3;
    cursor: pointer;
    }
    .quest_tab.selected{   
    background-color:#E4FFD5;
    }
.shadow-hexagon{
    filter: drop-shadow(0px 10px 10px rgba(0, 0, 0, 0.1));
    display:flex;
    min-width:1097px;
    margin-bottom:50px;
}
.outer-hexagon {
            position: relative;
            width: 100px;
            height: 100px;
            background-color: #F5F6FA;
            clip-path: polygon(45% 1.33975%, 46.5798% 0.60307%, 48.26352% 0.15192%, 50% 0%, 51.73648% 0.15192%, 53.4202% 0.60307%, 55% 1.33975%, 89.64102% 21.33975%, 91.06889% 22.33956%, 92.30146% 23.57212%, 93.30127% 25%, 94.03794% 26.5798%, 94.48909% 28.26352%, 94.64102% 30%, 94.64102% 70%, 94.48909% 71.73648%, 94.03794% 73.4202%, 93.30127% 75%, 92.30146% 76.42788%, 91.06889% 77.66044%, 89.64102% 78.66025%, 55% 98.66025%, 53.4202% 99.39693%, 51.73648% 99.84808%, 50% 100%, 48.26352% 99.84808%, 46.5798% 99.39693%, 45% 98.66025%, 10.35898% 78.66025%, 8.93111% 77.66044%, 7.69854% 76.42788%, 6.69873% 75%, 5.96206% 73.4202%, 5.51091% 71.73648%, 5.35898% 70%, 5.35898% 30%, 5.51091% 28.26352%, 5.96206% 26.5798%, 6.69873% 25%, 7.69854% 23.57212%, 8.93111% 22.33956%, 10.35898% 21.33975%);            margin: 0 20px;
}
.inner-hexagon {
            position: absolute;
            width: 83px;
            height: 83px;
            background-color: #fff;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            clip-path: polygon(45% 1.33975%, 46.5798% 0.60307%, 48.26352% 0.15192%, 50% 0%, 51.73648% 0.15192%, 53.4202% 0.60307%, 55% 1.33975%, 89.64102% 21.33975%, 91.06889% 22.33956%, 92.30146% 23.57212%, 93.30127% 25%, 94.03794% 26.5798%, 94.48909% 28.26352%, 94.64102% 30%, 94.64102% 70%, 94.48909% 71.73648%, 94.03794% 73.4202%, 93.30127% 75%, 92.30146% 76.42788%, 91.06889% 77.66044%, 89.64102% 78.66025%, 55% 98.66025%, 53.4202% 99.39693%, 51.73648% 99.84808%, 50% 100%, 48.26352% 99.84808%, 46.5798% 99.39693%, 45% 98.66025%, 10.35898% 78.66025%, 8.93111% 77.66044%, 7.69854% 76.42788%, 6.69873% 75%, 5.96206% 73.4202%, 5.51091% 71.73648%, 5.35898% 70%, 5.35898% 30%, 5.51091% 28.26352%, 5.96206% 26.5798%, 6.69873% 25%, 7.69854% 23.57212%, 8.93111% 22.33956%, 10.35898% 21.33975%);	    
        }
.clear-info{
	position: absolute;
	background-color: #fff;
	border: 1px solid #E2E3E4;
	padding:1px 5px;
	border-radius:15px;
	top: 60%;
	right:0%;
	font-weight:600;
}
.quest-box{
	position:relative;
	width:156.7px;
}
    </style>
    <title>Document</title>
</head>
<div class="main">
    <input id="tab1" type="radio" name="tabs">
    <label for="tab1" onclick="changeTab('profile')">프로필</label>

    <input id="tab2" type="radio" name="tabs">
    <label for="tab2" onclick="changeTab('problem')">기록</label>

    <?php 
    if($user==$_SESSION[$OJ_NAME.'_'.'user_id'] || isset($_SESSION[$OJ_NAME.'_'.'administrator'])){//운영자이거나, 본인일 경우에만 보임
    ?>
    <input id="tab3" type="radio" name="tabs" checked>
    <label for="tab3" onclick="changeTab('quest')">퀘스트</label>

    <input id="tab4" type="radio" name="tabs">
    <label for="tab4" onclick="changeTab('inventory')">인벤토리</label>
    <?php } ?>

    <div style="margin:10px;"></div>

</div>

<?php 
	$sql = "select user_git_id,nick_color from users where user_id = '$user'";
	$user_info = pdo_query($sql);
	$user_git_id = $user_info[0][0];
	$nick_color = $user_info[0][1];
	$sql = "select tier from uinfo where user_id = '$user'";
	$uinfo_db=pdo_query($sql);
	$tier = $uinfo_db[0][0];

	$l = 1;
	$r = 30000;
	$idx = 0;
	while($l <= $r)
	{
   	    $mid = intval(($l + $r) / 2);
   	    if($exp_total[$mid] > $exp_point) $r = $mid - 1;
   	    else{
     	 	$idx = $mid;
     	 	$l = $mid + 1;
   		}
	}
	$need_exp = $exp_point - $exp_total[$idx];
	?>
<input type="hidden" name="class" id="class" value="일일">

<div style="position:relative" id="user_card">		             
                <center><img id="github-avatar" src="" alt="GitHub Avatar" style="margin-top:60px; width:100px;height:100px;border-radius:20%;"></center>

                <div style="text-align:center;margin-top:25px;">
                    <div class="header" style="color:<?php echo $nick_color;?>; font-size:1.5rem;font-weight:600;"><?php echo $user?></div>
		    <div class="header" style="margin-top:13px;font-size:1.3rem;font-weight:600;"><?php echo $tier; ?></div>
		<div id="profile_db"></div>
</div>

<div id="class_db"></div>
<div id="quest_db"></div>


<div class="shadow-hexagon flex-box">

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">1000</div>
        </div>
      	<div style="position:relative;">
	   <img src="/image/algo_coin.png" style="height:17px;width:auto;position:absolute;top: 50%;left:27%;transform: translate(0, -50%);"><div style="margin-left:37px;">20</div>
	</div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">200</div>
        </div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info" style="right:15%;">
        <div style="position:relative;">
	   <img src="/image/algo_coin.png" style="height:17px;width:auto;position:absolute;top: 50%;left:0%;transform: translate(0, -50%);"><div style="margin-left:19px;">20</div>
	</div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">500</div>
        </div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">1000</div>
        </div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info" style="right:15%;">
        <div style="position:relative;">
	   <img src="/image/algo_coin.png" style="height:17px;width:auto;position:absolute;top: 50%;left:0%;transform: translate(0, -50%);"><div style="margin-left:19px;">100</div>
	</div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">200</div>
        </div>
    </div>
</div>


</div>



<div class="shadow-hexagon flex-box">

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">1000</div>
        </div>
      	<div style="position:relative;">
	   <img src="/image/algo_coin.png" style="height:17px;width:auto;position:absolute;top: 50%;left:27%;transform: translate(0, -50%);"><div style="margin-left:37px;">20</div>
	</div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">200</div>
        </div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info" style="right:15%;">
        <div style="position:relative;">
	   <img src="/image/algo_coin.png" style="height:17px;width:auto;position:absolute;top: 50%;left:0%;transform: translate(0, -50%);"><div style="margin-left:19px;">20</div>
	</div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">500</div>
        </div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">1000</div>
        </div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info" style="right:15%;">
        <div style="position:relative;">
	   <img src="/image/algo_coin.png" style="height:17px;width:auto;position:absolute;top: 50%;left:0%;transform: translate(0, -50%);"><div style="margin-left:19px;">100</div>
	</div>
    </div>
</div>

<div class="quest-box">
    <div class="outer-hexagon">
        <div class="inner-hexagon"></div>    
    </div>
    <div class="clear-info">
        <div style="position:relative;">
            <img src="/image/exp.png" style="height:13px;width:auto;position:absolute;top: 50%;transform: translate(0, -50%);"><div style="margin-left:37px;">200</div>
        </div>
    </div>
</div>




</div>

<?php include("template/$OJ_TEMPLATE/footer.php");?>
<script>
function changeTab(tabId) {
    window.location.href = "userinfo.php?user=<?php echo $user;?>&tab=" + tabId;
}

// 사용자 이름을 지정
    var username = '<?php echo $user_git_id;?>';
    // GitHub API를 통해 프로필 사진 URL 가져오기
    fetch(`https://api.github.com/users/${username}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('GitHub 사용자를 찾을 수 없습니다.');
            }
            return response.json();
        })
        .then(data => {
            // 이미지 태그의 src 속성에 프로필 사진 URL 설정
            document.getElementById('github-avatar').src = data.avatar_url;
        })
        .catch(error => {
	    document.getElementById('github-avatar').src = '/image/mainicon.png';            
        });

function showTooltip(needExp, maxExp) {
    var progressBar = document.getElementById('progressBar');
    var tooltip = document.getElementById('tooltip');

    // 툴팁 내용 설정
    var tooltipText = needExp + ' / ' + maxExp;
    tooltip.innerText = tooltipText;

    // 툴팁 위치 설정
    tooltip.style.display = 'block';
  }

  function hideTooltip() {
    var tooltip = document.getElementById('tooltip');
    tooltip.style.display = 'none';
  }
function selectTab(tab) {
        var allTabs = document.querySelectorAll('.quest_tab');
        allTabs.forEach(function (item) {
            item.classList.remove('selected');
        });
        // 클릭한 퀘스트 탭에 'selected' 클래스 추가
        tab.classList.add('selected');
	var questclass = tab.getAttribute('name');
	document.getElementById('class').value = questclass;
	quest_ajax();
    }

function profile_ajax() {
	var user = <?php echo json_encode($user); ?>;
        // jQuery를 사용하여 서버에 요청 및 결과 갱신
	$.ajax({
    	type: 'POST',
    	url: './quest/profile_db.php',
    	data: {
	    user: user,
    	},
    	success: function (data) {
        	$('#profile_db').html(data);
    		}
	});	
    }
function class_ajax() {
	var user = <?php echo json_encode($user); ?>;
        // jQuery를 사용하여 서버에 요청 및 결과 갱신
	$.ajax({
    	type: 'POST',
    	url: './quest/class_db.php',
    	data: {
	    user: user,
    	},
    	success: function (data) {
        	$('#class_db').html(data);
    		}
	});	
    }
function quest_ajax() {
	var user = <?php echo json_encode($user); ?>;
	var quest = $('#class').val();
	$.ajax({
    	type: 'POST',
    	url: './quest/quest_db.php',
    	data: {
	    user: user,
	    quest: quest,
    	},
    	success: function (data) {
        	$('#quest_db').html(data);
    		}
	});	
    }

profile_ajax();
class_ajax();
quest_ajax();

</script>

