<?php
if(isset($_GET['page'])){
	$page = $_GET['page'];
	if($page < 1){
		$page =1;
	}
}else{
	$page=1;
}
$search = $_GET['search'];
$search = str_replace(' ', '', $search);
$cate = $_GET['cate'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <link rel="stylesheet" href="board.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
</head>
<body>

        <div class="board_title">
            <strong><center>게시판 검색</center></strong>
        </div>
		
 <div class="bt_wrap left">
                <a href="./board.php" class="on">전체</a>
		<a href="./board.php?data=free" class="on">자유</a>
		<a href="./board.php?data=question" class="on">질문</a>
		<a href="./board.php?data=report" class="on">제보</a>
</div>
<div class="bt_wrap right">
                <a href="write.php" class="on">게시글 작성</a>
</div>
<div class="board_wrap"></div>
        <div class="board_list_wrap">
            <div class="board_list">

                <div class="top">
                    <div class="num">문제 번호</div>
                    <div class="title">제목</div>
                    <div class="writer">글쓴이</div>
                    <div class="date">작성일</div>
                    <div class="count">댓글 수</div>
                </div>
		<?php
		$sql = "SELECT `news_id`,`user_id`,`title`,`time`,`defunct`,`menu` FROM `news` ORDER BY `news_id` DESC LIMIT 2";
		$result = pdo_query($sql);
		foreach ($result as $row) {
    			echo '<div><div class="num">[공지]</div>';
    			echo '<div class="title"><a href="viewnews.php?id='.$row["news_id"].'">'.$row["title"].'</a></div>';
    			echo '<div class="writer"><a href="userinfo.php?user='.$row['user_id'].'">'.$row['user_id'].'</a></div>';
    			$Date = date('y.m.d', strtotime($row['time']));
    			echo '<div class="date">'.$Date.'</div>';
    			echo '<div class="count">-</div></div>';		
		}


		$offset = ($page - 1) * 18;
		if($cate=='제목'){
		$sql = "SELECT `post_id`, `problem_id`, `title`, `writer`, `created_time` FROM `post` where instr(replace(title, ' ', ''), '$search') > 0 ORDER BY `post_id` DESC LIMIT $offset, 18";
                }
		else if($cate=='내용'){
		$sql = "SELECT `post_id`, `problem_id`, `title`, `writer`, `created_time` FROM `post` where instr(replace(content, ' ', ''), '$search') > 0 ORDER BY `post_id` DESC LIMIT $offset, 18";
		}
		else if($cate=='작성자'){
		$sql = "SELECT `post_id`, `problem_id`, `title`, `writer`, `created_time` FROM `post` where instr(replace(writer, ' ', ''), '$search') > 0 ORDER BY `post_id` DESC LIMIT $offset, 18";
		}
		else{
		$sql = "SELECT `post_id`, `problem_id`, `title`, `writer`, `created_time` FROM `post` where instr(replace(problem_id, ' ', ''), '$search') > 0 ORDER BY `post_id` DESC LIMIT $offset, 18";
		}
		$result = pdo_query($sql);
		foreach ($result as $row) {
			if(isset($row["problem_id"])){
				echo '<div><div class="num"><a href="problem.php?id='.$row["problem_id"].'">'.$row["problem_id"].'</a></div>';
			}
    			else{
				echo '<div><div class="num"><a>-</a></div>';
			}
    			echo '<div class="title" style="overflow-wrap: break-word;"><a href="post_view.php?id='.$row["post_id"].'">'.$row["title"].'</a></div>';
    			echo '<div class="writer"><a href="userinfo.php?user='.$row['writer'].'">'.$row['writer'].'</a></div>';
    			$Date = date('y.m.d', strtotime($row['created_time']));
    			echo '<div class="date">'.$Date.'</div>';
			$sqls = "select count(*) from comment where post_id = ".$row["post_id"]."";
			$results = pdo_query($sqls);
			echo '<div class="count">'.$results[0][0].'</div>';
			echo '</div>';
		}
		$sql = "SELECT count(post_id) FROM post";
		$result = pdo_query($sql);
		$last_page = intval(ceil($result[0][0]/18));
		$next_page = $page + 1;
		if($next_page > $last_page){
		$next_page = $last_page;
		}
		?>
            </div>
            <div class="board_page">
                <a href="board.php?data=search&search=<?php echo $search; ?>" class="bt first"><<</a>
                <a href="board.php?page=<?php echo $page - 1; ?>&data=search&search=<?php echo $search; ?>" class="bt prev"><</a>		
                <a href="board.php?page=<?php echo $next_page; ?>&data=search&search=<?php echo $search; ?>" class="bt next">></a>
                <a href="board.php?page=<?php echo $last_page; ?>&data=search&search=<?php echo $search; ?>" class="bt last">>></a>
            </div>

	    <div style="padding:10px;"></div>
	<center>
	<div class="search-box">
	<input readonly class="shadow appearance-none border rounded width:30px py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow text-center" style="width:80px; " id="classification" name="category" type="text" placeholder="분류" value="제목" onfocus="showDropdown()" onblur="hideDropdown()">
<div id="classification-dropdown" class="absolute bg-white border rounded w-full z-10">
<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectOption('제목')">제목</a>
  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectOption('내용')">내용</a>
  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectOption('작성자')">작성자</a>
  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectOption('문제번호')">문제번호</a>
</div>
 <div class="vertical-line"></div>
    	<input type="text" class="search-txt" id="searchInput" placeholder="← 분류 선택후 검색" onKeypress="enter_check(event)">
	
    	<a class="search-btn" href="javascript:void(0);" onclick="performSearch()">
        <i class="fas fa-search"></i>
    	</a>
	</div>	
	</center>


        </div>
</body>
</html> 
