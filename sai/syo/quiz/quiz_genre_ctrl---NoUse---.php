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
	header('Location: login.php');
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
<link rel="stylesheet" href="quiz.css" type="text/css">

<title>3択クイズ選択画面[MyPf]</title>
</head>
<body background="../images/KAZUHIRO171013022.jpg" >

<div id="header">

  <script type="text/javascript" src="../main.js"></script>
  <script type="text/javascript" src="quiz.js"></script>
  <h1><span class="pyonpyon">3択</span>クイズ！<span class="pyonpyon"></span></h1>
	<dl>
		<dt><?php echo h($member['user_name']); ?>さんが挑戦中。</dt>
	</dl>
	<div class="header_menu" style="text-align: right">
			<a href="../menu.php">TOPへ</a>
			<a href="../logout.php">ログアウト</a>
	</div>
</div>



<div class="redirect_to_quiz3">
  <p>■>3 quiz_genre.phpからユーザーが『一つだけ』選んだジャンルを受けて、quiz.phpにリダイレクト【未完成】</p>
  <font color="red">ポストされた情報を、どのような形で次の画面にリダイレクトで送る？？</font>  
  <font color="red">  受け取る変数『 $_POST[selected_genre] 』⇒ name="syutsudai[]"にして、quiz.phpにポストで送りたい  </font>  
  <?php
    //■3用 選択された１ジャンルからクイズを出題
    if(!empty($_POST['selected_genre'])) {
      $stmt = $db->prepare('SELECT id from quiz_book where genre=:genre');
      $stmt->bindValue(':genre', $_POST['selected_genre'], PDO::PARAM_STR);
      $stmt->execute();
      //$limited_quiz = $selected_genre->fetchAll();
      //print_r($limited_quiz);
      while ($limited_quiz = $stmt->fetch()){
        $syutsudai_random[] = $limited_quizz['id'];
      }
      print_r($syutsudai_random);
      /*
	*/
    }
  ?>  
</div>







<!-- 
  <div class="redirect_to_quiz4">
    <p>■>4 quiz_genre.phpからユーザー選択を受けて、quiz.phpにリダイレクト【未完成】</p>
    
    <?php 
    /* ユーザー選択のリクエストパラメータ（$_POST[selected_genres][]）を受けて、
    * また、$_POST[randamsu]を受けて、 
    * （syutsudai　）にする。 
    *  ⇒複数ジャンルが選択されるとSELECT文に where genre=? and ? and ? and ?、、、、、と増えていくので、難しい。。
    
    $stmt = $db->prepare('SELECT * FROM quiz_book where genre=? and ? and ?');
    //$stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
    $stmt->execute();
    //$allquiz = $stmt->fetchAll(); 
    while ($allquiz = $stmt->fetch()){
      $syutsudai_all[] = (string)$allquiz['id'];
    }
    print_r($syutsudai_all);
    */
    ?>
  <form action="quiz.php" method="post">
    <?php
    /*
    foreach($syutsudai_all as $syutsudai){
      echo '<input type="hidden" name="syutsudai[]" value="' . $syutsudai . '">';
    }
    */
    ?>
    <input type="hidden" name="monme" value="1">
    <input type="submit" value="3択クイズゲーム開始！">
  </form>
</div>
-->


<div class=div_debug1>
    <p>■今後の予定■
      ・チェックボックスでクイズを絞る　
         ★★連番振るーROW_NUMBER()関数？ as quiz_sequence→$quiz[quiz_sequence]★★
         　★ランダムに出題★
         ＜＜input type="checkbox" name="category[]" value="足し算" id="足し算"＞＞
         ＜＜label for="足し算"＞＞＜＜足し算>>
         input type="checkbox" name="category[]" value="引き算" id="引き算"
      ・
      ・
    </p>  
    <p>■デバッグ用（変数の確認)■</p>
    <pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
    <pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
    <pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
    <pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
  </div>
</body>
</html>
