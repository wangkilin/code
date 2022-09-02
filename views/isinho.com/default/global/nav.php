<?php if ($this->crumb) { ?>
<nav class="padding10">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="nav-area">
                        <div class="collapse navbar-collapse">
                            <ul class="navbar-nav menu">
                                <?php
                                foreach ($this->crumb as $_index => $_nav) { if (! is_int($_index)) continue;?>
                                <li><a href="<?php echo $_nav['url']?>"><?php echo $_nav['name'];?></a></li>
                                <li><i class="icon icon-right ft12 margin5"></i></li>
                                <?php }?>
                                <li><a href="<?php echo $this->crumb['last']['url']?>"><?php echo $this->crumb['last']['name'];?></a></li>
                            </ul>
                        </div>
                </div>
            </div>

        </div>
    </div>
</nav>
<?php } ?>
