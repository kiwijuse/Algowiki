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
<?php $show_title="게시판 - AlgoWiki"; ?>
<?php include("template/$OJ_TEMPLATE/header.php");

if(isset($_GET['data'])){
	require("./board/".$_GET['data'].".php");
}else{
	require("./board/board.php");
}

include("template/$OJ_TEMPLATE/footer.php");

?>
<script>
    function performSearch() {
        var searchValue = document.getElementById("searchInput").value;
	var cateValue = document.getElementById("classification").value;
        window.location.href = "board.php?data=search&cate=" + encodeURIComponent(cateValue) + "&search=" + encodeURIComponent(searchValue);
    }
    function enter_check(e){
	if(e.keyCode == 13){
	performSearch();
	}
    }
function showDropdown() {
    document.getElementById('classification-dropdown').style.display = 'block';
  }

  function hideDropdown() {
    setTimeout(function() {
      document.getElementById('classification-dropdown').style.display = 'none';
    }, 200);
  }

  function selectOption(option) {
    document.getElementById('classification').value = option;
    hideDropdown();
  }
</script>

