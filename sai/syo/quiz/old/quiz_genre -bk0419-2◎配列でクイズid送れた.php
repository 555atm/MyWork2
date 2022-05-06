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

<title>無題ドキュメント</title>
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
			<a href="../index.php">TOPへ</a>
			<a href="../logout.php">ログアウト</a>
	</div>
</div>


<div>
<p>挑戦したいクイズのジャンルを選択してください</p>
<label><input type="checkbox" name="all_genres" value="全部" checked>全ジャンル（100問）</label><br>
<label><input type="checkbox" name="selected_genres[]" value="足し算">足し算（10問）</label><br>
<label><input type="checkbox" name="selected_genres[]" value="引き算">引き算（10問）</label><br>

<?php
/*考え中
if(!empty($_POST[selected_genres])){
  //$genres = $db->prepare(select * from quiz_book WHERE genre=? and ? and ?);
  bindValue(":genre", $genres,PARAM_BOTH);
  while (execute($genre = $genres->fetch));
}
*/
?>

<p>ジャンルのチェックボックスを↓ここ↓に表示したい</p>

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
<p>問題数の合計 [ｘ問]をこの場所に表示したい。</p>

</div>

<!-- 
  
  //ジャンルの数だけドロップダウンメニューの中身 ＝ optionを表示
  echo count($genre);
  $genre_length = count($genre)
  foreach ($i=0; $i <=$genre_length; $i++) {
    echo ('<option value=""><?php echo $_POST[$genre][$i]; ?><option>')
    }
    
  //ジャンルの数だけドロップダウンメニューの中身 ＝ optionを表示 Bパターン
  $categories=$filter['$genre'];
  foreach($categories as $category) {
    echo 'カテゴリ：', htmlspecialchars($category, ENT_QUOTES), ' ';
  }	

-->


<!-- ドロップダウンで選択 -->
<div id="div_genre">
  <p>ジャンルを一つだけ選択してクイズに挑戦する</p>
  <form action="quiz.php" method="post">
    <select name="selected_genres" class="selected_genres">
      <?php 
        // ↓課題１：下記を関数にできない。sql文がエラーになる。
        // ジャンルの一覧を取得
        $genres = $db->query('SELECT genre,COUNT(*) as genre_count FROM quiz_book group by genre');
        while ($genre = $genres->fetch()){
          echo '<option value="' . $genre['genre'] .'">' . $genre['genre'] . '(' . $genre['genre_count'] . '件)</option>';
          $_SESSION[selecgenre] = $genre['genre'];
        }
      ?>
    <input type="hidden" name="monme" value="1">
    <input type="submit" value="3択クイズゲーム開始！">    
    </select>
  </form>

</div>



<div class="random">
  <p>ランダムに問題を数問、出題したい</p>
  <p>3問</p>
  <p>5問</p>
  <p>10問</p>
  
  <?php 
    // クイズをランダムに、3件表示
    unset($_SESSION['syutsudai_random']);
    $stmt = $db->prepare('SELECT * FROM quiz_book ORDER BY RAND() LIMIT 3');
    //$stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
    $stmt->execute();
    //$allquiz = $stmt->fetchAll(); 
    while ($allquiz = $stmt->fetch()){
      echo '<p>' . $allquiz['id'] .' | '. $allquiz['genre'] .' | '. $allquiz['question'] .' | '. $allquiz['answer'] . '</p>';
      $syutsudai_random[] = $allquiz['id'];
    }
    print_r($syutsudai_random);
  ?>

  <form action="quiz.php" method="post">
  <?php
    foreach($syutsudai_random as $syutsudai_rand){
      echo '<input type="hidden" name="syutsudai[]" value="' . $syutsudai_rand . '">'; 
    }
  ?>
    <input type="submit" value="3択クイズゲーム開始！">    
  </form>

</div>


<div class="allquiz">
  <p>まずは全てのクイズをここ↓に表示</p>
  <p>id | quesiton | choice_a | choice_b | choice_c | answer | </p>

  <?php 
    // 全てのクイズと、その中身を表示
    unset($_SESSION['syutsudai']);
    $stmt = $db->prepare('SELECT * FROM quiz_book');
    //$stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
    $stmt->execute();
    //$allquiz = $stmt->fetchAll(); 
    while ($allquiz = $stmt->fetch()){
      echo '<p>' . $allquiz['id'] .' | '. $allquiz['genre'] .' | '. $allquiz['question'] .' | '. $allquiz['answer'] . '</p>';
      $syutsudai_all[] = $allquiz['id'];
    }
    print_r($syutsudai_all);
  ?>
  <form action="quiz.php" method="post">
    <?php
    foreach($syutsudai_all as $syutsudai){
      echo '<input type="hidden" name="syutsudai[]" value="' . $syutsudai . '">';
    }
    ?>
    <input type="submit" value="3択クイズゲーム開始！">
  </form>

</div>




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
