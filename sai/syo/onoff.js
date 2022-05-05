/*
<style>
body {
	width: 600px:
	margin: 0px auto;
}
#photo_frame {
	background-color: #333333;
	text-align: center;
	line-height: 1.0;
	padding: 15px 0px 10px;
}	
</style>

<!--
-->

<div id="photo_frame">
	<img id="photo" src="sleepy01.jpg">
</div>
<p>この画像は5秒間隔で自動的に切り替わります</p>
<button id="btn">スライドシューを停止</button>
*/






<script>
let n = 1;
let slideshow = setInterval(changeImg, 4000);

//-------画像の変更---------------------
function changeImg() {
	// 画像ファイル名の生成
	n++;
		if (n > 7) {
			n = 1;
		}
		let newFile = 'sleepy0' + n + '.jpg';

	// フェードアウト(0.1～1.0秒後)
	for (let i = 1; i <= 2; i++) {
		setTimeout(function() {
			let alpha = ( 2 - i ) / 10;
			document.getElementById('photo').style.opacity = alpha;
		}, i * 100);
	}

	//　画像の変更(1.0秒後)
	setTimeout(function() {
		document.getElementById('photo').setAttribute('src', newFile);
	}, 1000);

	// フェードイン(1.1~2.0秒後)
	for (let i = 2; i <= 20; i++) {
		setTimeout(function() {
			let alpha = (i - 10) / 10;
			document.getElementById('photo').style.opacity = alpha;
		}, i * 100);
	}

}

//------　繰り返し処理の停止---------------------
document.getElementById('btn').addEventListener('click', stopImg);
function stopImg() {
	clearInterval(slideshow);
}
</script>