<?php View::output('m/english/header.php'); ?>
	<div class="container">
		<div class="report-title">
		    已经连续坚持<span>2</span><span>7</span>天按时交作业,获得了<span>3</span><span>1</span><span>7</span>积分!继续加油!
        </div>
		<!--背景图-->
		<div class="report-img">
        	<img src="<?php echo getMudulePicUrlBySize('course', null, $this->item['pic']);?>"/>
        </div>
        <!--问题-->
        <div class="report-question">
            <ul class="report-box">
                <li>
                    <div class="report-item">
                        <p class="q-tit">问题<span><i>Q</i>UESTION</span></p>
                    </div>
                </li>
                <li>
                    <div class="report-item q-sound clearfix">
                        <div class="s-left-img"></div>
                        <div class="s-right">
                            <i class="iconfont icon-maikefeng"></i>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="report-item q-text clearfix">
                        <div class="q-text-l ">01</div>
                         <div class="q-text-r">
                             <p>八岁半的哥哥杰克,理性冷静,喜欢看书,他会将沿途看到的事物细心记录笔记本上；而七岁的妹妹安妮,喜欢幻想与冒险,并勇于尝试，这两个一动一静,个性截然不同的兄妹.</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="report-item q-answer clearfix">
                        <p class="fl-r">
                            <i class="iconfont icon-maikefeng"></i>
                            <span>回答问题</span>
                        </p>
                    </div>
                </li>
            </ul>
        </div>
		<div>
            <div><?php echo $this->item['title']; ?></div>
            <div><?php echo $this->item['title2']; ?></div>
            <div><a href="m/english/show/<?php echo $_GET['id'];?>">重听课程</a></div>
            <div>问题1：语音</div>
            <div>问题1：文本</div>
            <div>回答问题</div>
		</div>

	</div>


	<div class="container">
		<span>保存学习报告！</span>
	</div>


<?php View::output('m/english/footer.php'); ?>
