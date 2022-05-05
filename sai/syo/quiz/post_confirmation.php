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
	header('Location: ../login.php');
	exit();
}

// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES);
}

//確認のため、投稿内容を表示する
$quiz = $db->prepare('INSERT INTO quiz_book SET quiz=?, choice_a=?, choice_b=?, choice_c=?, answer=?,
commentary=?, genre=?, created=NOW()');
$quiz->execute(array(
  $_POST['quiz'],
  $_POST['choice_a'],
  $_POST['choice_b'],
  $_POST['choice_c'],
  $_POST['answer'],
  $_POST['commentary'],
  $_POST['genre']


?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title>無題ドキュメント</title>
</head>
<body>

<p>以下の内容で投稿して宜しいですか？</p>

<form action="" method="post">
  <dt>問題(30文字まで)</dt>
    <dd><?php echo h($quiz['quiz']); ?></dd>
    <dt>選択肢A(30文字まで)</dt>
    <dd><?php echo h($quiz['quiz']); ?></dd>
    <dt>選択肢B(30文字まで)</dt>
    <dd><?php echo h($quiz['quiz']); ?></dd>
    <dt>選択肢C(30文字まで)</dt>
    <dd><?php echo h($quiz['quiz']); ?></dd>
    <dt>答え</dt>
    <dd><?php echo h($quiz['quiz']); ?></dd>
    <dt>解説（任意）</dt>
    <dd><?php echo h($quiz['quiz']); ?></dd>
    <dt>ジャンル(30文字まで)</dt>
    <dd><?php echo h($quiz['quiz']); ?></dd>
<input type="submit" value="投稿する">
</form>

</body>
</html>
