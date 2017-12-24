<?php View::output('m/english/header.php'); ?>
	<div class="container">
		<div class="report-title">
		    已经连续坚持<span>2</span><span>7</span>天按时交作业,获得了<span>3</span><span>1</span><span>7</span>积分!继续加油!
        </div>
		<!--背景图-->
		<div class="report-img">
        	<img src="<?php echo getMudulePicUrlBySize('course', null, $this->item['pic']);?>"/>
        <!-- <a href="m/english/show/<?php echo $_GET['id'];?>">重听课程</a>

		<div><?php echo $this->item['title']; ?></div>
		<div><?php echo $this->item['title2']; ?></div>
        -->
        </div>
        <!--问题-->
        <div class="report-question"  id="itemContent">
            <ul class="report-box">
                <li>
                    <div class="report-item">
                        <p class="q-tit">问题<span><i>Q</i>UESTION</span></p>
                    </div>
                </li>
                <?php if ($this->itemList) {
		         $index = 1;?>
		<?php foreach($this->itemList as $_val) {
		    ?>
		<?php if ($_val['file_location']) { ?>
                <li>
                    <div class="report-item q-sound clearfix">
                        <div class="s-left-img"></div>
                        <div class="s-right">
                            <i class="iconfont icon icon-volume-high"></i>
                            <audio src="<?php echo getMuduleUploadedFileUrl('homework', $_val['file_location'], $_val['file_time'])?>" controls="controls" attach-id="<?php echo $_val['attach_id']?>"></audio>
                        </div>
                    </div>
                </li>
                <?php }?>
                <li>
                    <div class="report-item q-text clearfix">
                        <div class="q-text-l "><?php echo sprintf('%02d', $index++);?></div>
                         <div class="q-text-r">
                             <p><?php echo $_val['content'];?></p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="report-item q-answer jsAnswerWrap clearfix">
                        <p class="fl-r jsStartRecord">
                            <i class="iconfont icon icon-mic"></i>
                            <span>回答问题</span>
                        </p>
                    </div>
                </li>

		<?php } // end foreach ?>
		<?php } // end if ?>
            </ul>
        </div>

	</div>
	<div class="container">
		<span>保存学习报告！</span>
	</div>
<?php echo $this->weixin_signature;?>
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
    $('.container').on('click', '.jsStartRecord', function () {
        console.info('clicking button');
        $(this).removeClass('jsStartRecord');
        wx.startRecord();
        $(this).addClass('jsStopRecord');
    });
    $('.container').on('click', '.jsPlayVoice', function () {
        var voiceId = $(this).closest('.jsAnswerWrap').data('voiceId');
        console.info(voiceId);
        $(this).removeClass('jsPlayVoice');
        wx.playVoice({
            localId: voiceId // 需要播放的音频的本地ID，由stopRecord接口获得
        });
        $(this).addClass('jsStopVoice');
    });
    $('.container').on('click', '.jsStopVoice', function () {
        var voiceId = $(this).closest('.jsAnswerWrap').data('voiceId');
        $(this).removeClass('jsStopVoice').addClass('jsPlayVoice');
        console.info(voiceId);
        wx.stopVoice({
            localId: voiceId // 需要播放的音频的本地ID，由stopRecord接口获得
        });
        $(this).addClass('jsPlayVoice');
    });

    $('.container').on('click', '.jsStopRecord', function () {
        var $this = $(this);
        wx.stopRecord({
            success: function (res) {
                var localId = res.localId;
                $this.closest('.jsAnswerWrap').data('voiceId', res.localId);
                $this.closest('.jsAnswerWrap').find('.jsStopVoice, .jsPlayVoice').remove();
                $this.closest('.jsAnswerWrap').prepend(
                        '<p class="fl-r jsPlayVoice"><i class="icon icon-volume-high"></i></p>'
                        );
            }
        });
        $(this).removeClass('jsStopRecord');
        $(this).addClass('jsStartRecord');
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
