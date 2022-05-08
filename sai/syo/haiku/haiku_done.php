<?php

//// 0.ページが表示された時点で走る必須の処理

//ログイン中かチェック
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

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>お遊び俳句ゲーム_投稿完了 [MyPf]</title>
<link rel="stylesheet" href="haiku.css">
</head>
<body>

	<div class="wrap">
		<div class="header">
			<h1>お遊び俳句ゲーム<h1>
			<h1>投稿完了<h1>	
			<div class="header_menu" style="text-align: right">
							<a href="../menu.php" class="btn">TOPへ</a>
							<a href="logout.php" class="btn">ログアウト</a>
			</div>
		</div>

		<div class="norules">
			<br>
			<br>
			<br>
			<br>

		<?php
			// 投稿をDBに記録する
			if (!empty($_SESSION)) {
				if ($_SESSION['kamigo'] != '') {
					$haikus = $db->prepare('INSERT INTO haiku SET kamigo=?, nakashichi=?, shimogo=?, kami_random=?, naka_random=?,
					shimo_random=?, member_id=?, created=NOW()');
					$haikus->execute(array(
						$_SESSION['kamigo'],
						$_SESSION['nakashichi'],
						$_SESSION['shimogo'],
						$_SESSION['kami_random'],
						$_SESSION['naka_random'],
						$_SESSION['shimo_random'],
						$_SESSION['id']
					));
					echo '<p>投稿を完了しました。</p>';
					// unset($_SESSION[’value’]);だと動くけど『今後良くない』とwarning出てしまうので、
					//  $_SESSION[’value’] = array(); の形に修正した。
					$_SESSION['kami_random'] = array();
					$_SESSION['naka_random'] = array();
					$_SESSION['shimo_random'] = array();
					$_SESSION['kamigo'] = null;
					$_SESSION['nakashichi'] = null;
					$_SESSION['shimogo'] = null;
					$_SESSION['gamelevel'] = null;
				} else {
					echo '<p>投稿内容が空です。やり直してください</p>';
					echo '<a href=”gamelevel.php” class="btn">メニューへ戻る</a>';
				}
			}
		?>

		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		</div>
	</div>

	<div class="footer">
		<p>　　　　　　　　　　</p>
	</div>

	<!--
	<div class=div_debug1>
		<p>↓以下は開発用の記述です↓</p>
		<pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
		<pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
		<pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
		<pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
	</div>
	-->

</body>
</html>
