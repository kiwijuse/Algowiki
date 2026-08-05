<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
$view_title = "Welcome To Online Judge";
$problem_id = isset($_GET['id']) ? $_GET['id'] : '1000';
$sql = "select * from problem where problem_id = '$problem_id'";
$problem_db = pdo_query($sql);
if($problem_db == NULL){
require("error/problem_notfound.php");
exit(0);
}
if($problem_db[0][13]!='N' && !isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
require("error/problem_defunct.php");
exit(0);
}
$difficulty = $diff_class[$problem_db[0][19]];
$difficulty_src = $diff_src[$problem_db[0][19]];
$difficulty_color = $diff_color[$problem_db[0][19]];
$sql = "select user_id from privilege where rightstr = 'p$problem_id'";
$writer = pdo_query($sql)[0][0];

$sql = "select nick_color from users where user_id = '$writer'";
$w_n_c = pdo_query($sql)[0][0];
$dif_show = 'false';
if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
$user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];
$sql = "select show_difficulty from uinfo where user_id = '$user_id'";
$show_difficulty = pdo_query($sql)[0][0];
    if($show_difficulty==1){
    $dif_show = 'true';
    }else if($show_difficulty==2 && pdo_query("select count(*) from accept where user_id='$user_id' and problem_id='$problem_id'")[0][0] > 0 ){
    $dif_show = 'true';
    }
}
?>
<?php $show_title="".$problem_db[0][0]." : ".$problem_db[0][1]." - AlgoWiki"; ?>
<?php include("template/$OJ_TEMPLATE/header.php");?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
a{
    text-decoration: none;
}
ol {
    list-style-type: decimal;
    margin-left:10px;
    padding:0 0 0 50px;
            }
ul {
    list-style-type: disc;
    margin-left:10px;
    padding:0 0 0 50px;
            }

.underline-on-hover {
    text-decoration: none;
  }
  .underline-on-hover:hover {
    text-decoration: underline;
    text-underline-position: auto;
  }
.title{
    font-size:28px;
    font-weight:700;
    margin-top:30px;
    display:flex;
    width:100%;
    flex-wrap:wrap;
}
.button{
    display:flex;
    margin-top:20px;
    font-weight:700;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px; 
}
.button a.buttons {
    background: #fff;
    color: #000;
    background-color: rgba( 255, 255, 255, 0 );
    border: 1px solid #bbb;
    
    border-radius:5px;
    padding:10px 15px;
    cursor:pointer;
    -webkit-user-select:none;
    -moz-user-select:none;
    -ms-user-select:none;
    user-select:none;
}
.button a.buttons:hover {
    background-color:#F2F2F2;
}
.info{
    display:flex;
    float:right;
    font-weight:600;    

    border-radius:5px;
}
.info-border{
    text-align:center;    
    padding:3px;
    margin-right:5px;
}
.description{
    font-size:22px;
    margin-top:10px;
    margin-bottom:50px;
    font-weight:600;
}
.description.small{
    font-size:16px;
    font-family: "Open Sans", "Source Han Sans SC", "Noto Sans CJK SC", "PingFang SC", "Hiragino Sans GB", "Microsoft Yahei", sans-serif;
    font-weight:normal;
    margin:10px 0;
    border-radius:5px;
    border: 1px solid #bbb;
    padding: 14px;

    img{
	margin:0 auto;
    }
}
/*.description.small{
    font-size:16px;
    font-family: "Open Sans", "Source Han Sans SC", "Noto Sans CJK SC", "PingFang SC", "Hiragino Sans GB", "Microsoft Yahei", sans-serif;
    font-weight:normal;
    margin:10px 0;    
    border-top: 1px solid #bbb;
    border-bottom: 1px solid #bbb;
    padding: 14px;
}
*/
.sample{
    font-size:22px;
    margin-top:10px;
    margin-bottom:50px;
    font-weight:600;
}
.sample-border{
    font-size:16px;
    border-radius:5px;
    border: 1px solid #bbb;
    display:flex;
    margin:10px 0;
}
.input-sample{
    padding:10px;
    width:49.8%;
}
.output-sample{
    margin-left:auto;
    width:49.8%;
    padding:10px;
}
.sample-in{
    border-radius:5px;
    background-color:#F5F5F5;
    margin-top:5px;
    font-weight:500;
}
.collapse{
    	visibility: visible;
    }
    .accordion-button{
        --bs-accordion-btn-focus-box-shadow: none;
	height: 37.9px;
    }
.tag{
    padding: 5px 10px;
    background-color: #F3F4F6;
    border-radius: 5px;
    cursor: pointer;
    display: block; 
    list-style-type: none;
    -webkit-user-select:none;
    -moz-user-select:none;
    -ms-user-select:none;
    user-select:none;
    color:black;
    margin-right:10px;
}
.tag:hover{
    color:black;
    background-color:#DEDEDE;

}
    </style>
</head>
<body>
<div style="background: rgba(255,255,255,0.6);border-radius:10px;padding:10px;">
<div class="title"><?php echo $problem_db[0][0];?> - <?php echo $problem_db[0][1];?>
    <?php 
    if($dif_show == 'true'){
    echo '<div style="margin-left:auto;font-size:18px;font-weight:600;display:flex;color:'.$difficulty_color.'">'.$difficulty.'
    <img src="'.$difficulty_src.'" style="width:auto;height:50px;transform: translate(0%, -20%);margin-left:10px;"></div>';
  }
    ?>
</div>

<div class="button">
    <a class="buttons" href="submitpage.php?id=<?php echo $problem_id;?>">제출</a>
    <a class="buttons" href="status.php?problem_id=<?php echo $problem_id;?>">채점 현황</a>
<?php
if(isset($_SESSION[$OJ_NAME.'_user_id'])){
    echo'<a class="buttons" href="status.php?problem_id='.$problem_id.'&user_id='.$_SESSION[$OJ_NAME.'_user_id'].'">내 채점 현황</a>';
}?>
    <a class="buttons" href="board.php?data=search&cate=문제번호&dataValue=전체&search=<?php echo $problem_id;?>">질문 게시판</a>
<?php
if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
    echo '<div style="margin-left:auto;height:43px;display:flex;gap: 5px; ">';
	if($problem_db[0][13]!='N')echo '<a class="buttons" style="background-color:#FF4242;border: 1px solid #FF4242;cursor:default;margin-right:10px;color:white;">비공개</a>';
        echo '<a class="buttons" href="/admin/problem_edit.php?id='.$problem_id.'">문제 수정</a>';
        echo '<a class="buttons" href="/admin/phpfm.php?pid='.$problem_id.'&frame=3">채점 데이터</a></div>';
}?>
</div>


<?php
	$sql = "select ac_person_count, first_ac_try from pinfo where problem_id = $problem_id";
	$res = pdo_query($sql);

	$apc = $res[0][0];
	$fat = $res[0][1];
	
	$ac_rate = "0.000";
	if($apc > 0) $ac_rate = number_format(round($apc / $fat, 5) * 100, 3);
?>
<div style="width:100%;height:46.97px;">
<div class="info">
    <div class="info-border"><div style="border-bottom:1px solid #bbb;">시간 제한</div><?php echo $problem_db[0][11];?> S</div>
    <div class="info-border"><div style="border-bottom:1px solid #bbb;">메모리 제한</div><?php echo $problem_db[0][12];?> MB</div>
    <div class="info-border"><div style="border-bottom:1px solid #bbb;">제출 수</div><?php echo $problem_db[0][15];?></div>
    <div class="info-border"><div style="border-bottom:1px solid #bbb;">정답 수</div><?php echo $problem_db[0][14];?></div>
    <div class="info-border"><div style="border-bottom:1px solid #bbb;">정답률</div><?php echo $ac_rate.'%'; ?></div>

</div>
</div>


<div class="description">
문제 설명
    <div class="description small">
    <?php echo $problem_db[0][2];?>
    </div>
</div>

<div class="description">
입력 설명
    <div class="description small">
    <?php echo $problem_db[0][3];?>
    </div>
</div>

<div class="description">
출력 설명
    <div class="description small">
    <?php echo $problem_db[0][4];?>
    </div>
</div>

<div class="sample">
예시 1
    <div class="sample-border">
        <div class="input-sample"><div style="display:flex;align-items:center;text-align:center;">입력<button data-clipboard-target="#sample-in-1" style="margin-left:5px;"><img src="/image/copy.png" style="width:auto;height:18px;"></button></div>
	    <div class="sample-in"><pre style="min-height:46.7px;"><code id="sample-in-1" style="font-size:18px;"><?php echo $problem_db[0][5];?></code></pre></div>
    	</div>
	<div class="output-sample"><div style="display:flex;align-items:center;text-align:center;">출력<button data-clipboard-target="#sample-out-1" style="margin-left:5px;"><img src="/image/copy.png" style="width:auto;height:18px;"></button></div>
	    <div class="sample-in"><pre style="min-height:46.7px;"><code id="sample-out-1" style="font-size:18px;"><?php echo $problem_db[0][6];?></code></pre></div>
	</div>
    </div>
</div>

<?php
$sql = "select * from sdata where problem_id = '$problem_id'";
$result = pdo_query($sql);
$i = 2;
foreach($result as $row){
echo '<div class="sample">
예시 '.$i.'
    <div class="sample-border">
        <div class="input-sample"><div style="display:flex;align-items:center;text-align:center;">입력<button data-clipboard-target="#sample-in-'.$i.'" style="margin-left:5px;"><img src="/image/copy.png" style="width:auto;height:18px;"></button></div>
	    <div class="sample-in"><pre style="min-height:46.7px;"><code id="sample-in-'.$i.'" style="font-size:18px;">'.$row["sdata_in"].'</code></pre></div>
    	</div>
	<div class="output-sample"><div style="display:flex;align-items:center;text-align:center;">출력<button data-clipboard-target="#sample-out-'.$i.'" style="margin-left:5px;"><img src="/image/copy.png" style="width:auto;height:18px;"></button></div>
	    <div class="sample-in"><pre style="min-height:46.7px;"><code id="sample-out-'.$i.'" style="font-size:18px;">'.$row["sdata_out"].'</code></pre></div>
	</div>
    </div>
</div>';
$i+=1;}?>


<?php if($problem_db[0][8] != NULL){
echo '<div class="description">힌트
    <div class="description small">
    '.$problem_db[0][8].'
    </div>
</div>';}?>


<div class="description">
도움
    <div class="description small" style="display:flex;">
    만든 사람 :&nbsp;<a class="underline-on-hover" style="color:<?php echo $w_n_c;?>" href="userinfo.php?user=<?php echo $writer;?>"><?php echo $writer;?></a><br>
    
    </div>
</div>


<div class="accordion" id="accordionPanelsStayOpenExample">
    <div class="accordion-item" style="border:border-radius:5px;border: 1px solid #bbb;">
    	<h2 class="accordion-header">
 	    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
		<h4 class="ui header">알고리즘 분류</h4>
   	    </button>
	</h2>
    	<div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
	    <div class="accordion-body" style="display:flex;">
		<?php		    
		$sql = "select source from problem where problem_id = '$problem_id'";
		$tag = pdo_query($sql)[0][0];
		$tags = explode(' ', $tag);
		foreach ($tags as $tag){
		$tag_name = str_replace('_', ' ', $tag);
		$tag_name_ = $tag_name == "Mos" ? "Mo's" : $tag_name;

		echo '<a class="tag" href="problem_list.php?filter1='.$tag_name.'">' . $tag_name_ . '</a>';
		}
		?>
	    </div>
	</div>
    </div>
</div>



</body>
</html>
<?php
include("template/$OJ_TEMPLATE/footer.php");
?>
<script>
var clipboard = new ClipboardJS('[data-clipboard-target]');
    clipboard.on('success', function(e) {
	e.clearSelection();
    });
</script>


<script src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
  	tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
  });
</script>
