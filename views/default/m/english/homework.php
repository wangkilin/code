<?php View::output('m/english/header.php'); ?>
	<div class="container">
		<div class="report-title">
		    已经连续坚持<span><?php echo $this->homeworkRecord['keep_days'];?></span>天按时交作业,获得了<span><?php echo $this->homeworkRecord['keep_days'];?></span>积分!继续加油!
        </div>
		<!--背景图-->
		<div class="report-img">
        	<img src="<?php echo getModulePicUrlBySize('course', null, $this->item['pic']);?>"/>
        <!-- <a href="m/english/show/<?php echo $_GET['id'];?>">重听课程</a>

		<div><?php echo $this->item['title']; ?></div>
		<div><?php echo $this->item['title2']; ?></div>
        -->
        </div>
        <!--问题-->
        <form action="m/english/ajax_save_answer/<?php echo $_GET['id'];?>" method="post" id="answer_question_form">
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
                                <audio src="<?php echo getModuleUploadedFileUrl('homework', $_val['file_location'], $_val['file_time'])?>" controls="controls" attach-id="<?php echo $_val['attach_id']?>"></audio>
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
                            <input type="hidden" class="js-answer-item" name="homework_answer[<?php echo $_val['id'];?>]" value="" />
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
	</form>

	</div>
	<div class="container">
		<span id="save_study_report">保存学习报告！</span>
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
    $('#save_study_report').click (function () {
        var $answers = $('.container').find('.jsUploadVoice');
        for(var i =0; i <$answers.length; i++) {
            $answers.eq(i).trigger('click');
        }

        ICB.ajax.postForm($('#answer_question_form'));

        return false;
    });

    wx.startRecord(); // 防止第一次弹框 TODO
      setTimeout(function(){
        wx.stopRecord({
            fail:function(res){
                //alert("停止失败");
            },
            success: function (res) {}
        });
    },800); // 这里的 800 是血的教训，开始为了快写了10，
     //坑爹的发现 ios会失败，而安卓正常，最后发现是stop本身异步，太快调用fail都不会执行，坑点之一


    // 录音
    $('.container').on('click', '.jsStartRecord', function () {
        console.info('clicking button');
        $(this).removeClass('jsStartRecord');
        $(this).closest('.jsAnswerWrap').find('.jsUploadVoice, .jsPlayVoice').remove();

        wx.startRecord({
            /**
             * 用户拒绝录音授权
             */
            concel : function () {
            }
        });
        $(this).addClass('jsStopRecord');
    });
    // 播放录音
    $('.container').on('click', '.jsPlayVoice', function () {
        var voiceId = $(this).closest('.jsAnswerWrap').data('voiceId');
        console.info(voiceId);
        $(this).removeClass('jsPlayVoice');
        wx.playVoice({
            localId: voiceId // 需要播放的音频的本地ID，由stopRecord接口获得
        });
        $(this).addClass('jsStopVoice');
    });
    // 停止播放录音
    $('.container').on('click', '.jsStopVoice', function () {
        var voiceId = $(this).closest('.jsAnswerWrap').data('voiceId');
        $(this).removeClass('jsStopVoice').addClass('jsPlayVoice');
        console.info(voiceId);
        wx.stopVoice({
            localId: voiceId // 需要播放的音频的本地ID，由stopRecord接口获得
        });
        $(this).addClass('jsPlayVoice');
    });
    // 上传录音
    $('.container').on('click', '.jsUploadVoice', function () {
        var $this = $(this);
        var voiceId = $(this).closest('.jsAnswerWrap').data('voiceId');
        wx.uploadVoice({
            localId: voiceId, // 需要上传的音频的本地ID，由stopRecord接口获得
            isShowProgressTips: 1, // 默认为1，显示进度提示
            success: function (res) {
            		var serverId = res.serverId; // 返回音频的服务器端ID

                $this.closest('.jsAnswerWrap').find('.js-answer-item').val(res.serverId);
            }
        });
    });
	// 停止录音
    $('.container').on('click', '.jsStopRecord', function () {
        var $this = $(this);
        wx.stopRecord({
            success: function (res) {
                var localId = res.localId;
                $this.closest('.jsAnswerWrap').data('voiceId', res.localId);

                wx.uploadVoice({
                    localId: localId, // 需要上传的音频的本地ID，由stopRecord接口获得
                    isShowProgressTips: 1, // 默认为1，显示进度提示
                    success: function (res) {
                    		var serverId = res.serverId; // 返回音频的服务器端ID

                        $this.closest('.jsAnswerWrap').find('.js-answer-item').val(res.serverId);


                        $this.closest('.jsAnswerWrap').data('voiceId', res.localId);
                        $this.closest('.jsAnswerWrap').find('.jsUploadVoice, .jsPlayVoice').remove();
                        $this.closest('.jsAnswerWrap').append(
                               // '<p class="fl-r jsUploadVoice"><i class="icon icon-insert"></i></p>' +
                                '<p class="fl-r jsPlayVoice"><i class="icon icon-volume-high"></i></p>'
                                );
                    },
                    fail : function () {
                    }
                });
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

    // 上传语音接口
    wx.uploadVoice({
        localId: '', // 需要上传的音频的本地ID，由stopRecord接口获得
        isShowProgressTips: 1, // 默认为1，显示进度提示
        success: function (res) {
        var serverId = res.serverId; // 返回音频的服务器端ID
        }
    });

    //识别音频并返回识别结果接口
    wx.translateVoice({
        localId: '', // 需要识别的音频的本地Id，由录音相关接口获得
        isShowProgressTips: 1, // 默认为1，显示进度提示
        success: function (res) {
        		alert(res.translateResult); // 语音识别的结果
        }
    });

});
</script>
<?php View::output('m/english/footer.php'); ?>
