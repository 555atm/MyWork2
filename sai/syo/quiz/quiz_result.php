<?php

//// 0.ページが表示された時点で走る必須の処理

//ログインしているかチェック

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

<title>3択クイズ結果[MyPf]</title>
<link rel="stylesheet" href="quiz.css" type="text/css">
</head>
<body>
	<div class="header">
		<h1>3択クイズ!!</h1>
		<h2><?php echo h($_SESSION['member']['user_name']); ?>さんの挑戦結果:</h2>

	</div>

	<div class="header_menu" style="text-align: right">
				<a href="../menu.php" class="btn">TOPへ</a>
				<a href="../logout.php" class="btn">ログアウト</a>
	</div>

	<div id="div1">
		<dl>
			<p class="large"><?php echo h($_SESSION['zenbude']); ?>問中、<?php echo $_SESSION['seikaisu']; ?>問正解でした。</p>
				<?php
					unset($_SESSION['syutsudai']);
					unset($_SESSION['syutsudai_random']);
					unset($_SESSION['quiz_count']);
					unset($_SESSION['zenbude']);
					unset($_SESSION['monme']);
					unset($_SESSION['seikaisu']);
					unset($_SESSION['syutsudai_index']);
					unset($_SESSION['kotae']);
					unset($_SESSION['question']);
					unset($_SESSION['answer']);
					unset($_SESSION['choice_a']);	
					unset($_SESSION['choice_b']);
					unset($_SESSION['choice_c']);
					unset($_SESSION['genre']);
					unset($_SESSION['commentary']);
				?>
			<p class="large">お疲れさまでした!!</p>
			<img src="../images/otsukare.png" width="25%" height="25%"><br>
			<a href="./quiz_genre.php" class="btn">クイズ選択へ戻る</a>
			<br>
			<br>
		</dl>
	</div>
		
	<!-- 
	<div class=div_debug1>
		<p>↓以下は開発用の記述です↓</p>
		<pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
		<pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
		<pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
		<pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
		<pre><?php echo 'var_dump($_POST)の結果→   '; var_dump($_POST); ?></pre>
		<p><?php print_r($quiz);  ?></p>
		<?php
		function console_log($data){
			echo '<script>';
			echo 'console.log('.json_encode($data).')';
			echo '</script>';
		}
		console_log($_SESSION);
		?>
	</div>
	-->

</body>
</html>
