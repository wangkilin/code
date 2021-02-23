<script>
<?php if (strpos($_SERVER['HTTP_HOST'], 'icodebang.cn')) {
    $baiduStatCode = "be8b971fb0f380a3005b896a533a9cb2";
} else if (strpos($_SERVER['HTTP_HOST'], 'icodebang.com')) {
    $baiduStatCode = "681fc9b1c75c25b5868d6bfdea94f7df";
} else if (strpos($_SERVER['HTTP_HOST'], 'devboy.cn')) {
    $baiduStatCode = "bb69e584fbcae17dd29f6c1fdaca4fc1";
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
<?php }?>
</script>
