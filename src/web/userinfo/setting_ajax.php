
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
$user = isset($_POST['user']) ? $_POST['user'] : '';
$num = isset($_POST['num']) ? $_POST['num'] : '1';
if($num==1){

	echo '<div class="setting-title">비밀 번호 변경</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">아이디</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="user_id" id="user_id" disabled="disabled" type="text" value="'.$user.'">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">비밀번호</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="password" id="password" type="password">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">새 비밀번호</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="new_password1" id="new_password1" type="password">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">새 비밀번호 (확인)</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="new_password2" id="new_password2" type="password">
	    </div>
	</div>

	<div style="margin-top:100px;"></div>
	<div class="save_button" onclick="save()">저장</div>';

}

else if($num==2){

$sql = "select email from users where user_id ='$user'";
$email=pdo_query($sql)[0][0];

	echo '<div class="setting-title">이메일 주소 변경</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">아이디</div>
	    <div style="margin-left:10px;">
		<input class="form-control" id="user_id" disabled="disabled" type="text" value="'.$user.'">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">비밀번호</div>
	    <div style="margin-left:10px;">
		<input class="form-control" id="password" type="password">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">E-mail</div>
	    <div style="margin-left:10px;">
		<input class="form-control" id="new_email" type="text" value="'.$email.'">
	    </div>
	    <div class="m_check_button" onclick="">메일 인증</div>
	</div>

	<div style="margin-top:149px;"></div>
	<div class="save_button" onclick="save()">저장</div>';
}

else if($num==3){
$sql = "select comment from users where user_id ='$user'";
$comment=pdo_query($sql)[0][0];

	echo '<div class="setting-title">한줄 소개 변경</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">한줄 소개</div>
	    <div style="margin-left:10px;">
		<input class="form-control" id="new_comment" type="text" value="'.$comment.'">
	    </div>
	</div>

	<div style="margin-top:247px;"></div>
	<div class="save_button" onclick="save();comment()">저장</div>';
}

else if($num==4){
$sql = "select git_link,discord_link,blog_link from user_link where user_id ='$user'";
$result=pdo_query($sql);
$git = $result[0][0];
$discord = $result[0][1];
$blog = $result[0][2];

	echo '<div class="setting-title">연결 정보 관리</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">GitHub User Name</div>
	    <div style="margin-left:10px;display:flex;">
		<input class="form-control" id="new_git" type="text" value="'.$git.'" placeholder="GitHub User Name 입력">
	    </div>
	</div>


	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub" style="display:flex;align-items:center;">Discord 사용자 ID<a style="color:white;" href="https://support.discord.com/hc/ko/articles/4407571667351-%EB%B2%95-%EC%A7%91%ED%96%89-%EA%B8%B0%EA%B4%80%EC%9D%84-%EC%9C%84%ED%95%9C-%EC%82%AC%EC%9A%A9%EC%9E%90-ID-%EC%B0%BE%EA%B8%B0" target="_blank"><div class="help-discord">?</div></a></div>
	    <div style="margin-left:10px;">
		<input class="form-control" id="new_discord" type="text" value="'.$discord.'" placeholder="숫자로 이루어진 17~18자리 사용자 ID 입력">
	    </div>
	</div>


	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">Blog Link</div>
	    <div style="margin-left:10px;">
		<input class="form-control" id="new_blog" type="text" value="'.$blog.'" placeholder="https://algowiki.co.kr 와 같은 형태로 입력">
	    </div>
	</div>

	<div style="margin-top:149px;"></div>
	<div class="save_button" onclick="save();link()">저장</div>';
}

else if($num==5){

	echo '<div class="setting-title">회원 탈퇴</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">불가능</div>
	</div>

	<div style="margin-top:247px;"></div>
	<div class="save_button" onclick="save()">저장</div>';
}

else if($num==6){
$sql = "select show_tag,show_difficulty from uinfo where user_id = '$user'";
$result=pdo_query($sql);
$tag=$result[0][0];
$difficulty=$result[0][1];


	echo '<div class="setting-title">표시</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">문제 난이도 표시</div>
	    <div style="margin-left:10px;">
		<select class="form-control" size="1" id="difficulty" style="width: 160px;font-size:1em;">
          	<option value="0">표시 안함</option>
          	<option value="1">표시</option>
         	<option value="2">맞은 문제만 표시</option>
        	</select>
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">문제 태그 표시</div>
	    <div style="margin-left:10px;">
		<select class="form-control" size="1" id="tag" style="width: 160px;font-size:1em;">
          	<option value="0">표시 안함</option>
          	<option value="1">표시</option>
         	<option value="2">맞은 문제만 표시</option>
        	</select>
	    </div>
	</div>

	<div style="margin-top:198px;"></div>
	<div class="save_button" onclick="display();save()">저장</div>';
}


else if($num==7){
$sql = "select show_source,show_git,show_discord,show_blog from uinfo where user_id = '$user'";
$result=pdo_query($sql);
$show_source=$result[0][0];
$show_git=$result[0][1];
$show_discord=$result[0][2];
$show_blog=$result[0][3];
	echo '<div class="setting-title">공개</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">소스 코드 공개</div>
	    <div style="margin-left:10px;">
		<select class="form-control" size="1" id="show_source" style="width: 160px;font-size:1em;">
          	<option value="0">모두 공개</option>
          	<option value="1">틀린 코드 비공개</option>
        	</select>
	    </div>
	</div>

	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">GitHub 공개</div>
	    <div style="margin-left:10px;">
		<select class="form-control" size="1" id="show_git" style="width: 160px;font-size:1em;">
          	<option value="0">공개</option>
          	<option value="1">비공개</option>
        	</select>
	    </div>
	</div>
	
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">Discord 공개</div>
	    <div style="margin-left:10px;">
		<select class="form-control" size="1" id="show_discord" style="width: 160px;font-size:1em;">
          	<option value="0">공개</option>
          	<option value="1">비공개</option>
        	</select>
	    </div>
	</div>

	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div class="setting-sub">Blog 공개</div>
	    <div style="margin-left:10px;">
		<select class="form-control" size="1" id="show_blog" style="width: 160px;font-size:1em;">
          	<option value="0">공개</option>
          	<option value="1">비공개</option>
        	</select>
	    </div>
	</div>

	
	<div style="margin-top:100px;"></div>
	<div class="save_button" onclick="save();opens()">저장</div>';
}

?>
<script>
if(<?php echo $num;?>==6){
document.getElementById('difficulty').value = '<?php echo $difficulty;?>';
document.getElementById('tag').value = '<?php echo $tag;?>';
}else if(<?php echo $num;?>==7){
document.getElementById('show_source').value = '<?php echo $show_source;?>';
document.getElementById('show_discord').value = '<?php echo $show_discord;?>';
document.getElementById('show_blog').value = '<?php echo $show_blog;?>';
document.getElementById('show_git').value = '<?php echo $show_git;?>';
}

</script>