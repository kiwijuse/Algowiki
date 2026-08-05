<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
$show_title="Quiz - AlgoWiki";

include("template/$OJ_TEMPLATE/header.php");
?>
<style>
.quiz_border{
    margin:0 auto;
    text-align:center;
    width:75%;
    border-radius:15px;
    border:1px solid #E2E3E4;
    margin-top:50px;
    overflow:hidden;
}
.quiz_title{
    font-size:20px;
    font-weight:600;
    padding:10px 30px;
    margin-top:10px;
}
.quiz_content{   
    padding:15px;
}
.quiz_flex{
    display:flex;
    font-size:20px;
    align-items:center;
    cursor:pointer;
    width:max-content;
    margin:5px 0px;
}
.quiz_flex:hover{
    color:#6BB9E8;
}
.quiz_small{
    padding:5px 15px;
    padding-left:10px;
    font-size:18px;
}
.quiz_explain{
    display:flex;
    padding-bottom:15px;
}
.quiz_next{
    padding:10px;
    font-weight:600;
    font-size:18px;
    border-top: 1px solid #E2E3E4;  
    cursor:pointer;
    background-color:#5975D1;
    color:white;
}
.quiz_next:hover{
    background-color:#6BB9E8;
}
.explain_text{
    text-align:center;
    padding:0px 45px;
    margin-bottom:10px;
}
.hidden_text{
    color:white;
    border:1px solid black;
    margin:0 5px;
    -webkit-user-select:none;
    -moz-user-select:none;
    -ms-user-select:none;
    user-select:none;
}
.input_text{
    height:35px;
    font-size:18px;
    margin-bottom:5px;
    z-index:1;
    outline:none;
}
.input_flex{
    display:flex;
    justify-content: center;
}
.submit{
    height:35px;
    border:1px solid light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
    border-left:none;
    padding:0 5px;
    padding-left:20px;
    line-height:35px;
    border-radius:15px;    
    margin-left: -18px;
    font-weight:600;
    background-color:#21ba45;
    color:white;
    cursor:pointer;
}
.submit:hover{
        background-color: #96EB85;
}
</style>
<div class="quiz_border">
    <div class="quiz_title">
	빈칸에 들어갈것으로 알맞은 것은?
    </div>
    <div style="display:flex;padding:15px;padding-bottom:0px;">
	김남훈은 <div class="hidden_text">바보다</div>
    </div>
    <div class="quiz_content">
    	<div class="quiz_flex">
	    <div style="margin-bottom:4px;">①</div><div class="quiz_small">바보다</div>
	</div>
	<div class="quiz_flex">
	     <div style="margin-bottom:4px;">②</div><div class="quiz_small">바보다</div>
	</div>
	<div class="quiz_flex">
	    <div style="margin-bottom:4px;">③</div><div class="quiz_small">바보다</div>
	</div>
	<div class="quiz_flex">
	    <div style="margin-bottom:4px;">④</div><div class="quiz_small">바보다</div>
	</div>
	<div class="quiz_flex">
	    <div style="margin-bottom:4px;">⑤</div><div class="quiz_small">바보다</div>
	</div>
    </div>
    <div class="quiz_explain">
	<div class="explain_text">엄나문은 바보이기 떄문에 바보다.</div>	
    </div>
    <div class="quiz_next">다음 문제</div>
</div>


<div class="quiz_border">
    <div class="quiz_title">
	빈칸에 들어갈것으로 알맞은 것은?
    </div>
    <div style="display:flex;padding:15px;padding-bottom:0px;">
	김남훈은 <div class="hidden_text">바보다</div>
    </div>
    <div class="quiz_content" style="">
    	<div class="input_flex">
		<input class="input_text"><div class="submit">제출</div>
	</div>
    </div>
    <div class="quiz_explain">
	<div class="explain_text">엄나문은 바보이기 떄문에 바보다.</div>	
    </div>
    <div class="quiz_next">다음 문제</div>
</div>
