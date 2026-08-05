<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항</title>
    <link rel="stylesheet" href="board.css">
</head>
<body>
        <div class="board_title">
            <strong><center>질문 게시판</center></strong>
        </div>
		
 <div class="bt_wrap left">
                <a href="./board.php" class="on">전체</a>
		<a href="./board.php?data=free" class="on">자유</a>
		<a href="./board.php?data=question" class="on">질문</a>
		<a href="./board.php?data=report" class="on">제보</a>
</div>

 <div class="bt_wrap right">
                <a href="write.html" class="on">게시글 작성</a>
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


            </div>
            <div class="board_page">
                <a href="#" class="bt first"><<</a>
                <a href="#" class="bt prev"><</a>
                <a href="#" class="num on">1</a>
                <a href="#" class="num">2</a>
                <a href="#" class="num">3</a>
                <a href="#" class="num">4</a>
                <a href="#" class="num">5</a>
                <a href="#" class="bt next">></a>
                <a href="#" class="bt last">>></a>
            </div>
        </div>
</body>
</html> 
