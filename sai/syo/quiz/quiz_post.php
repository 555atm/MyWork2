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



	// 投稿を記録する
	if (!empty($_POST)) {
		if ($_POST['question'] != '') {
			$questions = $db->prepare('INSERT INTO quiz_book SET question=?, choice_a=?, choice_b=?, choice_c=?, answer=?,
			commentary=?, genre=?, created=NOW()');
			$questions->execute(array(
				$_POST['question'],
				$_POST['choice_a'],
				$_POST['choice_b'],
				$_POST['choice_c'],
				$_POST['answer'],
				$_POST['commentary'],
				$_POST['genre']
			));
			header('Location: post_done.php'); exit();
		}
	}


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title>3択クイズ投稿[MyPf]</title>
<link rel="stylesheet" href="quiz.css" type="text/css">
</head>
<body>

	<div class="header">
		<h1>[3択クイズ] 投稿画面</h1>
		<dl>
				<p><?php echo h($_SESSION['member']['user_name']); ?>さん、クイズの投稿をどうぞ♪</p>
				<p class="std_image"><img src="../images/sideways.png" width="90%" height="90%" alt="sideways" title="sideways"></p>
				<div class="header_menu" style="text-align: right">
					<a href="../menu.php" class="btn">TOPへ</a>
					<a href="../logout.php" class="btn">ログアウト</a>
				</div>
	</div>

	<div class="header_image2" style="text-align: right">
	</div>

	<div id="div1">
		<form action="" method="post">
		<dl>
			<dt>問題(100文字まで)</dt>
			<dd><textarea name="question" rows="5" cols="60" required autofocus></textarea></dd>
			<dt>選択肢A(30文字まで)</dt>
			<dd><textarea name="choice_a" rows="3" cols="60" required></textarea></dd>
			<dt>選択肢B(30文字まで)</dt>
			<dd><textarea name="choice_b" rows="3" cols="60" required></textarea></dd>
			<dt>選択肢C(30文字まで)</dt>
			<dd><textarea name="choice_c" rows="3" cols="60" required></textarea></dd>
			<dt>答え</dt>
			<dd>※ お手数ですが、正解の選択肢と全く同じ内容を貼り付けてください。 ※</dd>
			<dd><textarea name="answer" rows="2" cols="60" required></textarea></dd>
			<dt>ジャンル(16文字まで)</dt>
			<dd><input type="text" name="genre" size="35" required></dd>
			<dt>解説（任意）</dt>
			<dd><textarea name="commentary" rows="5" cols="60"></textarea></dd>
		</dl>
		<input type="submit" value="投稿内容の確認">
		</form>
	</div>

</body>
</html>
