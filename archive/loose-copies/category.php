<?php
////////////////////////////Common head
	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
        require_once('./include/db_info.inc.php');
        require_once('./include/const.inc.php');
	require_once('./include/curl.php');
        require_once('./include/memcache.php');
	require_once('./include/setlang.php');
	$view_title= "Welcome To Online Judge";
 $result=false;

//////////////////////////// custom function
function sortByCountTag($a, $b)
{
	$aIsEnglish = preg_match('/^[a-zA-Z]/', $a);
    	$bIsEnglish = preg_match('/^[a-zA-Z]/', $b);

	if ($aIsEnglish && !$bIsEnglish) //a가 영어로 시작, b가 한글로 시작.
		return 1;
	elseif (!$aIsEnglish && $bIsEnglish) // a가 한글로 시작, b가 영어로 시작.
		return -1;

	//성분이 같을 땐 가나다 순 정렬
	return strcmp($a, $b);




	//global $count_tag;
	// 문제 수 기준 내림차순 정렬, 같으면 가나다 순 정렬
	//if($count_tag[$b] == $count_tag[$a]) return strcmp($a, $b);
	//return $count_tag[$b] - $count_tag[$a];
}

///////////////////////////MAIN	

	$view_category="";
	$sql=	"select source "
			."FROM `problem` where defunct='N'"
			."LIMIT 500";
	$result=mysql_query_cache($sql);//mysql_escape_string($sql));
	$category=array();
	
        foreach ($result as $row){
		$cate=explode(" ",$row['source']);
		foreach($cate as $cat){
                        $cat=trim($cat);

                        if(mb_ereg("^http",$cat)){
                                $cat=get_domain($cat);
                        }
                        array_push($category,trim($cat));
                }

	}
	$count_tag = array_count_values($category);
	$category=array_unique($category);
	$category_arr = array();

	usort($category, 'sortByCountTag');
	if (!$result){
		$view_category= "<h3>No Category Now!</h3>";
	}else{
		$i = 0;
		foreach ($category as $cat){
			if(trim($cat)=="") continue;
			$cat_ = $cat == "Mos" ? "Mo's" : $cat;

			$hash_num=hexdec(substr(md5($cat),0,7));
			$label_theme=$color_theme[$hash_num%count($color_theme)];
			if($label_theme=="") $label_theme="default";
			$view_category = "<td style='width: 40%; vertical-align:middle; height:48px;'>";			
			$view_category .= "<a href='#' onclick='redirectToproblem(\"".htmlentities(urlencode($cat), ENT_QUOTES, 'utf-8')."\")'><span>".htmlentities($cat_,ENT_QUOTES,'utf-8')."</span></a></td>";
			
			
			//영문 출력------------------------------------------------------------------------------------------------------
                        $sql= "select tag_name_en FROM tag where tag_name = '".$cat."'";
                        $result = mysql_query_cache( $sql );
			$en_str = str_replace('_', ' ', $result[0]["tag_name_en"]);			
			$view_category .= "<td style='width: 40%; vertical-align:middle;'>";

			
			$view_category .= "<a href='#' onclick='redirectToproblem(\"".htmlentities(urlencode($cat), ENT_QUOTES, 'utf-8')."\")'><span>".htmlentities($en_str,ENT_QUOTES,'utf-8')."</span></a></td>";
			
			
			//------------------------------------------------------------------------------------------------------
			$view_category .= "<td style='width: 10%; vertical-align:middle;'><center><div class=problem-count>".$count_tag[$cat]."</div><center></td>";
			$view_category .= "<td style='width: 10%; vertical-align:middle;'>";		
			$file_name = str_replace("_", " ", $cat);
			if(file_exists("./wiki/".$file_name.".php")){
			    $view_category .= "<center><a class='search-btn' onclick='redirectToWiki(\"".htmlentities(urlencode($cat), ENT_QUOTES, 'utf-8')."\")'><i style='font-size:1.3em;' class='fas fa-search'></i></center></td>";
			}
			else{
			    $view_category .= "</td>";
			}
			array_push($category_arr, $view_category);
			}
	}

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/category.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script>
function redirectToWiki(cat) {
  	var url = 'wiki.php?data=' + cat;
 	window.location.href = url;			
}
function redirectToproblem(cat) {
  	var url = 'problem_list.php?filter1=' + cat;
 	window.location.href = url;
}
</script>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="./categorybtn.css" rel="stylesheet">
</head>
</html>
