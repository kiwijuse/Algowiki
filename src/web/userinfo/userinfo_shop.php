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

?>
<?php include("template/$OJ_TEMPLATE/header.php");?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
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
    <input id="tab3" type="radio" name="tabs">
    <label for="tab3" onclick="changeTab('quest')">퀘스트</label>

    <input id="tab4" type="radio" name="tabs" checked>
    <label for="tab4" onclick="changeTab('shop')">상점</label>

    <input id="tab5" type="radio" name="tabs">
    <label for="tab5" onclick="changeTab('inventory')">인벤토리</label>

    <input id="tab6" type="radio" name="tabs">
    <label for="tab6" onclick="changeTab('setting')">설정</label>
   <?php } ?>

    
    <div style="margin:10px;"></div>

</div>
<?php include("template/$OJ_TEMPLATE/footer.php");?>
<script>
window.onload = function() {
    // 프로필 탭에 대한 라디오 버튼을 찾아서 checked 속성을 추가
    document.getElementById('tab4').checked = true;
};

function changeTab(tabId) {
    window.location.href = "userinfo.php?user=<?php echo $user;?>&tab=" + tabId;
}
</script>

