<!DOCTYPE html>
<head>
  <link rel="icon" href="favicon.ico" type="image/x-icon" sizes="16x16">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=0.5">
<meta property="og:title" content="Algorithm Wiki"> 
	<meta property="og:url" content="http://algowiki.co.kr">
	<meta property="og:url" content="https://algowiki.co.kr">
	<meta property="og:url" content="algowiki.co.kr">
	<meta property="og:type" content="Study">
	<meta property="og:image" content="../image/og_image.png"> 
	<meta property="og:description" content="게임처럼 배우는 알고리즘">	
    <title><?php echo $show_title ?></title>
    
<script src="<?php echo "$OJ_CDN_URL/include/"?>jquery-latest.js"></script>
<style>
    .snow {
      position: fixed;
      width: 10px;
      height: 10px;
      background: #fff;
      border-radius: 100%;
      z-index: 9999; /* Ensure snow is on top of other elements */
      box-shadow: 0 0 3px #808080, 0 0 10px #FFFFFF, 0 0 20px #FFFFFF, 0 0 30px #FFFFFF, 0 0 40px #FFFFFF, 0 0 55px #FFFFFF;
      }
    @media (max-width: 991px) {
        .mobile-only {
                display:block !important;
        }

        .desktop-only {
            display:none !important;
        }
}
.padding {
  background: rgba(255,255,255,0.6);
  box-shadow: inset 5px 5px 20px 0px rgba(255,255,255,0.1);
  border-radius: 20px;
      box-shadow: 10px -10px 20px rgb(255 255 255 / 20%), -10px 10px 20px rgb(255 255 255 / 10%);
  backdrop-filter: blur(7px);
  border-bottom:3px solid rgba(255,255,255,0.4);
  border-right: 3px solid rgba(255,255,255,0.4);
  border-left: 3px solid rgba(255, 255, 255, 0.4);
  /*filter: brightness(1.1);*/
}
</style>
<?php
function isMobileDevice() {
    return preg_match('/(android|iphone|ipod|opera mini|iemobile)/i', $_SERVER['HTTP_USER_AGENT']);
}

if (!isMobileDevice()) {
    // 모바일 디바이스가 아닌 경우에만 스크립트 실행
    ?>
    <div id="snowboard">
        <!--<div v-for="i in 250" :key="i" class="snow" :ref="'snow' + i"></div>-->
    </div>
    <script src="https://unpkg.com/vue@2"></script>
    <script src="https://unpkg.com/gsap@3"></script>
    <!--<script>
        new Vue({
            el: '#snowboard',
            mounted() {
                for (let i = 0; i <=250; i++) {
                    const snow = this.$refs['snow' + i];
                    const baseX = gsap.utils.random(-10, 110);
                    gsap.set(snow, {
                        x: baseX + 'vw',
                        y: -10,
                        opacity: gsap.utils.random(0, 1),
                        scale: gsap.utils.random(0.2, 1.2),
                    });
                    gsap.to(snow, {
                        duration: gsap.utils.random(10, 30),
                        y: '97vh',
                        delay: gsap.utils.random(0, -30),
                        repeat: -1,
                        ease: 'none',
                    });
                    gsap.to(snow, {
                        duration: gsap.utils.random(5, 15),
                        x: baseX + gsap.utils.random(-12, 12) + 'vw',
                        yoyo: true,
                        repeat: -1,
                        delay: gsap.utils.random(-20, -10),
                        ease: 'power1.inOut',
                    });
                }
            },
        });
    </script>-->
    <?php
}
?><?php
	/*$currentUrl = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if(strpos($currentUrl,'www.algowiki.co.kr')!== false){
	$currentUrl = str_replace('www.algowiki.co.kr','algowiki.co.kr',$currentUrl);
	echo "<script>window.location.href = 'https://$currentUrl';</script>";
	}
	else if(strpos($currentUrl,'<SERVER_IP>')!== false){
	$currentUrl = str_replace('<SERVER_IP>','algowiki.co.kr',$currentUrl);
	echo "<script>window.location.href = 'https://$currentUrl';</script>";
	}*/

        require_once(dirname(__FILE__)."/../../include/memcache.php");
        function checkmail(){  // check if has mail
          global $OJ_NAME;
          $sql="SELECT count(1) FROM `mail` WHERE new_mail=1 AND `to_user`=?";
          $result=pdo_query($sql,$_SESSION[$OJ_NAME.'_'.'user_id']);
          if(!$result) return false;
          $row=$result[0];
          //if(intval($row[0])==0) return false;
          $retmsg="<span id=red>(".$row[0].")</span>";
          return $retmsg;
        }

        function get_menu_news() {
            $result = "";
            $sql_news_menu = "select `news_id`,`title` FROM `news` WHERE `menu`=1 AND `title`!='faqs.cn' ORDER BY `importance` ASC,`time` DESC LIMIT 10";
            $sql_news_menu_result = mysql_query_cache( $sql_news_menu );
            if ( $sql_news_menu_result ) {
                foreach ( $sql_news_menu_result as $row ) {
                    $result .= '<a class="item" href="/viewnews.php?id=' . $row['news_id'] . '">' ."<i class='star icon'></i>" . $row['title'] . '</a>';
                }
            }
            return $result;
        }
        $url=basename($_SERVER['REQUEST_URI']);
        $dir=basename(getcwd());
        if($dir=="discuss3") $path_fix="../";
        else $path_fix="";
        if(isset($OJ_NEED_LOGIN)&&$OJ_NEED_LOGIN&&(
                  $url!='loginpage.php'&&
                  $url!='lostpassword.php'&&
                  $url!='lostpassword2.php'&&
                  $url!='registerpage.php'
                  ) && !isset($_SESSION[$OJ_NAME.'_'.'user_id'])){

           header("location:".$path_fix."loginpage.php");
           exit();
        }

        if($OJ_ONLINE){
                require_once($path_fix.'include/online.php');
                $on = new online();
        }

        $sql_news_menu_result_html = "";

        if ($OJ_MENU_NEWS) {
            if ($OJ_REDIS) {
                $redis = new Redis();
                $redis->connect($OJ_REDISSERVER, $OJ_REDISPORT);

                if (isset($OJ_REDISAUTH)) {
                  $redis->auth($OJ_REDISAUTH);
                }
                $redisDataKey = $OJ_REDISQNAME . '_MENU_NEWS_CACHE';
                if ($redis->exists($redisDataKey)) {
                    $sql_news_menu_result_html = $redis->get($redisDataKey);
                } else {
                    $sql_news_menu_result_html = get_menu_news();
                    $redis->set($redisDataKey, $sql_news_menu_result_html);
                    $redis->expire($redisDataKey, 300);
                }

                $redis->close();
            } else {
                $sessionDataKey = $OJ_NAME.'_'."_MENU_NEWS_CACHE";
                if (isset($_SESSION[$sessionDataKey])) {
                    $sql_news_menu_result_html = $_SESSION[$sessionDataKey];
                } else {
                    $sql_news_menu_result_html = get_menu_news();
                    $_SESSION[$sessionDataKey] = $sql_news_menu_result_html;
                }
            }
        }
?>
	

</head>
<?php include(dirname(__FILE__)."/css.php");?>
<?php
        if(!isset($_GET['spa'])){
?>
<style>
.mobile-only {
    display: none;
}
@media (max-width: 768px) {
    .mobile-only {
        display: block;
    }
}

</style>
<body id="MainBg-C" style="position: relative; margin-top: 69px; height: calc(100% - 49px); overflow-y: scroll;background:white;background-size: 100%">
    <div id="page-header" class="ui fixed borderless menu" style="position: fixed; height: 49px; z-index:99999">

        <div id="menu" class="ui stackable mobile ui container computer" style="margin-left:auto;margin-right:auto;">
	
	    
            <a class="header item"  href="/"><img src = "../image/mainicon_santa.png"><span
                    style="font-family: 'Exo 2'; font-size: 1.5em; font-weight: 600; ">Algorithm Wiki</span></a>


            
          <?php
            if(file_exists("moodle"))  // 如果存在moodle目录，自动添加链接
            {
              echo '<a class="item" href="moodle"><i class="group icon"></i>Moodle</a>';
            }
            if(!isset($_GET['cid'])){
          ?>

            <!--<a class="desktop-only item <?php if ($url=="") echo "active";?>" href="/"><i class="home icon"></i> <?php echo $MSG_HOME?></a>-->
	    
	    <a class="item <?php if (strpos($url, "problem_list.php") !== false || strpos($url, "problem.php") !== false || strpos($url, "problemset.php") !== false) echo "active";?>"
                href="<?php echo $path_fix?>problem_list.php"><i class="list icon"></i><?php echo $MSG_PROBLEMS?> </a>
	    <a class="item <?php if (strpos($url, "quiz.php") !== false) echo "active";?>"
                href="<?php echo $path_fix?>quiz.php"><i class="list icon"></i>퀴즈 </a>
            <a class="item desktop-only <?php if ($url=="category.php") echo "active";?>"
                href="<?php echo $path_fix?>category.php"><i class="globe icon"></i>태그/위키 </a>

            <!--<a class="item <?php if ($url=="contest.php") echo "active";?>" href="<?php echo $path_fix?>contest.php<?php if(isset($_SESSION[$OJ_NAME."_user_id"])) echo "?my" ?>" ><i
                    class="trophy icon"></i> <?php echo $MSG_CONTEST?></a>-->
            <a class="item <?php if (strpos($url, "status.php") !== false) echo "active";?>" href="<?php echo $path_fix?>status.php"><i
                    class="tasks icon"></i>채점 현황</a>
            <a class="item desktop-only <?php if ($url=="ranklist.php") echo "active";?> "
                href="<?php echo $path_fix?>ranklist.php"><i class="signal icon"></i> 랭킹</a>
            <!--<a class="item <?php //if ($url=="contest.php") echo "active";?>" href="/discussion/global"><i class="comments icon"></i> 讨论</a>-->
            <!--<a class="desktop-only item <?php if ($url=="faqs.php") echo "active";?>" href="<?php echo $path_fix?>faqs.php"><i
                    class="help circle icon"></i> <?php echo $MSG_FAQ?></a>-->
	    <a class="item <?php if (strpos($url, "board.php") !== false || strpos($url, "post_view.php") !== false || strpos($url, "post_edit.php") !== false || strpos($url, "write.php") !== false) echo "active";?>" href="<?php echo $path_fix?>board.php"><i
                    class="tasks icon"></i>게시판 </a>
	    

	    <a><div class="ui simple dropdown item"><span style="color:black;"><i class="list icon"></i>기타 <i class="dropdown icon"></i>
</span>
		   <div class="menu">
		  	<a class="item mobile-only <?php if ($url=="category.php") echo "active";?>"
                href="<?php echo $path_fix?>category.php" style="display:none;"><i class="globe icon"></i>태그/위키 </a>

			<a class="item mobile-only <?php if ($url=="ranklist.php") echo "active";?> "
                href="<?php echo $path_fix?>ranklist.php" style="display:none;"><i class="signal icon"></i> 랭킹</a>

			<a class="item <?php if ($url=="contest.php") echo "active";?>" href="<?php echo $path_fix?>contest.php<?php if(isset($_SESSION[$OJ_NAME."_user_id"])) echo "?my" ?>" ><i
                    	class="trophy icon"></i> <?php echo $MSG_CONTEST?></a>
			<a class="item <?php if ($url=="faqs.php") echo "active";?>" href="<?php echo $path_fix?>faqs.php"><i
                    	class="help circle icon"></i> <?php echo $MSG_FAQ?></a>
			<a class="item <?php if ($url=="notice.php") echo "active";?>" href="<?php echo $path_fix?>notice.php"><i
			class="ui info icon"></i> 공지사항</a>
			<a class="item <?php if ($url=="terms.php") echo "active";?>" href="<?php echo $path_fix?>terms.php"><i
			class="ui info icon"></i> 이용약관(test)</a>
			<a class="item <?php if ($url=="privacy.php") echo "active";?>" href="<?php echo $path_fix?>privacy.php"><i
			class="ui info icon"></i> 개인정보 처리방침(test)</a>
	    	   </div>
		</div>
	    </a>
	    
	  


              <?php if (isset($OJ_BBS)&& $OJ_BBS){ ?>
                  <a class='item' href="discuss.php"><i class="clipboard icon"></i> <?php echo $MSG_BBS?></a>
              <?php }

            }
                ?>
            <?php if(isset($_GET['cid'])){
                $cid=intval($_GET['cid']);
            ?>
            <a id="" class="item" href="<?php echo $path_fix?>contest.php" ><i class="arrow left icon"></i><?php echo $MSG_CONTEST." 목록"?></a>
            <a id="" class="item active" href="<?php echo $path_fix?>contest.php?cid=<?php echo $cid?>" ><i class="list icon"></i><?php echo "대회 ".$MSG_PROBLEMS?></a>
            <a id="" class="item active" href="<?php echo $path_fix?>status.php?cid=<?php echo $cid?>" ><i class="tasks icon"></i><?php echo "대회 ".$MSG_STATUS?></a>
            <a id="" class="item active" href="<?php echo $path_fix?>contestrank.php?cid=<?php echo $cid?>" ><i class="numbered list icon"></i><?php echo "대회 ".$MSG_RANKLIST?></a>
            <a id="" class="item active" href="<?php echo $path_fix?>contestrank-oi.php?cid=<?php echo $cid?>" ><i class="child icon"></i>OI-<?php echo $MSG_RANKLIST?></a>
                    <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){ ?>
                            <a id="" class="item active" href="<?php echo $path_fix?>conteststatistics.php?cid=<?php echo $cid?>" ><i class="eye icon"></i><?php echo $MSG_STATISTICS?></a>
                    <?php }  ?>
            <?php }  ?>
            <?php echo $sql_news_menu_result_html; ?>
            <div class="right menu">
                <?php if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])) { ?>
                <a href="<?php echo $path_fix?>/userinfo.php?user=<?php echo $_SESSION[$OJ_NAME.'_'.'user_id']?>"
                    style="color: inherit; ">
                   <div class="ui simple dropdown item">
                        <?php echo $_SESSION[$OJ_NAME.'_'.'user_id']; ?>
                        <i class="dropdown icon"></i>
                        <div class="menu">
			    <a class="item" href="userinfo.php?user=<?php echo $_SESSION[$OJ_NAME.'_'.'user_id'];?>"><img id="profile-github-avatar" src="" alt="GitHub Avatar" style="width:18px;height:18px;border-radius:20%;margin-top:-2.5px;margin-right:9px;">프로필</a>
			     <a class="item" href="userinfo.php?tab=quest&user=<?php echo $_SESSION[$OJ_NAME.'_'.'user_id'];?>"><img src="/image/quest_icon.png" style="height:18px;width:auto;margin-top:-2.5px;">퀘스트</a>
                            <a class="item" href="userinfo.php?tab=setting&user=<?php echo $_SESSION[$OJ_NAME.'_'.'user_id'];?>"><i
                                    class="edit icon"></i><?php echo $MSG_REG_INFO;?></a>
                                <?php if ($OJ_SaaS_ENABLE){ ?>
                                <?php if($_SERVER['HTTP_HOST']==$DOMAIN)
                                        echo  "<a class='item' href='http://".  $_SESSION[$OJ_NAME.'_'.'user_id'].".$DOMAIN'><i class='globe icon' ></i>MyOJ</a>";?>
                                <?php } ?>
                            <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){ ?>
                            <a class="item" href="admin/"><i class="settings icon"></i><?php echo $MSG_ADMIN;?></a>
                            <?php }
if(isset($_SESSION[$OJ_NAME.'_'.'balloon'])){
  echo "<a class=item href='balloon.php'><i class='golf ball icon'></i>$MSG_BALLOON</a>";
}
                              if((isset($OJ_EXAM_CONTEST_ID)&&$OJ_EXAM_CONTEST_ID>0)||
                                     (isset($OJ_ON_SITE_CONTEST_ID)&&$OJ_ON_SITE_CONTEST_ID>0)||
                                     (isset($OJ_MAIL)&&!$OJ_MAIL)){
                                      // mail can not use in contest or mail is turned off
                              }else{
                                    $mail=checkmail();
                                    if($mail) echo "<a class='item mail' href=".$path_fix."mail.php><i class='mail icon'></i>$MSG_MAIL$mail</a>";
                              }




                            ?>
			    
                            <a class="item" href="logout.php"><i class="power icon"></i><?php echo $MSG_LOGOUT;?></a>
                        </div>
                    </div>
                </a>
                <?php } else { ?>


                <div class="item">
                    <a class="ui button" style="margin-right: 0.5em; " href="loginpage.php">
                       <?php echo $MSG_LOGIN?>
                    </a>
                    <?php if(isset($OJ_REGISTER)&&$OJ_REGISTER ){ ?>
                    <a class="ui primary button" href="registerpage.php">
                       <?php echo $MSG_REGISTER?>
                    </a>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div style="margin-top: 28px; ">
        <div id="main" class="ui main container">
<?php } ?>
<?php 
$profile_user = $_SESSION[$OJ_NAME.'_'.'user_id'];
$profile_git_image = pdo_query("select git_link from user_link where user_id = '$profile_user'")[0][0];
?>
<script>
var username = '<?php echo $profile_git_image;?>';
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
            document.getElementById('profile-github-avatar').src = data.avatar_url;
        })
        .catch(error => {
	    document.getElementById('profile-github-avatar').src = '/image/mainicon.png';            
        });
</script>