<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="test.css">
<title>テストです</title>
</head>
<body>
<!-- 
  <body background="./images/KAZUHIRO171013022.jpg" >

-->
  <p>テストです</p>

<?php
$errmsg[] = 'あ';
$errmsg[] = 'い';
print_r($errmsg);
echo '<br>';

foreach ($errmsg as $err) {
  echo $err . '<br>' ;
}
?>

</body>
</html>
