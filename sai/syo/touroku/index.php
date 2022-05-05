<?php
require('../dbconnect.php');

// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

session_start();

if (!empty($_POST)) {
	// エラー項目の確認
	if ($_POST['user_name'] == '') {
		$error['user_name'] = 'blank';
	}
	if (strlen($_POST['password']) < 6) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] == '') {
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);
		if ($ext != 'jpg' && $ext != 'gif') {
			$error['image'] = 'type';
		}
	}




	// 重複アカウントのチェック
	if (empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE	user_name=?');
		$member->execute(array($_POST['user_name']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['user_name'] = 'duplicate';
		}
	}

	if (empty($error)) {
		// 画像をアップロードする
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' .$image);
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}
}
// 書き直し
if ($_REQUEST['action'] == 'rewrite') {
$_POST = $_SESSION['join'];
$error['rewrite'] = true;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>ユーザー登録画面 [MyPf]</title>
<link rel="stylesheet" href="../style.css">
</head>

<body>
<header>
  <h3>私のポートフォリオ</h3>
</header>
<main>
  <h2>ユーザー登録</h2>
  <div id="content">
		<p>次のフォームに必要事項をご記入ください。</p>
		<form action="" method="post" enctype="multipart/form-data">
		<dl>
		<dt><span class="required">必須</span> ユーザー名</dt>
		<dd><input type="text" name="user_name" size="35" maxlength="255" value="<?php echo h($_POST['user_name']); ?>" required autofocus/>
			<?php if ($error['user_name'] == 'blank'): ?>
			<p class="error">* ニックネームを入力してください</p>
			<?php endif; ?>
			<?php if ($error['user_name'] == 'duplicate'): ?>
			<p class="error">* 入力されたユーザー名はすでに登録されています</p>
			<?php endif; ?>
		</dd>
		<dt><span class="required">必須</span> パスワード ※6文字以上</dt>
		<dd><input type="password" name="password" size="35" maxlength="20" value="<?php echo h($_POST['password']); ?>" required/>
      <?php if ($error['password'] == 'blank'): ?>
				<p class="error">* パスワードを入力してください</p>
			<?php endif; ?>
			<?php if ($error['password'] == 'length'): ?>
				<p class="error">* パスワードは6文字以上で入力してください</p>
			<?php endif; ?>
		</dd>
		<dt><br> [任意] ユーザーのイメージ画像</dt>
		<dd><input type="file" name="image" size="35" />
			<?php if ($error['image'] == 'type'): ?>
			<p class="error">* 写真などは「.gif」または「.jpg」の画像を指定してください
			</p>
			<?php endif; ?>
			<?php if (!empty($error)): ?>
			<p class="error">* 画像指定した方は、恐れ入りますが画像を改めて指定してください</p>
			<?php endif; ?>
		</dd>
		</dl>
			<input type="submit" value="入力内容を確認する" />
		</form>
  </div>





<p>&raquo;<a href="../login.php">ログイン画面に戻る<a></p>
</main>


</body>
</html>
