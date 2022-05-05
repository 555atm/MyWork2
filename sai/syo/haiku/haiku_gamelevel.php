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

////イレギュラーな操作で『ランダム文字・俳句のセッションデータ』が残っていた場合に備えて、消去。
//unset($_SESSION[’value’]);だと動くけど『今後良くない』とwarning出てしまうので、
//$_SESSION[’value’] = array(); の形に修正した。

if(!empty($_SESSION['kami_random'])) {
  $_SESSION['kami_random'] = array();
}
if(!empty($_SESSION['naka_random'])) {
  $_SESSION['naka_random'] = array();
}
if(!empty($_SESSION['shimo_random'])) {
  $_SESSION['shimo_random'] = array();
}
if(!empty($_SESSION['kamigo'])) {
  $_SESSION['kamigo'] = array();
}
if(!empty($_SESSION['nakashichi'])) {
  $_SESSION['nakashichi'] = array();
}
if(!empty($_SESSION['shimogo'])) {
  $_SESSION['shimogo'] = array();
}


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="haiku.css">
<title>お遊び俳句ゲーム [MyPf]</title>
</head>
<body>


<div class="wrap">

  <div class="header">
    <h1>お遊び俳句ゲーム<h1>
    <h2>ゲームレベル選択</h2>
    <div class="header_menu" style="text-align: right">
            <a href="../menu.php" class="btn">TOPへ</a>
            <a href="../logout.php" class="btn">ログアウト</a>
    </div>
  </div>

  <div class="contents">

  <br>
  <br>
  <br>
  <p>ゲームレベルを選択してください</p>
    <form action="haiku_gamelevel.php" method="post">
    <label><input type="radio" name="gamelevel" value="gamelv1">[Lv1] 課題文字なし　　　　　　　　　　　　　</label><br>
    <label><input type="radio" name="gamelevel" value="gamelv2">[Lv2] 課題文字:上の句にひらがな1文字　　　　</label><br>
    <label><input type="radio" name="gamelevel" value="gamelv3" checked>[Lv3] 課題文字: 上・中・下の句にそれぞれ1文字</label><br>
    <input type="hidden" name="nogamelevel" value="nogamelevel">
    <br>
    <label>  </label><input type="submit" value="選択">
    </form><br>

    <?php
      ////// ランダムなひらがな3文字を表示
      //ひらがな - ランダム文字列
      function getRandomHiragana($length = 1) {
        $hiragana = ["ぁ","あ","ぃ","い","ぅ","う","ぇ","え","ぉ","お",
            "か","が","き","ぎ","く","ぐ","け","げ","こ","ご",
            "さ","ざ","し","じ","す","ず","せ","ぜ","そ","ぞ",
            "た","だ","ち","ぢ","っ","つ","づ","て","で","と","ど",
            "な","に","ぬ","ね","の","は","ば","ぱ",
            "ひ","び","ぴ","ふ","ぶ","ぷ","へ","べ","ぺ","ほ","ぼ","ぽ",
            "ま","み","む","め","も","ゃ","や","ゅ","ゆ","ょ","よ",
            "ら","り","る","れ","ろ","わ","ゐ","ゑ","を"];
        $r_str = null;
        for ($i = 0; $i < $length; $i++) {
            $r_str .= $hiragana[rand(0, count($hiragana) - 1)];
        }
        return $r_str;
      }

      // 選択されたゲームレベルを確認し、進む OR 【【選びなおす】】
      $formStart = '
      <form action="haiku_s1.php" method="post">
      <input type="hidden" name="$subChar[]" value="$subChar[]">
      <label></label>
      <br>
      <input type="hidden" name="start" value="1">
      <input type="submit" value="ゲーム開始">
      </form>';

        // switch文
        switch ($_POST['gamelevel']){
          case 'gamelv1':
            $_SESSION['kami_random'] = null;
            $_SESSION['naka_random'] = null;
            $_SESSION['shimo_random'] = null;    
            echo '選択したゲームレベル：1<br>';
            echo '課題の文字は以下となります。ゲームを開始しますか？<br>';
            echo '上の句：条件なし<br>';
            echo '中の句：条件なし<br>';
            echo '下の句：条件なし<br>';
            echo $formStart;
            break;
          case 'gamelv2':
            $_SESSION['kami_random'] = getRandomHiragana();
            $_SESSION['naka_random'] = null;
            $_SESSION['shimo_random'] = null;    
            echo '選択したゲームレベル：2：<br>';
            echo '課題の文字は以下となります。ゲームを開始しますか？<br>';
            echo '上の句：『' . $_SESSION['kami_random'] . '』を含めること。<br>';
            echo '中の句：条件なし<br>';
            echo '下の句：条件なし<br>';
            echo $formStart;
            break;
          case 'gamelv3':
            $_SESSION['kami_random'] = getRandomHiragana();
            $_SESSION['naka_random'] = getRandomHiragana();
            $_SESSION['shimo_random'] = getRandomHiragana();    
            echo '選択したゲームレベル：3<br>';
            echo '課題の文字は以下となります。ゲームを開始しますか？<br>'; 
            echo '上の句：『' . $_SESSION['kami_random'] . '』を含めること。<br>';
            echo '中の句：『' . $_SESSION['naka_random'] . '』を含めること。<br>';
            echo '下の句：『' . $_SESSION['shimo_random'] . '』を含めること。<br>';
            echo $formStart;
            break;
          default:
            // デフォルトは特になにもしない。
        }          
  ?>
  <br>
  <br>
  <br>

</div>

<div class="rules">
      <p class="rules">【基本ルール】</p>
      <p class="rules">&nbsp;&nbsp;・上の句は６～８音、中の句は4~6音、</p>
      <p class="rules">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;下の句は６～８音にして下さい。</p>
      <p class="rules">&nbsp;&nbsp;・『ひらがなのみ』で俳句を書いてください</p>
      <p class="rules">&nbsp;&nbsp;・使用可能な文字：「あ～を」まで。</p>
      <p class="rules">&nbsp;&nbsp;・課題文字については大文字、小文字、</p>
      <p class="rules">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;濁音半濁音の有無、は区別されます。</p>
      <p class="rules">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;例えば...「や」と「ゃ」、「あ」と「ぁ」、「か」と</p>
      <p class="rules">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;「が」、「は」と「ぱ」、「お」と「を」は区別されます。</p>
</div>

<div class="footer">
  <p>　　　　　　　　　　</p>
</div>


<div class=div_debug1>
  <p>■今後の予定■
    ・\n
    ・
  </p>  
  <p>■デバッグ用（変数の確認■</p>
  <pre><?php	echo 'var_dump($_SESSION)の結果→   ';	var_dump ($_SESSION); ?></pre>
  <pre><?php echo 'print_r($_SESSION)の結果→   '; print_r($_SESSION); ?></pre>
  <pre><?php echo 'print_r($_COOKIE)の結果→   '; print_r($_COOKIE); ?></pre>
  <pre><?php echo 'print_r($_POST)の結果→   '; print_r($_POST); ?></pre>
</div>

</body>
</html>
