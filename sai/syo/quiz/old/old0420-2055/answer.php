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


//↓開発用。あとで消す↓
echo '【開発用】出題中のクイズIDは' . $_SESSION['syutsudai'][$_SESSION['syutsudai_index']] . 'で、全' . $_SESSION['zenbude'] . '問中の、第' . $_SESSION['monme'] . '問目、$_SESSIONのインデックスは' . $_SESSION['syutsudai_index'] .'です';

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
			<a href="../menu.php">TOPへ</a>
			<a href="../logout.php">ログアウト</a>
	</div>
</div>

<div id="div2">
	<?php
// 受け取った解答から、正解・不正解を判断する
if ($_SESSION['answer'] == $_SESSION['kotae']){
	$result = true;
  echo 'やったぁ！！正解!！';
	$_SESSION['seikaisu']++;
} else {
	$result = false;
  echo '残念！不正解！';
}
?>

<p>答えは<?php echo $_SESSION['answer']; ?>です</p>

<br>
<br>
<p>解説：<?php echo $_SESSION['commentary']; ?></p>
<p>現在、全<?php echo $_SESSION['zenbude']; ?>問中の<?php echo $_SESSION['monme'];?>問目です</p>
</div>

<?php
	/*問題まだあるなら『次の問題ボタン』を表示し、
	*最終問題なら代わりに『クイズ結果へ』を表示する
	*/
	/* 三項演算子にしたほうが良いのでは？↓【例】↓
	* ($_SESSION['quiz_count'] > $_SESSION['monme']) ? true : false;
	*/
?>
	
	<?php if( $_SESSION['zenbude'] > $_SESSION['monme']): ?>
		<div class="to_next">
			<form action="quiz.php" method="post">
				<input type="hidden" name="monme" value="<?php echo ($_SESSION['monme'] +1); ?>">
				<input type="hidden" name="next_quiz" value="1">
				<input type="submit" value="次の問題へ">
			</form>
		</div>
		
		<?php else: ?>
			<div class="to_result">
				<form action="quiz_result.php" method="post">
					<input type="submit" value="クイズ結果へ">
				</form>
			</div>	
		<?php endif; ?>
								


<div class=div_debug1>
	<pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
	<pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
	<pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
	<pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
</div>
</body>
</html>
