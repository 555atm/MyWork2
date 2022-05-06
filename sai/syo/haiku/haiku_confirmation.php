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
	// ログインしていない場合
	header('Location: ../login.php');
	exit();
}

// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES,"utf-8");
}



?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>お遊び俳句ゲーム_確認画面 [MyPf]</title>
<link rel="stylesheet" href="haiku.css">
</head>
<body>

  <div class="wrap">
    <div class="header">
      <h1>お遊び俳句ゲーム<h1>
      <h2>投稿内容の確認<h2>
    </div>
    <div class="header_menu" style="text-align: right">
              <a href="post.php" class="btn">前の画面に戻る</a>
              <a href="../menu.php" class="btn">TOPへ</a>
              <a href="logout.php" class="btn">ログアウト</a>
    </div>

    <div class="norules">

      <div class="input_confirmation">
        <p>【確認】以下の内容でよろしければ投稿ボタンを押してください。</p>
        <div class="tategaki">
          <?php
          echo '上の句 ：  ' . $_SESSION['kamigo'] . '<br>';
          echo '中の句 ：  ' . $_SESSION['nakashichi'] . '<br>';
          echo '下の句 ：  ' . $_SESSION['shimogo'] . '<br>';
          ?>
        </div>
        <br>

        <form action="post.php" method="post">
          <input type="hidden" name="modify" value="1">
          <input type="hidden" name="kamigo" value="<?php $_SESSION['kamigo']; ?>">
          <input type="hidden" name="nakashichi" value="<?php $_SESSION['nakashichi']; ?>">
          <input type="hidden" name="shimogo" value="<?php $_SESSION['shimogo']; ?>">
          <input type="submit" value="投稿を修正する">
        </form>

        
        <form action="haiku_done.php" method="post">
          <input type="hidden" name="kamigo" value="<?php $_SESSION['kamigo'] ?>">
          <input type="hidden" name="nakashichi" value="<?php $_SESSION['nakashichi'] ?>">
          <input type="hidden" name="shimogo" value="<?php $_SESSION['shimogo'] ?>">
          <input type="submit" value="投稿" class="btn">
        </form>
      </div>
    </div>

    <div class="footer">
      <p>　　　　　　　　　　</p>
    </div>

    <div class=div_debug1>
      <p>以下は開発用の記述です</p>  
      <p>■今後の予定■</p>  
      <p>■デバッグ用（変数の確認)■</p>
      <pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
      <pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
      <pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
      <pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
    </div>

  </div>
</body>
</html>
