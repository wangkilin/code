<ul class="nav nav-tabs">
    <li<?php echo ACTION=='index'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='index'?'#index" data-toggle="tab':'admin/'.CONTROLLER.'/index/'
        ?>"><?php _e('书稿管理'); ?></a>
    </li>
    <?php
    if ( isset($_GET['id']) && ( (CONTROLLER=='books' &&
            (in_array(SinhoBaseController::SINHO_PERMISSION_BOOKLIST_EDIT, $this->user_info['permission']['sinho_modify_manuscript_param']) ||
            in_array(SinhoBaseController::SINHO_PERMISSION_BOOKLIST_ALL, $this->user_info['permission']['sinho_modify_manuscript_param'])
            ) )
        || (CONTROLLER!='books' &&$this->hostConfig->sinho_permission['allow_team_leader_add_book']===true && isset($_GET['id']) ) )  )
    {
    ?>
    <li<?php echo ACTION=='book'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='book'?'#book" data-toggle="tab':'admin/'.CONTROLLER.'/book/'
        ?>"><?php _e('编辑书稿'); ?></a>
    </li>
    <?php } else if (!isset($_GET['id'])&& ( (CONTROLLER=='books' &&
            (in_array(SinhoBaseController::SINHO_PERMISSION_BOOKLIST_ADD, $this->user_info['permission']['sinho_modify_manuscript_param']) ||
            in_array(SinhoBaseController::SINHO_PERMISSION_BOOKLIST_ALL, $this->user_info['permission']['sinho_modify_manuscript_param'])
            ) )
        || (CONTROLLER!='books' &&$this->hostConfig->sinho_permission['allow_team_leader_add_book']===true && isset($_GET['id']) ) )  )
    {?>
        <li<?php echo ACTION=='book'?' class="active"':''?>>
          <a href="<?php
              echo ACTION=='book'?'#book" data-toggle="tab':'admin/'.CONTROLLER.'/book/'
            ?>"><?php _e('新建书稿'); ?></a>
        </li>
    <?php }?>
    <?php if ( (CONTROLLER=='books' &&
                (in_array(SinhoBaseController::SINHO_PERMISSION_BOOKLIST_ADD, $this->user_info['permission']['sinho_modify_manuscript_param']) ||
                in_array(SinhoBaseController::SINHO_PERMISSION_BOOKLIST_ALL, $this->user_info['permission']['sinho_modify_manuscript_param'])
                ) )
            || (CONTROLLER!='books' && $this->hostConfig && $this->hostConfig->sinho_permission['allow_team_leader_import_book']===true)) {?>
    <li<?php echo ACTION=='import'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='import'?'#import" data-toggle="tab':'admin/'.CONTROLLER.'/import/'
        ?>"><?php _e('书稿导入'); ?></a>
    </li>
    <?php }?>
    <?php if (CONTROLLER == 'books') { ?>
    <li<?php echo ACTION=='category'?' class="active"':''?>>
      <a href="<?php
          echo ACTION=='category'?'#category" data-toggle="tab':'admin/'.CONTROLLER.'/category/'
        ?>"><?php _e('图书分类'); ?></a>
    </li>
    <?php } ?>
    <li><a href="#search" data-toggle="tab"><?php _e('搜索书稿'); ?></a></li>
</ul>
