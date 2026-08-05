
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
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">아이디</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="user_id" id="user_id" disabled="disabled" type="text" value="'.$user.'">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">비밀번호</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="password" id="password" type="password">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">새 비밀번호</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="new_password1" id="new_password1" type="password">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">새 비밀번호 (확인)</div>
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
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">아이디</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="user_id" id="user_id" disabled="disabled" type="text" value="'.$user.'">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">비밀번호</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="password" id="password" type="password">
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">E-mail</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="new_email" id="new_email" type="text" value="'.$email.'">
	    </div>
	    <div class="m_check_button" onclick="">메일 인증</div>
	</div>

	<div style="margin-top:149px;"></div>
	<div class="save_button" onclick="save()">저장</div>';
}

else if($num==3){
$sql = "select user_git_id from users where user_id ='$user'";
$git=pdo_query($sql)[0][0];

	echo '<div class="setting-title">GitHub User Name 변경</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">GitHub User Name</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="new_git" id="new_git" type="text" value="'.$git.'">
	    </div>
	</div>

	<div style="margin-top:247px;"></div>
	<div class="save_button" onclick="save()">저장</div>';
}

else if($num==4){
$sql = "select comment from users where user_id ='$user'";
$comment=pdo_query($sql)[0][0];

	echo '<div class="setting-title">한줄 소개 변경</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">한줄 소개</div>
	    <div style="margin-left:10px;">
		<input class="form-control" name="new_comment" id="new_comment" type="text" value="'.$comment.'">
	    </div>
	</div>

	<div style="margin-top:247px;"></div>
	<div class="save_button" onclick="save()">저장</div>';
}

else if($num==5){

	echo '<div class="setting-title">회원 탈퇴</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">불가능 ㅋ</div>
	</div>

	<div style="margin-top:247px;"></div>
	<div class="save_button" onclick="save()">저장</div>';
}

else if($num==6){//표시 설정
$sql = "select show_tag,show_difficulty,show_source from uinfo where user_id = '$user'";
$result=pdo_query($sql);
$tag=$result[0][0];
$difficulty=$result[0][1];
$source=$result[0][2];

	echo '<div class="setting-title">표시</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">문제 난이도 표시</div>
	    <div style="margin-left:10px;">
		<select class="form-control" size="1" id="difficulty" style="width: 160px;font-size:1em;">
          	<option value="0">표시 안함</option>
          	<option value="1">표시</option>
         	<option value="2">맞은 문제만 표시</option>
        	</select>
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">문제 태그 표시</div>
	    <div style="margin-left:10px;">
		<select class="form-control" size="1" id="tag" style="width: 160px;font-size:1em;">
          	<option value="0">표시 안함</option>
          	<option value="1">표시</option>
         	<option value="2">맞은 문제만 표시</option>
        	</select>
	    </div>
	</div>
	<div style="display:flex;margin:5px 15px;margin-bottom:15px;width:100%;">
	    <div style="font-size:16px;width:25%;border-right:1px solid #ddd;line-height:34px;">소스 코드 공개</div>
	    <div style="margin-left:10px;">
		<select class="form-control" size="1" id="source" style="width: 160px;font-size:1em;">
          	<option value="0">모두 공개</option>
          	<option value="1">틀린 코드 비공개</option>
        	</select>
	    </div>
	</div>

	<div style="margin-top:149px;"></div>
	<div class="save_button" onclick="display();save()">저장</div>';
}

?>
<script>
document.getElementById('difficulty').value = '<?php echo $difficulty;?>';
document.getElementById('tag').value = '<?php echo $tag;?>';
document.getElementById('source').value = '<?php echo $source;?>';

</script>