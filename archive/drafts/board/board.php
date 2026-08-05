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
            <strong><center>전체 게시판</center></strong>
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
                <div>
                    <div class="num">-</div>
                    <div class="title"><a href="view.html">정필선 제드에 대한 냉철한 분석</a></div>
                    <div class="writer">algokiwi</div>
                    <div class="date">23.12.14</div>
                    <div class="count">9816</div>
                </div>
		<div>
                    <div class="num">1019</div>
                    <div class="title"><a href="view.html">저는 러지입니다.</a></div>
                    <div class="writer">hun</div>
                    <div class="date">23.12.14</div>
                    <div class="count">1</div>
                </div>
<div>
                    <div class="num">-</div>
                    <div class="title"><a href="view.html">제 3시간을 없앤 정필선을 저격합니다.</a></div>
                    <div class="writer">algokiwi</div>
                    <div class="date">23.12.14</div>
                    <div class="count">181818</div>
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
