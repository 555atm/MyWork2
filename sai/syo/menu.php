<?php
session_start();
require('dbconnect.php');
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	// ↑ログインしている＝（idがセッションに記録されいる。かつ、最後の行動から1時間以内であれば。）
	$_SESSION['time'] = time();
	$members = $db->prepare('SELECT * FROM members WHERE id=?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
} else {
	// ログインしていない
	header('Location: login.php');
	exit();
}

// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES);
}



?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="style.css">
<title>メニュー [MyPf]</title>
</head>
<body>
<div class="header">
<h1>私のポートフォリオ</h1>
<div class="header_menu" style="text-align: right">
			<a href="logout.php" class="btn">ログアウト</a>
	</div>
</div>

<div class="wrapp">
  <div class="top_menu">
    <h2>メニュー</h2>
    <label class="menu_btn">お遊び俳句ゲームに挑戦☆</label>
    <a href="./haiku/haiku_gamelevel.php" class="btn">START</a><br><br>
    <label class="menu_btn">お遊び俳句の作品を閲覧(工事中)</label>
    <a href="./haiku/haiku_read.php" class="btn">START</a><br><br>

    <label class="menu_btn">クイズに挑戦！！</label>
    <a href="./quiz/quiz_genre.php" class="btn">START</a><br><br>

    <label class="menu_btn">クイズを投稿する</label>
    <a href="./quiz/quiz_post.php"  class="btn">START</a><br><br>

    <label class="menu_btn">なんでも掲示板【一旦完成】</label>
    <a href="./bbs.php" class="btn">START</a>
    <p>今後追加予定の機能
      ・いろいろ
      ・
    </p>
  </div>
</div>




<div id="slider">
        <ul id="photos">
            <li><img src="./images/sad.png" alt="Sample1" width="130" height="130"></li>
            <li><img src="./images/glad.png" alt="Sample2" width="130" height="130"></li>
        </ul>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            var $photos = $('#photos'),
                $lis    = $('#photos li');

            var li_count = $lis.length;
            var li_width = $lis.width() + parseInt($lis.css('margin-left'), 10) + parseInt($lis.css('margin-right'), 10);

            $photos.css('width', (li_width * li_count) + 'px');

            setInterval(function () {
                $photos.stop().animate({
                    marginLeft: parseInt($photos.css('margin-left'), 10) - li_width + 'px'
                }, function () {
                    $photos.css('margin-left', '0px');
                    $photos.find('li:first').appendTo($photos);
                });
            }, 1500);
        });
</script>

</body>
</html>
