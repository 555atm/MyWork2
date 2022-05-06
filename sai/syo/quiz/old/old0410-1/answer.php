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
/*
*/

// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES);
}

// クイズの回答を受け取る
$_SESSION['kotae'] = $_POST["kotae"];



?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title>3択クイズです</title>
<link rel="stylesheet" href="quiz.css" type="text/css">
</head>
<body>


<div id="div5">

	
	<?php
/*
// 受け取った解答から、正解・不正解を判断する
if ($_SESSION['answer'] == $_SESSION['kotae']){
	$result = true;
  echo '正解!！';
} else {
	$result = false;
  echo '残念！不正解です！';
}
*/
?>

<p>答えは<?php echo $_SESSION['answer']; ?>です</p>

<br>
<br>
<p>解説：<?php echo $_SESSION['commentary']; ?></p>
</div>

<div id="div6">
<input type="submit" value="次の問題へ">

<?php
// 『次の問題へ』ボタンが押されたら場合の処理
// (問題数を＋１する）
?>

</div>

</body>
</html>
