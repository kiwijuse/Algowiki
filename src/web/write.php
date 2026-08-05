<?php
$cache_time = 30;
$OJ_CACHE_SHARE = false;
require_once( './include/cache_start.php' );
require_once( './include/db_info.inc.php' );
require_once( './include/memcache.php' );
require_once( './include/setlang.php' );
require_once( './include/bbcode.php' );
$view_title = "Welcome To Online Judge";
$result = false;
?>
<?php $show_title="게시글 작성 - AlgoWiki"; ?>
<?php include("template/$OJ_TEMPLATE/header.php");

if (!isset($_SESSION[$OJ_NAME.'_'.'user_id'])){/*로그인 안했을 시 로그인 페이지로 이동*/
	echo "<script>location.href='./loginpage.php'</script>";	
}?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Post-Writing Page</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
  /* Additional styles if needed */
  #classification-dropdown {
    display: none;
  }
.shadow {
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .appearance-none {
      appearance: none;
    }
    .border {
      border: 1px solid #ddd;
    }
    .rounded {
      border-radius: 5px;
    }
    .w-full {
      width: 100%;
    }
    .py-2 {
      padding-top: 0.5rem;
      padding-bottom: 0.5rem;
    }
    .px-3 {
      padding-left: 0.75rem;
      padding-right: 0.75rem;
    }
    .text-gray-700 {
      color: #4a5568;
    }
    .leading-tight {
      line-height: 1.25;
    }
    .focus\:outline-none:focus {
      outline: none;
    }
    .focus\:shadow-outline:focus {
      box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
    }

</style>
</head>
<body class="bg-gray-100">


    <h1 class="text-xl mb-4 font-semibold text-gray-700">게시글 작성</h1>
	<form method=POST action=post_add.php>
    <div class="mb-4">
	
      <label class="block text-gray-700 text-sm font-bold mb-2" for="post-title">
        제목
      </label>
      <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="post-title" name="title" type="text" placeholder="제목 입력">
    </div>
    <div class="mb-4 flex">
      <div class="relative w-1/4 mr-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="classification">
          분류
        </label>
        <input readonly class="shadow appearance-none border rounded width:30px py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="classification" name="category" type="text" placeholder="분류" value="자유" onfocus="showDropdown()" onblur="hideDropdown()">
<div id="classification-dropdown" class="absolute bg-white border rounded w-full z-10">
  <!-- Dropdown content -->
  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectOption('자유')">자유</a>
  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectOption('질문')">질문</a>
  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="selectOption('제보')">제보</a>
</div>
      </div>
    </div>
<div class="mb-4 flex">
<div class="relative w-1/2 mr-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="numbers">
          문제 번호(숫자만 기입)
        </label>
        <input class="shadow appearance-none border rounded min-width:30px; py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="problem_id" type="text" placeholder="기입 안해도 상관 X">
      </div>
</div>
    <div class="mb-6">
      <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
        내용
      </label>
      <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="content" name="content" placeholder="내용 입력" rows="30"></textarea>
    </div>
    <div class="flex items-center justify-between" style="float: right;">
      <input type=submit value='저장' name=submit class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button" >
    </div>
	</form>

<script>
  function showDropdown() {
    document.getElementById('classification-dropdown').style.display = 'block';
  }

  function hideDropdown() {
    // 드랍다운 숨기기
    setTimeout(function() {
      document.getElementById('classification-dropdown').style.display = 'none';
    }, 200);
  }

  function selectOption(option) {
    // 선택된 옵션에 대한 처리
    console.log('선택된 옵션:', option);

    // 선택된 옵션을 입력 상자에 설정
    document.getElementById('classification').value = option;

    // 드랍다운 숨기기
    hideDropdown();
  }
  function saveContent() {
    // Implement save functionality or show a message
    alert('Your post has been saved!');
  }
</script>
</body>
</html>

<?php

include("template/$OJ_TEMPLATE/footer.php");

?>
