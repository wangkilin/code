<?php View::output('m/english/header.php'); ?>

    <div class="container">
        <?php if ($this->item) { ?>
        <div>
          <img src="<?php echo getMudulePicUrlBySize('course', null, $this->item['pic']);?>"/>
        </div>
        <div id="itemContent">
          <?php echo $this->item['content']; ?>
        </div>
        <div id="fix-bottom-right">
          <a href="m/english/homework/<?php echo $this->item['id'];?>"><?php echo _t('交作业');?></a>
          <a href="m/english/share/<?php echo $this->item['id'];?>"><?php echo _t('分享');?></a>
        </div>
        <?php
        //var_dump($this->historyInfo);
        } ?>
    </div>

<script type="text/javascript">
$(document).ready(function(){
    var scrollTop = <?php echo is_array($this->historyInfo) && !empty($this->historyInfo['page_position']['scrollHeight']) ? $this->historyInfo['page_position']['scrollHeight'] : 0;?>;
    //$(window).scrollTop(scrollTop);
    $(window).unload(function () {
        // 设置课程阅读记录
           var positionInfo = {
               pageHeight : $('body').height(),
               scrollHeight : $(window).scrollTop()
           };
           var url = G_BASE_URL + 'm/english/setCourseRead/<?php echo $this->item['id'];?>';

           ICB.ajax.sendNotice (url, {page_position : positionInfo});
       });

    $('#itemContent audio').each(function (index, dom) {
        var jplayerId = 'jplayer_'+index;
        var nextJplayerId = 'jplayer_'+(index+1);
        var jplayerContainerId = 'jplayer_container_' + index;
        var audioSrc = $(dom).attr('src');
        $(this).before(Hogan.compile(ICB.template.jplayer).render(
		  {
			'jplayer_id'           : jplayerId,
			'jplayer_container_id' : jplayerContainerId
	      }
		));

		$("#" + jplayerId).jPlayer({
			ready: function () {
				$(this).jPlayer("setMedia", {
					title: "Bubble",
					mp3: audioSrc
				});
			},
			play: function() { // To avoid multiple jPlayers playing together.
				//console.info($("#" + jplayerId).jPlayer());
				if (! $(this).data('play_times')) {
				    $(this).data('play_times', 1);
				} else {
				    $(this).data('play_times', $(this).data('play_times') + 1);
				}
				$(this).jPlayer("pauseOthers", 0);// 停止其他播放，并定位到开始位置
			},
			ended : function () {
				if ($(this).data('play_times') == 1 // 当前音频刚开始播放
					&& $('#'+nextJplayerId).length  // 还有下一个音频， 并且是未播放的，连续播放
					&& ! $('#'+nextJplayerId).data('play_times')) {
				    $("#" + nextJplayerId).jPlayer('play');
				}
			},
			//swfPath: "../../js",
			supplied: "mp3",
			cssSelectorAncestor: "#" + jplayerContainerId,
			wmode: "window",
			globalVolume: true,
			useStateClassSkin: true,
			autoBlur: false,
			smoothPlayBar: true,
			keyEnabled: true
		});
    });


});

</script>
<?php View::output('m/english/foot_order.php'); ?>
