<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="rensyu.css">
<title>無題ドキュメント</title>
</head>
<body>
  <div class="content">
    <div class="bg">
        <p>たんぽぽ</p>
        <br>
        <?php
//【チェック③】投稿した中の句'のなかに'課題のランダム文字'が含まれている場合

$v123 = '123';
$v1 = '1';
echo $v123 .'<br>';
echo $v1 .'<br>';
echo strpos($v123,$v1);

if(strpos($v123,$v1) != false){
  echo 'OK!!引数１には引数２の文字アリ<br>';
} else {
  echo 'NG!引数１には引数２の文字ナシ<br>';
}


strpos($_SESSION['kamigo'],$_SESSION['kami_random'])

?>


        <br>
        <br>

        <div class="div1">
          <p>文章</p>
        </div>

        <br>
        <br>



  

        
    </div>
  </div>  

  
</body>
</html>
