<?php View::output('global/header_meta.php'); ?>

<body onload="typeof(RefreshOnce)==='undefined' ? null : RefreshOnce();" orient="portrait">

        <!-- Start Header -->
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <!-- Logo -->
                        <img class="black-logo" src="<?php echo G_STATIC_URL; ?>/isinho.com/logo-black-stamp.png" style="float:left" />
                        <img class="blue-logo" src="<?php echo G_STATIC_URL; ?>/isinho.com/logo-stamp.png" style="float:left" />
                        <div class="logo">
                            <!-- <a href="./">沈阳<span>新禾</span>文化传媒有限公司</a> -->
                        </div>
                        <!--/ End Logo -->
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="nav-area">
                            <!-- Main Menu -->
                            <nav class="mainmenu">
                                <div class="mobile-nav"><!-- 空标签， 存放移动端菜单 --></div>
                                <div class="collapse navbar-collapse">
                                    <ul class="nav navbar-nav menu">
                                        <li class="active"><a href="/#slider">首页</a></li>
                                        <li class=""><a href="/#BookPublish">图书</a></li>
                                        <li class=""><a href="/#newmedia">新媒体</a></li>
                                        <li class=""><a href="/#software">软件</a></li>
                                        <li class=""><a href="/#website">网站</a></li>
                                        <!-- <li class=""><a href="./#movie">影视</a></li> -->
                                        <li class=""><a href="/page/">动态</a></li>
                                        <li class=""><a href="/#contact">联系</a></li>
                                    </ul>
                                </div>
                            </nav>
                            <!--/ End Main Menu -->
                        </div>
                    </div>

                </div>
            </div>
        </header>
