setInterval(nowTime, 1000);

function nowTime() {
let now = new Date();
let yyyy = now.getFullYear();
let MM = now.getMonth() + 1;
let dd = now.getDate();
let day_arr = ['日', '月', '火', '水', '木', '金', '土',];
let day = day_arr[now.getDay()];
let hh = now.getHours();
let mm = now.getMinutes();
let ss = now.getSeconds();
if (hh < 10) {
  hh = '0' + hh;
}
if (mm < 10) {
  mm = '0' + mm;
}
if (ss < 10) {
  ss = '0' + ss;
}
  let text = yyyy + '年' + MM + '月' + dd + '日(' + day + '曜) ' + hh + '時' + mm + '分' + ss + '秒';
  document.getElementById('time').textContent = text;
}
