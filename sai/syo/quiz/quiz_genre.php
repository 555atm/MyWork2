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
  $_SESSION['member'] = $member;
} else {
	// ログインしていない
	header('Location: ../login.php');
	exit();
}

// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}


//// ジャンルごとのクイズ件数を取得

//【あくまで参考】登録されているクイズの『全件数』をカウントして表示したい↓
$quiz_count = $db->prepare('SELECT COUNT(*) AS quiz_count FROM quiz_book');
	$quiz_count->execute();
	while ($quiz_kensu = $quiz_count->fetch()) {
		$_SESSION['quiz_count'] = $quiz_kensu['quiz_count'];
	}	


// ジャンルで絞った問題を表示
$questions = $db->prepare('SELECT * FROM quiz_book WHERE genre=:genre');
$questions->bindValue( ':genre', $_POST['genre'], PDO::PARAM_INT);
$questions->execute();
while ($quiz = $questions->fetch()) {
	$_SESSION['question'] = $quiz['question']; 
	$_SESSION['answer'] = $quiz['answer'];
	$_SESSION['choice_a'] = $quiz['choice_a'];
	$_SESSION['choice_b'] = $quiz['choice_b'];
	$_SESSION['choice_c'] = $quiz['choice_c'];
	$_SESSION['genre'] = $quiz['genre'];
	$_SESSION['commentary'] = $quiz['commentary'];
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
<body>

<div class="header">

  <script type="text/javascript" src="../main.js"></script>
  <script type="text/javascript" src="quiz.js"></script>
  <h1><span class="pyonpyon">3択</span>クイズ！<span class="pyonpyon"></span></h1>
	<h2><?php echo h($_SESSION['member']['user_name']); ?>さんが挑戦中。</h2>
	<div class="header_menu" style="text-align: right">
			<a href="../menu.php" class="btn">TOPへ</a>
			<a href="../logout.php" class="btn">ログアウト</a>
	</div>
</div>

<div class="quiz_menu">
  <div id="div1">
    <p>メニュー1: 全ジャンルのランダムクイズに挑戦！！</p>
    <p>　　　　　　　　　　※全ての問題から、選んだ数だけの問題数がランダムに出題されます。</p>

    <?php
      // 全てのクイズと、その中身を表示
      unset($_SESSION['syutsudai']);
      $stmt = $db->prepare('SELECT * FROM quiz_book ORDER BY RAND()');
      //$stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
      $stmt->execute();
      //$allquiz = $stmt->fetchAll(); 
      while ($allquiz = $stmt->fetch()){
        $syutsudai_all[] = (string)$allquiz['id'];
      }
    ?>
    <form action="quiz.php" method="post">
      <?php
      foreach($syutsudai_all as $syutsudai){
        echo '<input type="hidden" name="syutsudai[]" value="' . $syutsudai . '">';
      }
      ?>
      <label>全ての問題の中から</label>
      <select name="limit_num">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="5">5</option>
        <option value="8">8</option>
      </select>
      <label>問を出題 </label>
      <input type="hidden" name="monme" value="1">
      <input type="submit" value="3択クイズゲーム開始！" class="submit">
    </form>

  </div>


  <div id="div2">
    <p>メニュー2: ジャンルを絞ってランダムクイズに挑戦！！</p>
    <p> 　　　　　　　※ ジャンルを一つ選択し、指定した問題数でクイズが出題されます。</p>
    <form action="quiz.php" method="post">
      <select name="selected_genre" class="selected_genre">
        <?php 
          // ジャンルの一覧を取得
          $genres = $db->query('SELECT genre,COUNT(*) as genre_count FROM quiz_book group by genre  ORDER BY RAND()');
          while ($genre = $genres->fetch()){
            echo '<option value="' . $genre['genre'] .'">' . $genre['genre'] . '(' . $genre['genre_count'] . '問)</option>';
            //echo '<p>' . $genre['genre'] . $genre['genre_count'] . '</p>';
          }
        ?>
    </select>
    <label>の中から</label>
    <select name="limit_num">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="5">5</option>
      <option value="8">8</option>
    </select>
    <label>問を出題 </label>
    <input type="hidden" name="monme" value="1">
    <input type="submit" value="3択クイズゲーム開始！" class="submit">
    </form>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
  </div>


  <!-- ↓メニュー３は廃止予定。
  <div id="div3">
    <p>メニュー3 ジャンルを選択してクイズを遊ぶ【未完成】</p>
    <?php
    /*考え中
    if(!empty($_POST[selected_genres])){
      //$genres = $db->prepare(select * from quiz_book WHERE genre=? and ? and ?);
      bindValue(":genre", $_POST[selected_genres],PARAM_BOTH);
      while (execute($genre = $genres->fetch));
    }
    */
    ?>

    <p>【開発用】チェックを入れたジャンルの問題を、ランダムに出題できるようにしたい。</p>
    <p> (ジャンルのチェックボックスは表示できた。)</p>

    <form action="quiz.php" method="post" name="categories">
      <?php 
          // ジャンルを全てチェックボックスとして表示
          $genres = $db->query('SELECT genre,COUNT(*) as genre_count FROM quiz_book group by genre');
          while ($genre = $genres->fetch()){
            echo '<input type="checkbox" name="syutsudai[]" value="' . $genre['genre'] . '" id="' . $genre['genre'] .'">';
            echo '<label>' . $genre['genre'] .'(' . $genre['genre_count']  .'件)</label><br>';
          }
          print_r($syutsudai_selectgenre);

      ?>
      <input type="button" value="全部ON！" onclick="allcheck(true);">
      <input type="button" value="全部OFF！" onclick="allcheck(false);">
      <input type="hidden" name="monme" value="1">
      <input type="submit" value="3択クイズゲーム開始！">    
    </select>
    </form>
    <form oninput="result.value = Number(a.value) * Number(b.value) / Number(c.value);">
      <input type="number" name="a" id="a" size="5"> ×
      <input type="number" name="b" id="b" size="5"> ÷
      <input type="number" name="c" id="c" size="5"> =
      <output name="result" for="a b c"> 0</output>
    </form>
    <p>↓</p>
    <font color="red">■４で全て実現すれば良い（問題数もここで選ぶ。）</font><br>
    <font color="red">  fetchallの使い方が分かっていない。</font><br>
    <font color="red">【開発用】問題数の合計 [選択：ｘ問/ 全：ｘ問]をこの場所に表示したい。</font><br>
    <?php
    echo '【開発用】全クイズ件数は' . $_SESSION['quiz_count'] . '件です';
    ?>
  </div>
  ↑メニュー３は廃止予定。-->

</div>




<div class=div_debug1>
    <p>↓以下は開発用の記述です↓</p>
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

    <?php
    // 【練習】ジャンルごとに、ランダムに並んだもの（カラムはジャンルとクイズIDのみ）を表示
      $genres = $db->query('SELECT genre,id FROM quiz_book order by genre');
      while ($genre = $genres->fetch()){
        echo '<input type="checkbox" name="syutsudai[]" value="' . $genre['genre'] . '" id="' . $genre['genre'] .'">';
        echo '<label>' . $genre['genre'] .'(' . $genre['genre_count']  .'件)</label><br>';
      }
  ?>


    <p>■デバッグ用（変数の確認)■</p>
    <pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
    <pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
    <pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
    <pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
  </div>
<!-- 

-->

</body>
</html>
