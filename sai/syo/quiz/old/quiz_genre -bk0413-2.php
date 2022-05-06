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
<div>
<p>挑戦したいクイズのジャンルを選択してください</p>
<label><input type="checkbox" name="all_genres" value="全部" checked>全ジャンル</label><br>
<label><input type="checkbox" name="selected_genres[]" value="足し算">足し算</label><br>
<label><input type="checkbox" name="selected_genres[]" value="引き算">引き算</label><br>

<!-- 
<?php 
      // ↓課題１：下記を関数にできない。sql文がエラーになる。
      // ジャンルの一覧を取得
      $genres = $db->query('SELECT DISTINCT genre FROM quiz_book');
      while ($genre = $genres->fetch()){
        echo '<option value="">' . $genre['genre'] .'</option>';
      }
      ?>
-->

<p>↓</p>
<p>問題数の合計 [ｘ問]</p>
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
	<select name="genre" id="genre">
    <?php 
      // ↓課題１：下記を関数にできない。sql文がエラーになる。
      // ジャンルの一覧を取得
      $genres = $db->query('SELECT DISTINCT genre FROM quiz_book');
      while ($genre = $genres->fetch()){
        echo '<option value="">' . $genre['genre'] .'</option>';
      }
    ?>
  </select>

</div>

<!-- クイズTOPのジャンル選択画面から、選択したジャンルをPOST -->

<div class="quiz_start" style="text-align: right">
<form action="./quiz/quiz.php" method="post">
  <input type="hidden" name="monme" value="1">
  <input type="hidden" name="genre" value="<?php echo $filter; ?>">
  <input type="submit" value="3択クイズゲーム開始！">
</form>
</div>


<div class="random">
      <p>ランダムに問題を表示</p>
      <p>3問</p>
      <p>5問</p>
      <p>10問</p>
</div>


</body>
</html>
