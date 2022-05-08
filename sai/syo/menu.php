<?php
session_start();
require('dbconnect.php');
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	// ↑ログインしている＝（idがセッションに記録されいる。かつ、最後の行動から1時間以内であれば。）
	$_SESSION['time'] = time();
	$members = $db->prepare('SELECT * FROM members WHERE id=?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
  $_SESSION['member'] = $member;
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
<h2><?php echo h($_SESSION['member']['user_name']); ?>さん、ようこそ♪</h2>
<div class="header_menu" style="text-align: right">
			<a href="logout.php" class="btn">ログアウト</a>
	</div>
</div>

<div class="wrapp">
  <div class="top_menu">
    <h2>メニュー</h2>
    <label class="menu_btn">お遊び俳句ゲームに挑戦</label>
    <a href="./haiku/gamelevel.php" class="btn">START</a><br><br>
    <label class="menu_btn">お遊び俳句の作品を閲覧</label>
    <a href="./haiku/haiku_read.php" class="btn">START</a><br><br>

    <label class="menu_btn">クイズに挑戦！！</label>
    <a href="./quiz/quiz_genre.php" class="btn">START</a><br><br>

    <label class="menu_btn">クイズを投稿する</label>
    <a href="./quiz/quiz_post.php"  class="btn">START</a><br><br>

    <label class="menu_btn">しんぷる掲示板</label>
    <a href="./bbs.php" class="btn">START</a>
    <br>
    <br>

  </div>
</div>

<!--
	<div class=div_debug1>
		<p>■今後追加予定■
			・ページング　・ソート機能　・コメント機能　・いいね機能
		</p>  
			
			<p>■デバッグ用（変数の確認■</p>
			<pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
			<pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
			<pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
			<pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
		</div>
	</div>
-->
	
</body>
</html>
