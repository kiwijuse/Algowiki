<?php $show_title="$OJ_NAME"; ?>
<?php
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
?>
<?php include("template/$OJ_TEMPLATE/header.php");?>
<script src="template/<?php echo $OJ_TEMPLATE?>/js/textFit.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<style>
#avatar_container:before {
    content: "";
    display: block;
    padding-top: 100%;
}
</style>
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
	.main{
	min-width:693px;
	}
	.title-ex{
	padding: 10px 15px;
        background-color: #444444;
        border-radius: 5px;
        color: #ffffff;
        position: absolute;
        opacity: 0;
        transition: all ease 0.5s;
	font-size:1rem;
	top:57%;
	left:34.7%;
	transform: translate(-50%, -50%);
	}
	
	progress {
        width: 150px; /* 프로그래스 바의 폭을 조절할 수 있습니다. */
        height: 20px; /* 프로그래스 바의 높이를 조절할 수 있습니다. */
    }

    #progressContainer {
      position: absolute;
      width: 83.5%;
      height: 13px;
      left:50%;
      transform: translate(-50%);
      background-color: #eee;
      cursor: pointer;
      border-radius:15px;
    }

    #progressBar {
      position: absolute;
      top: 0;
      left: 0;
      border-radius:15px;
      height: 100%;
      background-color: #4caf50;
    }

    #progressText {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #fff;
      font-size:0.8rem;
    }

    #tooltip {
      display: none;
      position: absolute;
      background-color: #333;
      color: #fff;
      padding: 5px;
      border-radius: 3px;
      bottom: 5%;
      left: 50%;
      transform: translateX(-50%);
    }
   a{
color:#686868;
}
   a:hover {
        color: #6BB9E8; /* Color to change to on hover */
    }
.discord-link{
    position:relative;
    margin-right:7px;
}
.discord-link:hover .tooltips {
    visibility: visible;
    opacity: 1;
}
.tooltips {
    visibility: hidden;
    width: 110px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;
    position: absolute;
    transform: translateX(-50%);
    bottom: -50px;
    left: 40%;
    opacity: 0;
    transition: opacity 0.3s;
}
.discord-mark{
    height:15px;
    width:auto;
}
.padding{
    border:none;
    background:none;
    box-shadow:none;
    backdrop-filter:none;
}
    </style>
    <title>Document</title>
</head>
<div class="main">
    
    <input id="tab1" type="radio" name="tabs" checked>
    <label for="tab1" onclick="changeTab('profile')">프로필</label>

    <input id="tab2" type="radio" name="tabs">
    <label for="tab2" onclick="changeTab('problem')">기록</label>
    <?php 
    if($user==$_SESSION[$OJ_NAME.'_'.'user_id'] || isset($_SESSION[$OJ_NAME.'_'.'administrator'])){//운영자이거나, 본인일 경우에만 보임
    ?>
    <input id="tab3" type="radio" name="tabs">
    <label for="tab3" onclick="changeTab('quest')">퀘스트</label>

    <input id="tab4" type="radio" name="tabs">
    <label for="tab4" onclick="changeTab('shop')">상점</label>

    <input id="tab5" type="radio" name="tabs">
    <label for="tab5" onclick="changeTab('inventory')">인벤토리</label>

    <input id="tab6" type="radio" name="tabs">
    <label for="tab6" onclick="changeTab('setting')">설정</label>

    <?php } ?>

    <div style="margin:10px;"></div>


<?php 
	$sql = "select streak_color,nick_color,comment from users where user_id = '$user'";
	$user_info = pdo_query($sql);
	$streak_color = $user_info[0][0];
	$nick_color = $user_info[0][1];
	$comment = $user_info[0][2];
	$sql = "select count(*) from accept where user_id = '$user'";
	$user_info = pdo_query($sql);
	$solve_count = $user_info[0][0];
	$sql = "select count(*) from privilege where user_id = '$user' and rightstr like 'p%'";
	$user_info = pdo_query($sql);
	$create_count = $user_info[0][0];
	$sql = "select count(*) from post where writer = '$user'";
	$user_info = pdo_query($sql);
	$post_count = $user_info[0][0];
	$sql = "select acc_exp,title,tier,show_git,show_discord,show_blog from uinfo where user_id = '$user'";
	$uinfo_db=pdo_query($sql);
	$exp_point = $uinfo_db[0][0];
	$title = $uinfo_db[0][1];
	$tier = $uinfo_db[0][2];
	$show_git = $uinfo_db[0][3];
	$show_discord = $uinfo_db[0][4];
	$show_blog = $uinfo_db[0][5];
	$sql = "select content from title_content where title = '$title'";
	$title_content = pdo_query($sql)[0][0];	
	$sql= "select * from user_link where user_id = '$user'";
	$link = pdo_query($sql);
	$git = $link[0][1];
	$discord = $link[0][2];
	$blog = $link[0][3];


	$l = 1;
	$r = 30000;
	$idx = 0;
	while($l <= $r)
	{
   	    $mid = intval(($l + $r) / 2);
   	    if($exp_total[$mid] > $exp_point) $r = $mid - 1;
   	    else{
     	 	$idx = $mid;
     	 	$l = $mid + 1;
   		}
	}
	$need_exp = $exp_point - $exp_total[$idx];

	$sql="select * from ( SELECT user_id, RANK() OVER (ORDER BY acc_exp DESC, user_id ASC) AS ranking FROM uinfo )ranking where ranking.user_id = '$user'";
	$uinfo_db=pdo_query($sql);
	$lv_rank = $uinfo_db[0][1];
?>


<div class="padding" style="border:none;background:none;">
<div class="ui grid">
    <div class="row">
        <div class="flex-container" style = "width:100%;height:335px;display: flex; border-radius: 10px;border: 1px solid #E2E3E4;margin-bottom: 50px;position:relative;">
            <div style="width:23%;margin-bottom: 0px; border-right: 1px solid #E2E3E4;position:relative" id="user_card">		             
                <center><img id="github-avatar" src="" alt="GitHub Avatar" style="margin-top:60px; width:100px;height:100px;border-radius:20%;"></center>

                <div style="text-align:center;margin-bottom:0px;margin-top:25px;">		
                    <div class="header" style="color:<?php echo $nick_color;?>; font-size:1.5rem;font-weight:600;"><?php echo $user?></div>
		    <div class="header" style="margin-top:13px;font-size:1.3rem;font-weight:600;"><?php echo $tier; ?></div>
		    <div class="header" style="margin-top:7px;font-size:1.2rem;font-weight:600;"><?php echo $idx+1;?> LV</div>
		    
		     
		</div>
		<div id="progressContainer"
     			onmouseover="showTooltip(<?php echo $need_exp; ?>, <?php echo $exp_a[$idx+1]; ?>)"
     			onmouseout="hideTooltip()">
  		    <div id="progressBar" style="width:<?php echo ($need_exp / $exp_a[$idx+1]) * 100; ?>%;"></div>
  		    <div id="progressText"><?php echo number_format($need_exp/$exp_a[$idx+1]*100,1); ?>%</div>		    
		</div>
                <div id="tooltip"></div>
		<div style="float:right;margin-top:30px;margin-right:10px;display:flex;">
		    <?php if($show_blog == 0 && !!$blog){
		    echo '<a href="'.$blog.'" target="_blank" style="margin-right:7px;"><img src="/image/blog-mark.png" style="height:20px;width:auto;"></a>';
		    } if($show_discord == 0 && !!$discord){
		    echo '<a href="https://discord.com/users/'.$discord.'" target="_blank" class="discord-link"><img src="/image/discord-mark.png" class="discord-mark"><span class="tooltips">브라우저에서만 동작합니다.</span></a>';
		    } if($show_git == 0 && !!$git){
		    echo '<a href="https://github.com/'.$git.'" target="_blank"><img src="/image/github-mark.png" style="height:20px;width:auto;"></a>';
		    } 
		    ?>
		</div>
            </div>
	 	<div class="title-ex"><?php echo $title_content; ?></div>
	    <div style="width:77%; margin-top: 0; margin-bottom: 0px; font-weight:700;">		
		<div style="height:33%;width:100%;border-bottom: 1px solid #E2E3E4;">
			<div style="height:30%;"></div>
			<div class="flex-container" style="height:25%;margin-left:30px;font-size:1.5rem;margin-left:30px; margin-right:30px;margin-bottom:0px; display:flex;text-align: center;">
				<div style="width:25%;">
				<a href="userinfo.php?user=<?php echo $user;?>&tab=problem&category=맞은 문제"><?php echo $solve_count; ?></a>
				</div>
				<div style="width:25%; ">
				?
				</div>
				<div style="width:25%;">
				<a href="userinfo.php?user=<?php echo $user;?>&tab=problem&category=만든 문제"><?php echo $create_count; ?></a>
				</div>
				<div style="width:25%;">
				<a href="board.php?data=search&cate=작성자&dataValue=전체&search=<?php echo $user;?>"><?php echo $post_count; ?></a>
				</div>
			</div>

			<div class="flex-container"style="margin-left:30px; margin-right:30px; display:flex;text-align: center;">
				<div style="width:25%">
				맞은 문제
				</div>
				<div style="width:25%">
				기여한 문제
				</div>
				<div style="width:25%">
				만든 문제
				</div>
				<div style="width:25%">
				작성한 게시글
				</div>
			</div>			
		</div>

		<div style="height:33%;width:100%;border-bottom: 1px solid #E2E3E4;">
			<div style="height:30%;"></div>
			<div class="flex-container" style="height:25%;margin-left:30px;font-size:1.5rem;margin-left:30px; margin-right:30px; display:flex;text-align: center;">
				<div style="width:25%;">
				<div class ="title-main" style="cursor: pointer;"><?php echo $title; ?></div>				
				</div>
				<div style="width:25%; ">
				?
				</div>
				<div style="width:25%;">
				?
				</div>
				<div style="width:25%;">
				<?php echo $lv_rank; ?>
				</div>
			</div>

			<div class="flex-container"style="margin-left:30px; margin-right:30px; display:flex;text-align: center;">
				<div style="width:25%">
				칭호
				</div>
				<div style="width:25%">
				종합 랭킹
				</div>
				<div style="width:25%">
				실력 랭킹
				</div>
				<div style="width:25%">
				LV랭킹
				</div>
			</div>			
		</div>

		<div style="height:33%;width:100%;">
			<div style="height:10%;"></div>
			<div style="margin-left:30px; margin-right:30px;height:90%;font-size:18px;">
				한줄 소개
				<div style="padding:20px;font-size:16px;font-weight:500;">
				<?php echo $comment;?>
				</div>
			</div>
			
		</div>


	    </div>

        </div>


         <div style= "width:100%;">
                <div style = "width:100%; margin-bottom: 50px;">                    
                        <div class="column">
                            <h4 class="ui top attached block header">제출 통계</h4>
                            <div class="ui bottom attached segment">
                                <div id="sub_date_chart" style="width:100%;height:210px"></div>
                                <!-- <a href="/status.php?user_id=<?php echo $user?>"><i class="search icon"></i>제출 기록</a> -->
                            </div>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="column">
                            <h4 class="ui top attached block header">통계</h4>
                            <div class="ui bottom attached segment">
                                <div class="ui grid">
                                    <div class="row">
                                        <div id="pie_chart_legend" class="six wide column"></div>
                                        <div class="ten wide column">
                                            <canvas id="pie_chart"></canvas>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                  
                    
                        <div class="column">
                            <h4 class="ui top attached block header">
                                푼 문제
                            </h4>
                            <div class="ui bottom attached segment">
				<?php
				$sql = "select distinct problem_id from accept where user_id = '$user' order by problem_id asc";
				$result = pdo_query($sql);
				foreach($result as $row){
					$divs = "<a href=problem.php?id=".$row["problem_id"]." class = 'ui basic label' style = 'margin-bottom:5px;margin-left:5px;'>".$row["problem_id"]."</a>";
					if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){//로그인 했으면											
						$login_user = $_SESSION[$OJ_NAME.'_'.'user_id'];	
						$sql= "select result from solution where user_id = '$login_user' and problem_id = '".$row["problem_id"]."' and result > 4";											
						$false_problem = pdo_query($sql);
						if($false_problem){
							$divs = "<a href=problem.php?id=".$row["problem_id"]." class = 'ui red basic label' style = 'margin-bottom:5px;margin-left:5px;'>".$row["problem_id"]."</a>";
						}
						$sql= "select * from accept where user_id = '$login_user' and problem_id = '".$row["problem_id"]."'";											
						$true_problem = pdo_query($sql);
						if($true_problem){
							$divs = "<a href=problem.php?id=".$row["problem_id"]." class = 'ui green basic label' style = 'margin-bottom:5px;margin-left:5px;'>".$row["problem_id"]."</a>";
						}

					}
					echo $divs;
				}					
				?>

                                <!--<script language='javascript'>
                                    function p(id, c) {
                                        if(c>0)document.write("<a href=problem.php?id=" + id + " class=\"ui green basic label\" id=\"show-problem-id\" style=\"margin-bottom:5px;\">" + id + " </a>");
                                        else document.write("<a href=problem.php?id=" + id + " class=\"ui basic label\" id=\"show-problem-id\" style=\"margin-bottom:5px;\">" + id + " </a>");
                                    }

                                    function ptot(len) {
                                        document.write("<div style='text-align:right;margin-bottom:10px'><div class='ui green small horizontal statistic'><div class='value'>" + len + "</div><div class='label'>AC</div></div></div>")
                                    }
                                    <?php
                                    $sql = "SELECT `problem_id`,count(1) from solution where `user_id`=? and result=4 and problem_id != 0 group by `problem_id` ORDER BY `problem_id` ASC";
                                    if ($ret = pdo_query($sql, $user)) {
                                        $len = count($ret);
                                        echo "ptot($len);";
                                        foreach ($ret as $row){
                                            if (isset($acc_arr[$row['problem_id']]))
                                                echo "p($row[0],$row[1]);";
                                            else
                                                echo "p($row[0],0);";
                                        }
                                    }
                                    ?>
                                </script>-->
                            </div>

                        </div>
                    
		    
                        <div class="column">
                            <h4 class="ui top attached block header">풀지 못한 문제</h4>
                            <div class="ui bottom attached segment">

				<?php
				$sql = "SELECT DISTINCT s.problem_id FROM solution s LEFT JOIN accept a ON s.problem_id = a.problem_id AND s.user_id = a.user_id WHERE s.user_id = '$user' and a.problem_id IS NULL";
				$result = pdo_query($sql);
				foreach($result as $row){
					$divs = "<a href=problem.php?id=".$row["problem_id"]." class = 'ui basic label' style = 'margin-bottom:5px;margin-left:5px;'>".$row["problem_id"]."</a>";
					if (isset($_SESSION[$OJ_NAME.'_'.'user_id'])){//로그인 했으면											
						$login_user = $_SESSION[$OJ_NAME.'_'.'user_id'];	
						$sql= "select result from solution where user_id = '$login_user' and problem_id = '".$row["problem_id"]."' and result > 4";											
						$false_problem = pdo_query($sql);
						if($false_problem){
							$divs = "<a href=problem.php?id=".$row["problem_id"]." class = 'ui red basic label' style = 'margin-bottom:5px;margin-left:5px;'>".$row["problem_id"]."</a>";
						}
						$sql= "select * from accept where user_id = '$login_user' and problem_id = '".$row["problem_id"]."'";											
						$true_problem = pdo_query($sql);
						if($true_problem){
							$divs = "<a href=problem.php?id=".$row["problem_id"]." class = 'ui green basic label' style = 'margin-bottom:5px;margin-left:5px;'>".$row["problem_id"]."</a>";
						}

					}
					echo $divs;
				}					
				?>

                                <!--<script language='javascript'>
                                    function p(id, c) {
                                        document.write("<a href=problem.php?id=" + id + " class=\"ui basic label\" id=\"show-problem-id\">" + id +
                                            " </a>");
                                    }
                                    <?php
                                    $sql = "SELECT `sol`.`problem_id`, count(1) from solution sol where `sol`.`user_id`=? and `sol`.`result`!=4 and sol.problem_id != 0 and not exists (select * from solution s where s.user_id=sol.user_id and s.problem_id = sol.problem_id and s.result = 4) group by `sol`.`problem_id` ORDER BY `sol`.`problem_id` ASC";
                                    if ($result = pdo_query($sql, $user)) {
                                        foreach ($result as $row)
                                            echo "p($row[0],$row[1]);";
                                    }
                                    ?>
                                </script>-->
                            </div>
                        </div>
                    

                          <!--              <div class="row">
                        <div class="column">
                            <h4 class="ui top attached block header">
                               近期登录日志

                            </h4>
                            <div class="ui bottom attached segment">

					<?php
					if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
					?><table border=1 class='ui table'>
					<thead><tr class=toprow><th>UserID</th><th>Password</th><th>IP</th><th>Time</th></tr></thead>
					<tbody>
					<?php
					$cnt=0;
					foreach($view_userinfo as $row){
					        if ($cnt)
					                echo "<tr class='oddrow'>";
					        else
					                echo "<tr class='evenrow'>";
					        for($i=0;$i<count($row)/2;$i++){
					                echo "<td>";
					                echo "\t".$row[$i];
					                echo "</td>";
					        }
					        echo "</tr>";
					        $cnt=1-$cnt;
					}
					?>
					</tbody>
					</table>
					<?php
					}
					?>
                             </div>
                        </div>
                    </div> -->

            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(function() {
        $('#user_card .image').dimmer({
            on: 'hover'
        });
        var pie = new Chart(document.getElementById('pie_chart').getContext('2d'), {
            aspectRatio: 1,
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        <?php
                        foreach ($view_userstat as $row) {
                            echo $row[1] . ",\n";
                        }
                        ?>
                    ],
                    backgroundColor: [
                        "#32CD32",
                        "#FA8072",
                        "#DC143C",
                        "#FF9912",
                        "#8A2BE2",
                        "#4169E1",
                        "#DB7093",
                        "#082E54",
                        "#FFFF00",
                    ]
                }],
                labels: [
                    <?php
                    foreach ($view_userstat as $row) {
                        echo "\"" . $jresult[$row[0]] . "\",\n";
                    }
                    ?>
                ]
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                },
                legendCallback: function(chart) {
                    var text = [];
                    text.push(
                        '<ul style="list-style: none; padding-left: 20px; margin-top: 0; " class="' +
                        chart.id + '-legend">');

                    var data = chart.data;
                    var datasets = data.datasets;
                    var labels = data.labels;

                    if (datasets.length) {
                        for (var i = 0; i < datasets[0].data.length; ++i) {
                            text.push(
                                '<li style="font-size: 15px; color: #666; margin:10px 20px"><span style="width: 12px; height: 12px; display: inline-block; border-radius: 50%; margin-right: 5px; background-color: ' +
                                datasets[0].backgroundColor[i] + '; "></span>');
                            if (labels[i]) {
                                text.push(labels[i]);
                                text.push(' : ' + datasets[0].data[i]);
                            }
                            text.push('</li>');
                        }
                    }

                    text.push('</ul>');
                    return text.join('');
                }
            },
        });
        document.getElementById('pie_chart_legend').innerHTML = pie.generateLegend();
    });

// 사용자 이름을 지정
    var username = '<?php echo $git;?>';
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
            document.getElementById('github-avatar').src = data.avatar_url;
        })
        .catch(error => {
	    document.getElementById('github-avatar').src = '/image/mainicon.png';            
        });

//프로그래스 바
	function showTooltip(needExp, maxExp) {
    var progressBar = document.getElementById('progressBar');
    var tooltip = document.getElementById('tooltip');

    // 툴팁 내용 설정
    var tooltipText = needExp + ' / ' + maxExp;
    tooltip.innerText = tooltipText;

    // 툴팁 위치 설정
    tooltip.style.display = 'block';
  }

  function hideTooltip() {
    var tooltip = document.getElementById('tooltip');
    tooltip.style.display = 'none';
  }
$(document).ready(function(){
    $(".title-main").hover(function(){
      $(".title-ex").css("opacity", "1");
    }, function(){
      $(".title-ex").css("opacity", "0");
    });
  });
function changeTab(tabId) {
    window.location.href = "userinfo.php?user=<?php echo $user;?>&tab=" + tabId;
}
window.onload = function() {
    // 프로필 탭에 대한 라디오 버튼을 찾아서 checked 속성을 추가
    document.getElementById('tab1').checked = true;
};
</script>

<?php 
$sub_data = [];
$max_count = 0;
$sql = "SELECT DATE(in_date),count(*) FROM solution WHERE user_id=? AND  in_date >= DATE_SUB(CURDATE(),INTERVAL 1 YEAR) AND result < 13 GROUP BY DATE(in_date);";
$ret = pdo_query($sql, $user);
foreach ($ret as $row) {
    array_push($sub_data, [$row[0], (int)$row[1]]);
    $max_count = max($max_count, (int)$row[1]);
}
// $max_count = ceil($max_count / 100) * 100;
date_default_timezone_set('PRC');
$today = date('Y-m-d', time());
$beg_time = date('Y-m-d', strtotime("-10 month"));
// echo json_encode($sub_data, false);
?>
<script  src="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE"?>/js/echarts.min.js"></script>
<!--<script src="https://cdn.staticfile.org/echarts/5.1.2/echarts.min.js"></script>-->

<script type="text/javascript">
    var chartDom = document.getElementById('sub_date_chart');
    var myChart = echarts.init(chartDom);
    var option;
    var today = new Date();
    option = {
        title: {
            top: 30,
            left: 'center',
        },
        tooltip: {
            formatter: function(params) {
                return params.value[0] + '<br>푼 문제수：' + params.value[1];
            }
        },
        visualMap: {
            min: 0,
            max: <?php echo $max_count ?>,
            show: false,
            type: 'piecewise',
            orient: 'horizontal',
            left: 'center',
            top: 10,
            inRange: {
                color: <?php echo $streak_color;?>
            }
        },
        calendar: {
            top: 30,
            left: 40,
            right: 30,
            cellSize: [20, 20],
            range: ['<?php echo $beg_time ?>', '<?php echo $today ?>'],
            itemStyle: {
                borderWidth: 0.3,
            },
            lineStyle: {
                color: '#D10E00',
                width: 1,
                opacity: 1,

            },
            yearLabel: {
                show: false
            },
            dayLabel: {
                firstDay: 1,
                nameMap: ['일', '월', '화', '수', '목', '금', '토'],
    		margin: '8px'
            },
            monthLabel: {
                nameMap: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
    		margin: 15,
                fontSize: 14,
                color: 'gray'
            },
            splitLine: {
                show: false
            },
            
        },
        series: {
            name: '커밋횟수',
            type: 'heatmap',
            coordinateSystem: 'calendar',
            data: <?php echo json_encode($sub_data, false); ?>,
        }
    };

    option && myChart.setOption(option);
</script>


</div>


<?php include("template/$OJ_TEMPLATE/footer.php");?>