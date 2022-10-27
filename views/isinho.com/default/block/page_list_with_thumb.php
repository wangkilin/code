<?php if ($this->itemList) { ?>
<?php foreach ($this->itemList AS $key => $_itemInfo) { ?>
<div class="icb-item clearfix nomargin " data-topic-id="">
    <div class="icb-content col-sm-12">
        <div class="mod-body">
            <div class="icb-article-title-box clearfix">
                <span class="icb-article-title">
				 <h4>
					<a href="/page/<?php echo $this->_isInside ? 'inside_index/' : ''; ?><?php echo empty($_itemInfo['url_token']) ? $_itemInfo['id']: $_itemInfo['url_token']; ?>" target="blank"><?php echo $_itemInfo['title']; ?></a>
				</h4>
                </span>
            </div>

            <div class="content-wrap">
                <div class="content row" id="detail_<?php echo $_itemInfo['id']; ?>">
                    <?php if ($this->thumbList[$_itemInfo['id']]) {?>
                    <div class="markitup-box col-sm-2"><img src="<?php echo $this->thumbList[$_itemInfo['id']]; ?>" class="pull-left inline-img">
                    </div>
                    <?php }?>
                    <div class="<?php echo isset($this->thumbList[$_itemInfo['id']]) ? 'col-sm-10':'col-sm-12';?>">
                        <?php
                        echo nl2br(cjk_substr(trim(strip_tags(FORMAT::parse_attachs(FORMAT::parse_bbcode($_itemInfo['contents'])))), 0, 200) ); ?> <?php
                        if (cjk_strlen($_itemInfo['contents']) > 130) {
                            ?> ...
                        <br/>
                        <a class="more pull-right" href="/page/<?php echo $this->_isInside ? 'inside_index/' : ''; ?><?php echo empty($_itemInfo['url_token']) ? $_itemInfo['id']:$_itemInfo['url_token']; ?>" target="_blank">查看全部</a>
                        <?php
                        } ?>
                    </div>
                    <div class="collapse article-brief all-content">
                        <?php //echo FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($_itemInfo['message']))); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="mod-footer clearfix">
            <?php $_tmpCategoryId = $_itemInfo['category_id'];
            while($_tmpCategoryId > 0 && isset($this->categoryList[$_tmpCategoryId])) { ?>
            <span class="pull-left article-tag">
            <a class="text" href="/page/<?php echo $this->_isInside ? 'inside_square/' : ''; ?>category-<?php
               echo $_tmpCategoryId; ?>"><?php
               echo $this->categoryList[$_tmpCategoryId]['title']; ?></a>
            </span>
            <?php
            $_tmpCategoryId = $this->categoryList[$_tmpCategoryId]['parent_id'];
            }// end while ?>
            <span class="pull-right more-operate text-color-999">
            <?php   echo  $this->userList[$_itemInfo['user_id']] ['user_name']; ?>
            •
            <?php
            echo date_friendly(strtotime($_itemInfo['modify_time']), null, 'Y-m-d'); ?>
            </span>

            </span>
        </div>
    </div>
</div>

<?php
            } ?>
<?php echo $this->pagination; ?>

<?php } ?>
