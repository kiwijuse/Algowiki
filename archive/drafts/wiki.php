<?php
////////////////////////////Common head
        $cache_time=10;
        $OJ_CACHE_SHARE=false;
        require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
        require_once('./include/setlang.php');
        $view_title= "Welcome To Online Judge";

/////////////////////////Template
$phpVariable = $_GET['phpVariable'];
$faqs_name="faqs.$OJ_LANG";
$sql="select title,content from news where title=? and defunct='N' order by news_id limit 1";
$result=pdo_query($sql,$faqs_name);
if(file_exists("./wiki/".$_GET['data'].".php")){
	require("./wiki/".$_GET['data'].".php");
}else{
	require("./wiki/noexist.php");
}

require("./wiki/".$_GET['data'].".php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
        require_once('./include/cache_end.php');
?>

