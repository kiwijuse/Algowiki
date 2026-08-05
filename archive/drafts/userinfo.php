<?php
 $cache_time=10; 
 $OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
        require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	require_once("./include/const.inc.php");
	require_once("./include/my_func.inc.php");
	require_once("include/memcache.php");

$now =  date('Y-m-d H:i', time());
	if(isset($OJ_OI_MODE)&&$OJ_OI_MODE&&!isset($_SESSION[$OJ_NAME."_administrator"])){
               
                $sql="select count(contest_id) from contest where start_time<'$now' and end_time>'$now' and title like '%$OJ_NOIP_KEYWORD%'";
                $row=pdo_query($sql);
                $cols=$row[0];
                //echo $sql;
                //echo $cols[0];
                if($cols[0]>0) {
                      $view_errors =  "<h2> $MSG_NOIP_WARNING </h2>";
                      require("template/".$OJ_TEMPLATE."/error.php");
                      exit(0);
                }
        }

$user=$_GET['user'];
$sql ="select user_id from users where user_id = '$user'";
$result=pdo_query($sql);
if($result==NULL){
	require("error/user_notfound.php");
	exit(0);

}
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';


/////////////////////////Template
if($tab=='profile'){
require("userinfo/userinfo_profile.php");
}else if($tab=='problem'){
require("userinfo/userinfo_problem.php");
}else if($tab=='quest'){
require("userinfo/userinfo_quest.php");
}else if($tab=='shop'){
require("userinfo/userinfo_shop.php");
}else if($tab=='inventory'){
require("userinfo/userinfo_inventory.php");
}else if($tab=='setting'){
require("userinfo/userinfo_setting.php");
}else{
require("userinfo/userinfo_profile.php");
}

/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

