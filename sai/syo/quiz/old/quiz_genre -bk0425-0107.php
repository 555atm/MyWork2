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

//↓開発用。あとで消す↓
//これから出題されるクイズの件数『 $_SESSION['quiz_count'] 』をカウントして表示したい↓考え中↓

//【あくまで参考】登録されているクイズの『全件数』をカウントして表示したい↓
$quiz_count = $db->prepare('SELECT COUNT(*) AS quiz_count FROM quiz_book');
	$quiz_count->execute();
	while ($quiz_kensu = $quiz_count->fetch()) {
		$_SESSION['quiz_count'] = $quiz_kensu['quiz_count'];
	}	
echo '【開発用】これから出題されるクイズの件数は' . $_SESSION['quiz_count'] . '件です';


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

<title>3択クイズ選択画面[私のポートフォリオ]</title>
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


<div class="allquiz">
  <p>■1 クイズを全問解く【一旦完成】</p>

  <?php
    // 全てのクイズと、その中身を表示
    unset($_SESSION['syutsudai']);
    $stmt = $db->prepare('SELECT * FROM quiz_book');
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
      <option value="1">1問</option>
      <option value="2">2問</option>
      <option value="3">3問</option>
      <option value="5">5問</option>
      <option value="8">8問</option>
    </select>
    <label>を出題 </label><br>
    <input type="hidden" name="monme" value="1">
    <input type="submit" value="3択クイズゲーム開始！">
  </form>

</div>


<div class="random">
  <p>■2 ランダムに問題を数問、出題する（3問・5問・8問）【3問だけなら完成】</p>
  
  <form action="quiz.php" method="post">
  <select name="limit_num">
    <option value="1">1問</option>
    <option value="2">2問</option>
    <option value="3">3問</option>
    <option value="5">5問</option>
    <option value="8">8問</option>
  </select>
  <label>を出題</label>

    </select>
    <label><font color="red">【←工事中→】</font></label>
    <input type="hidden" name="syutsudai[]" value="' . $syutsudai_rand . '">
    <input type="hidden" name="monme" value="1">
    <input type="submit" value="3択クイズゲーム開始！">
  </form>

  <!--
  -->
  <?php 
    //// クイズをランダムに、3件表示
    echo '↓開発用。あとで消す↓';
    echo '<p>id | genre | quesiton | answer | </p>';
    //$_SESSION['syutsudai_random'] を消去。
    unset($_SESSION['syutsudai_random']);
    // クイズをランダムに、3件表示するためのSELECT文を実行
    $stmt = $db->prepare('SELECT * FROM quiz_book ORDER BY RAND() LIMIT :keyword');
    $stmt->bindValue(':keyword', 5, PDO::PARAM_INT);
    $stmt->execute();
    //$limited_quiz = $stmt->fetchAll(); 
    //$syutsudai_random[]という配列変数に、全ジャンルからのランダム3問のクイズIDを代入。
    while ($limited_quiz = $stmt->fetch()){
      echo '<p>' . $limited_quiz['id'] .' | '. $limited_quiz['genre'] .' | '. $limited_quiz['question'] .' | '. $limited_quiz['answer'] . '</p>';
      $syutsudai_random[] = $limited_quiz['id'];
    }
    print_r($syutsudai_random);
  ?>
  <form action="quiz.php" method="post">
    <?php
      //ランダム3問のクイズIDが入った配列（$syutsudai_random[]）の中身をURLパラメータとして格納。
      foreach($syutsudai_random as $syutsudai_rand){
        echo '<input type="hidden" name="syutsudai[]" value="' . $syutsudai_rand . '">';
      }
    ?>
    <input type="hidden" name="monme" value="1">
    <input type="submit" value="3択クイズゲーム開始！">    
  </form>
</div>



<!-- ドロップダウンで選択 -->
<div id="div_genre">
  <p>【未完成】■3 ジャンルを一つだけ選択してクイズを遊ぶ</p>
  <font color="red">ジャンル選択の見た目は出来た。次は、選んだジャンルの全問を、ランダムに出題させたい。</font>
  <form action="quiz.php" method="post">
    <select name="selected_genre" class="selected_genre">
      <?php 
        // ↓課題１：下記を関数にできない。sql文がエラーになる。
        // ジャンルの一覧を取得
        $genres = $db->query('SELECT genre,COUNT(*) as genre_count FROM quiz_book group by genre');
        while ($genre = $genres->fetch()){
          echo '<option value="' . $genre['genre'] .'">' . $genre['genre'] . '(' . $genre['genre_count'] . '件)</option>';
          //echo '<p>' . $genre['genre'] . $genre['genre_count'] . '</p>';
        }
      ?>
  </select>
  <label>の中から</label>
  <select name="limit_num">
    <option value="1">1問</option>
    <option value="2">2問</option>
    <option value="3">3問</option>
    <option value="5">5問</option>
    <option value="8">8問</option>
  </select>
  <label>を出題</label><br>
  <input type="hidden" name="monme" value="1">
  <input type="submit" value="3択クイズゲーム開始！">    
  </form>
</div>



<div>
  <p>【未完成】■4 ジャンルを選択してクイズを遊ぶ</p>
  <?php
  /*考え中
  if(!empty($_POST[selected_genres])){
    //$genres = $db->prepare(select * from quiz_book WHERE genre=? and ? and ?);
    bindValue(":genre", $_POST[selected_genres],PARAM_BOTH);
    while (execute($genre = $genres->fetch));
  }
  */
  ?>

  <p>【開発用】ジャンルのチェックボックスは表示できた。</p>
  <p>次は、チェックを入れたジャンルの問題を、ランダムに出題できるようにしたい。</p>

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
  <p>↓</p>
  <font color="red">■４で全て実現すれば良い（問題数もここで選ぶ。）</font><br>
  <font color="red">  fetchallの使い方が分かっていない。</font><br>
  <font color="red">【開発用】問題数の合計 [選択：ｘ問/ 全：ｘ問]をこの場所に表示したい。</font>
</div>

<div class=div_debug1>

  <?php
    // 【練習】ジャンルごとに、ランダムに並んだもの（カラムはジャンルとクイズIDのみ）を表示
      $genres = $db->query('SELECT genre,id FROM quiz_book order by genre');
      while ($genre = $genres->fetch()){
        echo '<input type="checkbox" name="syutsudai[]" value="' . $genre['genre'] . '" id="' . $genre['genre'] .'">';
        echo '<label>' . $genre['genre'] .'(' . $genre['genre_count']  .'件)</label><br>';
      }
  ?>
  


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
