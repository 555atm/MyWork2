<?php
/* ①セッションスタート ②ログインしてなければアクセスNGなのでログインページへ移動させる
*/
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
/*
*/

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

<title>3択クイズ→結果画面[私のポートフォリオ]</title>
<link rel="stylesheet" href="quiz.css" type="text/css">
</head>
<body>
<div id="div1">
<h1>3択クイズ：結果</h1>
	<dl>
		<dt><?php echo h($member['user_name']); ?>さんの挑戦結果:</dt>
		<dd><?php echo h($_SESSION['zenbude']); ?>問中、<?php echo $_SESSION['seikaisu']; ?>問正解でした。</dt>
		<dd>お疲れさまでした。</dd>
		<dd>↓問題をふりかえる↓</dd>
		<dd>1問目：問題～～　正解：a　あなたの回答：a</dd>
		<dd>2問目：問題～～　正解：a　あなたの回答：a</dd>
		<dd>3問目：問題～～　正解：a　あなたの回答：a</dd>
	</dl>
	<div class="header_menu" style="text-align: right">
			<a href="../menu.php">TOPへ</a>
			<a href="../logout.php">ログアウト</a>
	</div>
</div>



</body>
</html>
