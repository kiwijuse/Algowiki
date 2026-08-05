<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once('./include/my_func.inc.php');
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
$show_title="AlgoWiki";
$view_title = "Welcome To Online Judge";
$result = false;
include("template/$OJ_TEMPLATE/header.php");
$onclick = 'login_first()';
if(isset($_SESSION[$OJ_NAME.'_user_id']))$onclick = 'go_quest(\'userinfo.php?user='.$_SESSION[$OJ_NAME.'_user_id'].'&tab=quest\')';
if(!isMobileDevice())$ment = '알고리즘 공부와<br>퀘스트, LV등 게임적 요소를<br>결합하여 흥미 유발<br>';
else $ment = '알고리즘 공부와<br>게임적 요소를<br>결합하여 흥미 유발<br>';	
?>
<head>       
    <link rel="stylesheet" href="index.css">
    <script src="https://cdn.tailwindcss.com"></script>
<style>
</style>
</head>
<div class="no_drag">
<div style="position:relative;">
    <img src="/image/main_image_1.png" class="main_image">
    <div class="image-big">게임처럼<br>배우는<br>알고리즘
	<div class="image-small" onclick="go_main()"><img src="/image/mainicon.png" style="width:auto;height:25px;">Algorithm Wiki</div>
    </div>
</div>

<div style="text-align:center;margin-top:70px;margin-bottom:70px;font-size:18px;">
    알고리즘 위키는
    <div style="font-size:22px;margin-top:20px;line-height:28px;opacity:.4;">현대의 디지털 지식을 엮어내는 플랫폼으로<br>미래의 기술적 혁신과 지적 발전을 위한</div>
    <div style="font-size:22px;line-height:28px;">핵심 아카이브입니다</div>
</div>

<div class="problem_text">
    <div style="font-size:22px;line-height:28px;margin-left:10%;"><div style="font-size:16px;margin-bottom:20px;">알고리즘 위키는</div>
        다양한 문제와<br>
 	실력에 맞는<br>
	퀘스트 시스템으로<br>
	알고리즘 학습을<br>
	효율적으로 이끕니다
	<div class="go_problem" onclick="go_problem()">문제 바로가기</div>
    </div>
    <div class="p_list">
	<!--Hello World!,사칙연산,구구단,End Of File,빠른 입출력-->
	<?php
	echo '<div class="problem_box box1">
	<div class="question-card bg-white p-4" onclick="ToAddress(\'1001\')">
	<h3 class="font-semibold mb-1">사칙연산</h3>
	<div class="flex space-x-1 text-sm mb-2">
	<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'구현\');">구현</div></div>
	<div class="flex space-x-1 text-sm des-text">
      	<p class="text-sm mb-4" style="overflow-wrap: break-word;">간단한 사칙연산을 수행하는 프로그램을 작성해보자.
	사칙연산이란, 덧셈 (+), 뺄셈 (−), 곱셈 (×), 나눗셈 (/) 의 네 가지 이항연산을 묶어 부르는 말이다.</p>
	</div></div></div>';        

	echo '<div class="problem_box box2">
	<div class="question-card bg-white p-4" onclick="ToAddress(\'1000\')">
	<h3 class="font-semibold mb-1">Hello World!</h3>
	<div class="flex space-x-1 text-sm mb-2">
	<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'구현\');">구현</div></div>
	<div class="flex space-x-1 text-sm des-text">
      	<p class="text-sm mb-4" style="overflow-wrap: break-word;">"Hello World!"를 출력해보자.</p>
	</div></div></div>';

	echo '<div class="problem_box box3">
	<div class="question-card bg-white p-4" onclick="ToAddress(\'1010\')">
	<h3 class="font-semibold mb-1">수 정렬하기</h3>
	<div class="flex space-x-1 text-sm mb-2">
	<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'정렬\');">정렬</div></div>
	<div class="flex space-x-1 text-sm des-text">
      	<p class="text-sm mb-4" style="overflow-wrap: break-word;">N개의 수로 이루어진 수열 a1, a2, ... aN과, k가 주어진다.k가 1이라면 오름차순 정렬한 결과를, 
	2라면 내림차순 정렬한 결과를 출력해보자.</p>
	</div></div></div>';

	echo '<div class="problem_box box4">
	<div class="question-card bg-white p-4" onclick="ToAddress(\'1007\')">
	<h3 class="font-semibold mb-1">구구단</h3>
	<div class="flex space-x-1 text-sm mb-2">
	<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'수학\');">수학</div>
	<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'구현\');">구현</div></div>
	<div class="flex space-x-1 text-sm des-text">
      	<p class="text-sm mb-4" style="overflow-wrap: break-word;">N 이 주어진다. 구구단 N 단을 출력해보자.</p>
	</div></div></div>';

	echo '<div class="problem_box box5">
	<div class="question-card bg-white p-4" onclick="ToAddress(\'1009\')">
	<h3 class="font-semibold mb-1">빠른 입출력</h3>
	<div class="flex space-x-1 text-sm mb-2">
	<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'수학\');">수학</div>
	<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'구현\');">구현</div>
	<div class="tag px-2 py-1 bg-gray-100 rounded-lg" onclick="redirectToAddress(\'사칙연산\');">사칙연산</div>
	</div>
	<div class="flex space-x-1 text-sm des-text">
      	<p class="text-sm mb-4" style="overflow-wrap: break-word;">같은 입출력이라도, 어떤 방식을</p>
	</div></div></div>';?>
    </div>	
</div>

<div style="position:relative;">
    <img src="/image/main_image_2.png" class="main_image">
    <div class="image-big2">같이 발전해 나아갈 수 있는<br>
	질의 응답 기반의<br>
	게시판 시스템을 구축합니다
	<div class="image-small2" onclick="go_board()">게시판 바로가기</div>
    </div>    
</div>

<div style= "padding:10%;width:100%;">
    <div style="font-size:22px;line-height:28px;opacity:.4;">알고리즘 위키의</div>
    <div style="display:flex;font-size:22px;line-height:28px;margin-bottom:20px;">
        새로운 소식<div style="opacity:.4;">을 만나보세요</div>
    </div>
    <div style="display:flex;">
	<div class="new-info" onclick="go_problem()">
	    <img src="/image/4.png" style="border-radius:20px;">
	    <div class="new-info-text">
	    지속적인 문제<br>업데이트로 인하여<br>문제수 200 돌파<br>	    
	    </div>
	</div>

	<div class="new-info" onclick="<?php echo $onclick;?>">
	    <img src="/image/2.png" style="border-radius:20px;">
	    <div class="new-info-text">
	    <?php echo $ment;?>
	    </div>
	</div>

	<div class="new-info" onclick="go_wiki()">
	    <img src="/image/10.png" style="border-radius:20px;">
	    <div class="new-info-text">
	    지속적인 위키페이지<br>업데이트를 통해<br>알고리즘 진입장벽 완화<br>
	    </div>
	</div>

	<div class="new-info" onclick="go_board()">
	    <img src="/image/11.png" style="border-radius:20px;">
	    <div class="new-info-text">
	    실시간으로 올라오는<br>질문과 답변을 통해<br>알고리즘 이해 촉진<br>
	    </div>
	</div>
    </div>
</div>

</div>
<?php
include("template/$OJ_TEMPLATE/footer.php");
?>
<script>
function go_main() {
    window.location.href = '';
}
function go_problem() {
    window.location.href = 'problem_list.php';
}
function go_board() {
    window.location.href = 'board.php';
}
function go_wiki() {
    window.location.href = 'category.php';
}
function go_quest(url){
    window.location.href = url;
}
function redirectToAddress(tag) {
    window.location.href = 'problem_list.php?filter1=' + tag;
}
function ToAddress(tag) {
    window.location.href = 'problem.php?id=' + tag;
}
function login_first(){
    alert("로그인 후 이용하실 수 있습니다.");
}
</script>

