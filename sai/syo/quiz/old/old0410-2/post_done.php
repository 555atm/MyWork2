<?php
session_start();
require('../dbconnect.php');
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

<title>3択クイズ投稿完了</title>
<link rel="stylesheet" href="quiz.css" type="text/css">
</head>
<body>

<div id="div1">
  <h1>[3択クイズ] 投稿完了画面</h1>
  <dl>
			<dt><?php echo h($member['user_name']); ?>さん、クイズ投稿完了しました。ありがとうございます。</dt>
</div>

<div id="div2">
  <a href="/quiz_post.php">クイズ投稿画面に戻る</a>
  <a href="../index.php">ログインTOPに戻る</a>
</div>




</body>
</html>
