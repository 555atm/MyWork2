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


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="haiku.css">
<title>お遊び俳句ゲーム [MyPf]</title>
</head>
<body>
	<div class="wrap">
		<div class="header">
			<h1>お遊び俳句ゲーム<h1>
			<h1>投稿された俳句一覧（工事中）<h1>
			<div class="header_menu" style="text-align: right">
							<a href="../menu.php" class="btn">TOPへ</a>
							<a href="../logout.php" class="btn">ログアウト</a>
			</div>
		</div>

		<div class="space">
			<p>　　</p>
		</div>

  	<div class="haiku_list">

				<label>| No.　上の句　　中の句　　下の句　｜　課題文字[上], 課題文字[中], 課題文字[下]</label><br>
				<label>-------------------------------------------------------------------------------</label><br>
				<?php
				// 俳句を閲覧する
						$haikus = $db->prepare('SELECT * FROM haiku');
						$haikus->execute();
						while ($haiku = $haikus->fetch()){						
							echo $_SESSION['id'] =$haiku['id'] .'. ';
							echo $_SESSION['kamigo'] =$haiku['kamigo'] .' ';
							echo $_SESSION['nakashichi'] =$haiku['nakashichi'] .' ';
							echo $_SESSION['shimogo'] =$haiku['shimogo'] .' ';
							echo ($haiku['kami_random'] == null ? '　|　[上] 無　' : '　|　[上] ' . $_SESSION['kami_random'] =$haiku['kami_random'] .'　');
							echo ($haiku['naka_random'] == null ? '[中] 無　' : '[中] ' . $_SESSION['naka_random'] =$haiku['naka_random'] .'　');
							echo ($haiku['shimo_random'] == null ? '[下] 無　' : '[下] ' . $_SESSION['shimo_random'] =$haiku['shimo_random'] .'　');
							echo '<br>';
						}
				?>
		</div>

		<div class="footer">
		  <p>　　　　　　　　　　</p>
		</div>
		
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

</body>
</html>
