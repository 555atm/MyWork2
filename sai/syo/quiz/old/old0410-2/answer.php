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


<div id="div1">
<h1>3択クイズ！</h1>
	<dl>
		<dt><?php echo h($member['user_name']); ?>さんが挑戦中。</dt>
	</dl>
	<div class="header_menu" style="text-align: right">
			<a href="../index.php">TOPへ</a>
			<a href="../logout.php">ログアウト</a>
	</div>
</div>

<div id="div2">
	<?php
// 受け取った解答から、正解・不正解を判断する
if ($_SESSION['answer'] == $_SESSION['kotae']){
	$result = true;
  echo 'やったぁ！！正解!！';
} else {
	$result = false;
  echo '残念！不正解！';
}
?>

<p>答えは<?php echo $_SESSION['answer']; ?>です</p>

<br>
<br>
<p>解説：<?php echo $_SESSION['commentary']; ?></p>
</div>

<div class="nextquiz">
<form action="quiz.php" method="post">
	<input type="submit" value="次の問題へ">
</form>
<?php
/*『次の問題へ』ボタンが押された場合の処理
 * (まだ問題があれば、問題数を＋１する）
 *(全問解いたら、結果画面へ。）

クイズの全件数を取ってくる
const quizlength = (selectで件数取得)

 if (まだ問題あったら) {
	<form action="quiz.php" method="post">
		<input type="submit" value="次の問題へ">
	</form> 
} else {
	<form action="quiz_result.php" method="post">
		<input type="submit" value="結果画面へ">	
}
*/

?>
</div>

<div class=div_debug1>
	<pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
	<pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
	<pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
	<pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
</div>
</body>
</html>
