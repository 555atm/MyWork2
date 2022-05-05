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
	header('Location: ../login.php');
	exit();
}

// htmlspecialcharsのショートカット
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES);
}


// ページが読み込まれたらクイズを表示する

// ランダムにクイズを3問出題する用??
$_SESSION['syutsudai'];
//unset($_SESSION['syutsudai']);
//クイズスタート＝1問目の場合のみ、出題されるクイズのIDを$_SESSION['syutsudai']に保存。
if ($_POST['monme'] == '1'){
	foreach((array)$_POST['syutsudai'] as $syutsudai){
		$_SESSION['syutsudai'][] = (int)$syutsudai;
	}
}

//■3用 選択された、一つのジャンルからクイズを出題
if(!empty($_POST['selected_genre'])) {
	//select文
	$stmt = $db->prepare('SELECT id from quiz_book where genre=:genre order by RAND()');
	$stmt->bindValue(':genre', $_POST['selected_genre'], PDO::PARAM_STR);
	$stmt->execute();
	while ($limited_quiz = $stmt->fetch()){
		$_SESSION['syutsudai'][] = (int)$limited_quiz['id'];
	}
}
// 問題数をユーザー希望のｎ問にする
if (!empty($_POST['limit_num'])) {
	//【開発用】あとで消す↓
	echo $_POST['limit_num'];
	//↓残す
	$_POST['limit_num'] = (int)$_POST['limit_num'];
	//【開発用】あとで消す↓
	echo $_POST['limit_num'];
	//↓残す
	$_SESSION['syutsudai'] = array_slice($_SESSION['syutsudai'], 0, $_POST['limit_num']);
}
/*
*/

////変数宣言
//これから出題される、全問題数。php7.2からは以下のif文をつけないと、
//Warning (2): count(): Parameter must be an array or an object that implements Countable in file名　line 〇〇
//というエラーが出るので、if(is_array（変数名～は必須です。
if (is_array($_SESSION['syutsudai'])) {
$_SESSION['zenbude'] = count($_SESSION['syutsudai']);
}

//$id = (int)$_GET['monme'];
$id = (int)$_POST['monme'];
$_SESSION['monme'] = $id;

//↓考え中。$_SESSION['syutsudai_index']は、$_SESSION['syutsudai']のインデックス番号です、↓
if (($_POST['monme']=='1')){
	//正解数は最初ゼロ。
	$_SESSION['seikaisu'] = 0;
	//1問目は配列のインデックス0のクイズIDのものを出題する。
	$syutsudai_index = 0;
	$_SESSION['syutsudai_index'] = $syutsudai_index;
} elseif(!empty($_POST['next_quiz'])){
	$_SESSION['syutsudai_index']++;
} else {
	echo '予期せぬエラーです。';
}
//↓開発用。あとで消す↓
echo '【開発用】出題中のクイズIDは' . $_SESSION['syutsudai'][$_SESSION['syutsudai_index']] . 'で、全' . $_SESSION['zenbude'] . '問中の、第' . $_SESSION['monme'] . '問目、$_SESSION[syutsudai]のインデックスは' . $_SESSION['syutsudai_index'] .'です';

// $id = $_SESSION['monme'];
$questions = $db->prepare('SELECT * FROM quiz_book WHERE id=:id2');
$questions->bindValue( ':id2', $_SESSION['syutsudai'][$_SESSION['syutsudai_index']], PDO::PARAM_INT);
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



/*
$questions = $db->prepare('SELECT * FROM quiz_book WHERE id=1');
$questions->execute(array(
	$_POST['question'],
	$_POST['choice_a'],
	$_POST['choice_b'],
	$_POST['choice_c'],
	$_POST['answer'],
	$_POST['commentary'],
	$_POST['genre']
));
$quiz_book = $question->fetchAll();
*/


//header('Location: post_done.php'); exit();
// ↑次へボタンが押されたら、Headerファンクションで再度同じページ（quiz.php)にジャンプする。
// こうすることで再読み込みボタンやF5からの『フォーム再送信画面』で投稿が重複することを防げる(???)

// footerに問題数を表示 全20問中のn問目
// 

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title>3択クイズ画面[MyPf]</title>
<link rel="stylesheet" href="quiz.css" type="text/css">
</head>

<body>
<div class="header">
  <script type="text/javascript" src="../main.js"></script>
  <h1><span class="pyonpyon">3択</span>クイズ！<span class="pyonpyon"></span></h1>
	<h2><?php echo h($member['user_name']); ?>さんが挑戦中。</h2>
	<div class="header_menu" style="text-align: right">
			<a href="../menu.php" class="btn">TOPへ</a>
			<a href="../logout.php" class="btn">ログアウト</a>
	</div>
</div>

<div id="div2">
  <p>問題:<?php echo $_SESSION['monme'] . '    (ジャンル : '. h(($_SESSION['genre'])) . ')';?></p>

	<p>
		<?php
			echo h(($_SESSION['question'])).'<br>';
		?>
	</p>	
</div>


	<div id="div3">
	  <form action="./answer.php" method="post">
	    <label><input type="radio" name="kotae" id="kotae" value="<?php echo $_SESSION['choice_a'] ?>" required>
	    選択肢A：<?php echo h(($_SESSION['choice_a'])); ?></label><br>
	    <label><input type="radio" name="kotae" id="kotae" value="<?php echo $_SESSION['choice_b'] ?>">
	    選択肢B：<?php echo $_SESSION['choice_b'] ?></label><br>
		<label><input type="radio" name="kotae" id="kotae" value="<?php echo $_SESSION['choice_c'] ?>">
		選択肢C：<?php echo $_SESSION['choice_c'] ?></label><br>
	    <input type="submit" value="答え">
	  </form>
		<footer>
			<p>現在、全<?php echo $_SESSION['zenbude']; ?>問中の<?php echo $_SESSION['monme'];?>問目です</p>
		</footer>
	</div>


<!-- デバッグ用 
<div class=div_debug1>
	<pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
	<pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
	<pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
	<pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
	<pre><?php echo 'var_dump($_POST)の結果→   '; var_dump($_POST); ?></pre>
	<p><?php print_r($quiz);  ?></p>
	<?php
	function console_log($data){
	  echo '<script>';
	  echo 'console.log('.json_encode($data).')';
	  echo '</script>';
	}
	console_log($_SESSION);
	?>

</div>
-->



</body>
</html>