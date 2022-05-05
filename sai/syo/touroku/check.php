<?php
session_start();
require('../dbconnect.php');

// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES);
}

//
if (!isset($_SESSION['join'])) {
header('Location: index.php');
exit();
}
if (!empty($_POST)) {
	// 登録処理をする
	$statement = $db->prepare('INSERT INTO members SET user_name=?, password=?, user_image=?, created=NOW()');
		echo $ret = $statement->execute(array(
			$_SESSION['join']['user_name'],
			sha1($_SESSION['join']['password']),
			$_SESSION['join']['image']
		));
		unset($_SESSION['join']);
		header('Location: thanks.php');
		exit();
	}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ユーザー登録確認画面 [MyPf]</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ユーザー登録</h1>
  </div>
  <div id="content">
		<p>以下の情報でユーザー登録します。よろしいですか？</p>
		<form action="" method="post">
			<input type="hidden" name="action" value="submit" />
		<dl>
		<dt>ユーザー名</dt>
		<dd>
			<?php echo h($_SESSION['join']['user_name']); ?>
		</dd>
		<dt>パスワード</dt>
		<dd>
		【表示されません】
		</dd>
		<dt>ユーザーのイメージ画像</dt>
		<dd>
			<img src="../member_picture/<?php echo h($_SESSION['join']['image']); ?>" width="100" height="100" alt="image" />
		</dd>
		</dl>
		<a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | 		<input type="submit" 
		value="登録する" />
		</form>
  </div>

</div>
</body>
</html>
