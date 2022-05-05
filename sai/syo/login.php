<?php

// htmlspecialcharsのfunktion化
function h($value) {
  return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

require('dbconnect.php');
session_start();

if ($_COOKIE['user_name'] != '') {
	$_POST['user_name'] = $_COOKIE['user_name'];     //⑦にログイン情報アリ（ユーザーネーム、PW）のCookieなら、cookieのメール情報をPOST。
	$_POST['password'] = $_COOKIE['password'];     //同上。
$_POST['save'] = 'on';    //onにすることで、Cookieに新しい有効期間が設定される。（最後のログインから2週間保存する。）
}


if (!empty($_POST)) {    //ログインボタンが押されていれば。（＝送信データが空でなければ。）
	// ログインの処理
	if ($_POST['user_name'] != '' && $_POST['password'] != '') {    //ユーザー名とPWが入力されていれば。
		$login = $db->prepare('SELECT * FROM members WHERE user_name=? AND
			password=?');     //③入力されたユーザー名、PWが登録済みかどうか確認
			$login->execute(array(
				$_POST['user_name'],
				sha1($_POST['password'])  //PWはsha1暗号化状態で登録されているので、検索時もsha1暗号化された文字列で検索する。（sha2のsha512にすべきでは。。）
			));
			$member = $login->fetch();
			if ($member) {     //DBにユーザ名・PWあったので、ログインする。
				// ログイン成功
				$_SESSION['id'] = $member['id'];
				$_SESSION['time'] = time();
				
				// ログイン情報を記録する
				if ($_POST['save'] == 'on') {     //⑧
					setcookie('user_name', $_POST['user_name'], time()+60*60*24*14);
					setcookie('password', $_POST['password'], time()+60*60*24*14);
				}
				
				header('Location: menu.php'); exit();
			} else {
				$error['login'] = 'failed';     //⑤ログイン失敗（（PW間違いか、ユーザー登録されていないか。。。））
			}
		} else {
			$error['login'] = 'blank';  //ユーザ名とPWが入力されていなければエラーを返し、入力を促す。
		}
	}
		
	/*
	*/
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>ログイン画面 [MyPf]</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body background="./image/bleachbypassthmb4.jpg">

		<header>
			<h1>私のポートフォリオ</h1>
		</header>
		<main>
			<h2>ログイン</h2>
			<div id="content">
				<form action="" method="post">
					<dl>
						<dt>ユーザー名</dt>
							<dd>
								<input type="text" name="user_name" size="35" maxlength="255" value="<?php echo h($_POST['user_name']); ?>" autofocus>
									<?php if ($error['login'] == 'blank'): ?>
										<p class="error">* ユーザー名を入力してください</p>
									<?php endif; ?>
									<?php if ($error['login'] == 'failed'): ?>
										<p class="error">* ログインに失敗しました。ユーザー名、パスワードをご確認下さい。</p>
									<?php endif; ?>
							</dd>
						<dt>パスワード</dt>
							<dd>
								<input type="password" name="password" size="35" maxlength="255" value="<?php echo h($_POST['password']); ?>" />
							</dd>
						<br>
						<dt>自動ログイン</dt>
							<dd>
								<input id="save" type="checkbox" name="save" value="on"><label
									for="save">次回からは自動的にログインする</label>
							</dd>
					</dl>
					<input type="submit" value="ログイン" />
				</form>
				<p>&raquo;<a href="touroku/">登録がまだの方はこちらをクリック</a></p>
			</div>	
		</main>
		<footer>
			<p>※当サイトの画像の一部は、pakutasoさんの画像サービスをお借りしたものです。</p>
			<p>現在の時刻は <span id="time"><script src="current_time.js"></script></span> です。</p>
			<!-- 
				<img id="photo" src="./images/sleepy01.png" width="20%" height="20%">
				<script src="onoff.js"></script>
			-->
		</footer>
</body>
</html>
