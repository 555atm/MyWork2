<?php
session_start();
require('dbconnect.php');
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

// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES);
}


// 投稿を記録する
if (!empty($_POST)) {
	if ($_POST['message'] != '') {
		$message = $db->prepare('INSERT INTO posts SET member_id=?, message=?,
		reply_post_id=?, created=NOW()');
		$message->execute(array(
			$member['id'],
			$_POST['message'],
			$_POST['reply_post_id']
		));
		header('Location: bbs.php'); exit();
		// ↑投稿処理の最後にHeaderファンクションで再度bbs.phpにジャンプする。
		// こうすることで再読み込みボタンやF5からの『フォーム再送信画面』で投稿が重複することを防げる。
	}
}
// 投稿を取得する
$page = $_REQUEST['page'];
  //↑URLパラメータで指定された値をページ数として$pageに代入。空なら1にする。
if ($page == '') {
	$page = 1;
}
$page = max($page, 1);  //URLパラメータにマイナス値が指定されたら$pageを1にする

// 投稿件数から最終ページを取得する
$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);

$start = ($page - 1) * 5;  //スタート位置は 5*(ページ数-1)

$posts = $db->prepare('SELECT m.user_image, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?, 5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

// 返信の場合。p.id=x で1件だけ取得できる。 ＠は、誰かのメッセージに対する変身を意味する記号。
if (isset($_REQUEST['res'])) {
	$response = $db->prepare('SELECT m.user_name, m.user_image, p.* FROM members m,
posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
	$response->execute(array($_REQUEST['res']));

	$table = $response->fetch();
	$message = '@' . $table['name'] . ' ' . $table['message'];
}


// 本文内のURLにリンクを設定します
function makeLink($value) {
	return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>' , $value);
}
	?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title> [MyPf]</title>

	<link rel="stylesheet" href="style.css" >
</head>

<body>
<div id="wrap">
  <div class="header">
		<script type="text/javascript" src="./main.js"></script>
		<h1><span class="pyonpyon">ひとこと</span>掲示板へ<span class="pyonpyon">ようこそ</span></h1>
		<p class="std_image"><img src="./images/DSC_0210_4.JPG" width="240" height="80" alt="スタンダード" title="和風"></p>
		<div class="header_menu" style="text-align: right">
			<a href="./menu.php" class="btn">メニューへ</a>
			<a href="./quiz/quiz_post.php" class="btn">3択クイズ投稿へ</a>
			<a href="./haiku/gamelevel.php" class="btn">お遊び俳句ゲームへ</a>
			<a href="logout.php" class="btn">ログアウト</a>
		</div>
	</div>
</div>	

  <div id="content">
		<form action="" method="post">
			<dl>
				<dt><?php echo h($member['user_name']); ?>さん、メッセージをどうぞ
			</dt>
				<dd>
					<!-- ↓返信対象のコメントを取り出し先頭に@をつけた（if (isset($_REQUEST['res']）ので、それをtextareaへ。 -->	
					<textarea name="message" cols="70" rows="8" autofocus><?php echo h($message);
					?></textarea>
					<!-- ↓返信のために取り出した内容をtextareaへ。 -->	
					<input type="hidden" name="reply_post_id" value="<?php echo h($_REQUEST['res']); ?>" />
				</dd>
			</dl>
				<input type="submit" value="投稿する" />
		</form>

		<?php
		//↓必要？？
		foreach ($posts as $post):
		?>

		<br>
		<div class="msg">
			<img src="member_picture/<?php echo h($post['user_image']); ?>" width="100" height="100" alt="<?php echo h($post['user_name']); ?>" />
			<!-- ↓URLが投稿されたらリンクにする＝makelink関数-->
			<p><?php echo makeLink(h($post['message']));?><span class="name">（<?php echo h($post['user_name']); ?>）</span>
			<!-- ↓返信のためのリンク -->
			[<a href="bbs.php?res=<?php echo h($post['id']); ?>">Re</a>]</p>  
			<p class="day"><a href="view.php?id=<?php echo h($post['id']); ?>"><?php echo h($post['created']); ?></a>
		<?php
		if ($post['reply_post_id'] > 0):
		?>
			<a href="view.php?id=<?php echo h($post['reply_post_id']); ?>">返信元のメッセージ</a>
		<?php
			endif;
		?>
		<?php
		// ↓「本人」の投稿のみ[削除]リンクが表示され、削除できるようになる。
		if ($_SESSION['id'] == $post['member_id']):
		?>
			[<a href="delete.php?id=<?php echo h($post['id']); ?>" style="color:#F33;">削除</a>]
		<?php 
		endif;
		?>
			</p>
		</div>
		<?php
		endforeach;
		?>

		<ul class="paging"> 
		<?php // 前のページへ、次のページへというリンク。
		if ($page > 1) {
		?>
		<li><a href="bbs.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
		<?php
		} else {
		?>
		<li>前のページへ</li>
		<?php
		}
		?>
		<?php
		if ($page < $maxPage) {
		?>
		<li><a href="bbs.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
		<?php
		} else {
		?>
		<li>次のページへ</li>
		<?php	
		}
		?>
		</ul>
  </div>
</div>
</body>
</html>
