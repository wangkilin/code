<?php View::output('m/english/header.php'); ?>
	<div class="container">
		<span>已经连续坚持xx天按时交作业，获得xxx积分,继续加油！</span>
	</div>
	<div>
	  <img src="<?php echo getMudulePicUrlBySize('course', null, $this->item['pic']);?>"/>
	</div>
	<div class="container" id="itemContent">
		<div><?php echo $this->item['title']; ?></div>
		<div><?php echo $this->item['title2']; ?></div>
		<div><a href="m/english/show/<?php echo $_GET['id'];?>">重听课程</a></div>
		<?php if ($this->itemList) {?>
		<?php foreach($this->itemList as $_val) {?>
		<?php if ($_val['file_location']) { ?>
		<div><audio src="<?php echo getMuduleUploadedFileUrl('homework', $_val['file_location'], $_val['file_time'])?>" controls="controls" attach-id="<?php echo $_val['attach_id']?>"></audio></div>
		<?php }?>
		<div><?php echo $_val['content'];?></div>
		<div class="jsToRecord">
		    <button>回答问题</button>
		</div>
		<?php } // end foreach ?>
		<?php } // end if ?>
		ll
<?php echo $this->weixin_signature;?>
	</div>
	<div class="container">
		<span>保存学习报告！</span>
	</div>

<script type="text/javascript">

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
		ended : function () {},
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
$(function () {
    $('.container').on('click', '.jsToRecord button', function () {
        console.info('clicking button');
        $(this).removeClass('jsRoRecord');
        wx.startRecord();
        $(this).addClass('jsRecording');
    });

    $('.container').on('click', '.jsRecording button', function () {
        wx.stopRecord({
            success: function (res) {
                var localId = res.localId;
            }
        });
        $(this).removeClass('jsRecording');
        $(this).addClass('jsRoRecord');
    });

    //监听录音自动停止接口

    wx.onVoiceRecordEnd({
    // 录音时间超过一分钟没有停止的时候会执行 complete 回调
    complete: function (res) {
    var localId = res.localId;
    }
    });

    //播放语音接口

    wx.playVoice({
    localId: '' // 需要播放的音频的本地ID，由stopRecord接口获得
    });

    //暂停播放接口

    wx.pauseVoice({
    localId: '' // 需要暂停的音频的本地ID，由stopRecord接口获得
    });

    //停止播放接口

    wx.stopVoice({
    localId: '' // 需要停止的音频的本地ID，由stopRecord接口获得
    });

    //监听语音播放完毕接口

    wx.onVoicePlayEnd({
    success: function (res) {
    var localId = res.localId; // 返回音频的本地ID
    }
    });
});
</script>
<?php View::output('m/english/footer.php'); ?>
