<script>
<?php if (strpos($_SERVER['HTTP_HOST'], 'icodebang.cn')) {
    $baiduStatCode = "be8b971fb0f380a3005b896a533a9cb2";
} else if (strpos($_SERVER['HTTP_HOST'], 'icodebang.com')) {
    $baiduStatCode = "681fc9b1c75c25b5868d6bfdea94f7df";
} else if (strpos($_SERVER['HTTP_HOST'], 'devboy.cn')) {
    $baiduStatCode = "bb69e584fbcae17dd29f6c1fdaca4fc1";
} else if (strpos($_SERVER['HTTP_HOST'], 'kinful.com')) {
    $baiduStatCode = "2f76f17fb0ba37097de30a8552b76a11";
} else if (strpos($_SERVER['HTTP_HOST'], 'teacup.com.cn')) {
    $baiduStatCode = "eecfca100aa19c14ceed1e271e00d47a";
} else if (strpos($_SERVER['HTTP_HOST'], 'scanonly.com')) {
    $baiduStatCode = "57b91a3d0d94a5ad2bf47748512edf40";
} else if (strpos($_SERVER['HTTP_HOST'], '3dwindy.com')) {
    $baiduStatCode = "f8ff896c8113089b4866edbf81ae0ce1";
} else if (strpos($_SERVER['HTTP_HOST'], 'ekotlin.com')) {
    $baiduStatCode = "e1e8b8e705799181727e5d81aecf2fd2";
} else if (strpos($_SERVER['HTTP_HOST'], 'ukotlin.com')) {
    $baiduStatCode = "d6d835d2bfa79eaf1afa7c1fe5bbe293";
}
if ($baiduStatCode) {
?>
// 百度统计
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?<?php echo $baiduStatCode;?>";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
<?php } ?>
</script>
<!-- 360自动提交 -->
<script>
(function(){
var src = "https://s.ssl.qhres2.com/ssl/ab77b6ea7f3fbf79.js";
document.write('<script src="' + src + '" id="sozz"><\/script>');
})();
</script>
