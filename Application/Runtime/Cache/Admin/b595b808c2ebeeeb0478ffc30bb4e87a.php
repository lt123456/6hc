<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>6hc后台管理</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- basic styles -->
    <link href="<?php echo C('__STATIC__');?>/assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/assets/css/font-awesome.min.css"/>

    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/assets/css/font-awesome-ie7.min.css"/>
    <![endif]-->

    <!-- page specific plugin styles -->

    <!-- fonts -->

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300"/>

    <!-- ace styles -->

    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/assets/css/ace.min.css"/>
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/assets/css/ace-rtl.min.css"/>
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/assets/css/ace-skins.min.css"/>

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/assets/css/ace-ie.min.css"/>
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/assets/css/jquery-ui-1.10.3.full.min.css"/>
    <![endif]-->


    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/lottery/lottery.admin.css"/>
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/lottery/lottery.global.css"/>
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/assets/css/datepicker.css"/>
    <!-- inline styles related to this page -->
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/validator/jquery.validator.css">
    <!-- ace settings handler -->

    <script src="<?php echo C('__STATIC__');?>/assets/js/ace-extra.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="<?php echo C('__STATIC__');?>/assets/js/html5shiv.js"></script>
    <script src="<?php echo C('__STATIC__');?>/assets/js/respond.min.js"></script>


    <![endif]-->
    <script src="<?php echo C('__STATIC__');?>/assets/js/jquery-2.0.3.min.js"></script>
</head>

<body>

<!-- head -->
<div class="navbar navbar-default" id="navbar">
    <script type="text/javascript">
        try {
            ace.settings.check('navbar', 'fixed')
        } catch (e) {
        }
    </script>

    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="#" class="navbar-brand">
                <small>
                    <i class="icon-leaf"></i>
                    ACE后台管理系统
                </small>
            </a><!-- /.brand -->
        </div>
        <!-- /.navbar-header -->

        <div class="navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="grey">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon-tasks"></i>
                        <span class="badge badge-grey">4</span>
                    </a>

                    <ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="icon-ok"></i>
                            还有4个任务完成
                        </li>

                        <li>
                            <a href="#">
                                <div class="clearfix">
                                    <span class="pull-left">软件更新</span>
                                    <span class="pull-right">65%</span>
                                </div>

                                <div class="progress progress-mini ">
                                    <div style="width:65%" class="progress-bar "></div>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <div class="clearfix">
                                    <span class="pull-left">硬件更新</span>
                                    <span class="pull-right">35%</span>
                                </div>

                                <div class="progress progress-mini ">
                                    <div style="width:35%" class="progress-bar progress-bar-danger"></div>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <div class="clearfix">
                                    <span class="pull-left">单元测试</span>
                                    <span class="pull-right">15%</span>
                                </div>

                                <div class="progress progress-mini ">
                                    <div style="width:15%" class="progress-bar progress-bar-warning"></div>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <div class="clearfix">
                                    <span class="pull-left">错误修复</span>
                                    <span class="pull-right">90%</span>
                                </div>

                                <div class="progress progress-mini progress-striped active">
                                    <div style="width:90%" class="progress-bar progress-bar-success"></div>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                查看任务详情
                                <i class="icon-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="purple">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon-bell-alt icon-animated-bell"></i>
                        <span class="badge badge-important">8</span>
                    </a>

                    <ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="icon-warning-sign"></i>
                            8条通知
                        </li>

                        <li>
                            <a href="#">
                                <div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-pink icon-comment"></i>
												新闻评论
											</span>
                                    <span class="pull-right badge badge-info">+12</span>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="btn btn-xs btn-primary icon-user"></i>
                                切换为编辑登录..
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-success icon-shopping-cart"></i>
												新订单
											</span>
                                    <span class="pull-right badge badge-success">+8</span>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <div class="clearfix">
											<span class="pull-left">
												<i class="btn btn-xs no-hover btn-info icon-twitter"></i>
												粉丝
											</span>
                                    <span class="pull-right badge badge-info">+11</span>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                查看所有通知
                                <i class="icon-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="green">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon-envelope icon-animated-vertical"></i>
                        <span class="badge badge-success">5</span>
                    </a>

                    <ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="icon-envelope-alt"></i>
                            5条消息
                        </li>

                        <li>
                            <a href="#">
                                <img src="<?php echo C('__STATIC__');?>/assets/avatars/avatar.png" class="msg-photo"
                                     alt="Alex's Avatar"/>
										<span class="msg-body">
											<span class="msg-title">
												<span class="blue">Alex:</span>
												不知道写啥 ...
											</span>

											<span class="msg-time">
												<i class="icon-time"></i>
												<span>1分钟以前</span>
											</span>
										</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <img src="<?php echo C('__STATIC__');?>/assets/avatars/avatar3.png" class="msg-photo"
                                     alt="Susan's Avatar"/>
										<span class="msg-body">
											<span class="msg-title">
												<span class="blue">Susan:</span>
												不知道翻译...
											</span>

											<span class="msg-time">
												<i class="icon-time"></i>
												<span>20分钟以前</span>
											</span>
										</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <img src="<?php echo C('__STATIC__');?>/assets/avatars/avatar4.png" class="msg-photo"
                                     alt="Bob's Avatar"/>
										<span class="msg-body">
											<span class="msg-title">
												<span class="blue">Bob:</span>
												到底是不是英文 ...
											</span>

											<span class="msg-time">
												<i class="icon-time"></i>
												<span>下午3:15</span>
											</span>
										</span>
                            </a>
                        </li>

                        <li>
                            <a href="inbox.html">
                                查看所有消息
                                <i class="icon-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="<?php echo C('__STATIC__');?>/assets/avatars/user.jpg"
                             alt="Jason's Photo"/>
								<span class="user-info">
									<small>欢迎光临,</small>
									Jason
								</span>

                        <i class="icon-caret-down"></i>
                    </a>

                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="#">
                                <i class="icon-cog"></i>
                                设置
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="icon-user"></i>
                                个人资料
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="<?php echo U('Login/logout');?>">
                                <i class="icon-off"></i>
                                退出
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- /.ace-nav -->
        </div>
        <!-- /.navbar-header -->
    </div>
    <!-- /.container -->
</div>

<!-- /head -->


<!-- left -->
<div class="main-container" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>

    <div class="main-container-inner">
        <a class="menu-toggler" id="menu-toggler" href="#">
            <span class="menu-text"></span>
        </a>

        <div class="sidebar" id="sidebar">
            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'fixed')
                } catch (e) {
                }
            </script>

            <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                    <button class="btn btn-success">
                        <i class="icon-signal"></i>
                    </button>

                    <button class="btn btn-info">
                        <i class="icon-pencil"></i>
                    </button>

                    <button class="btn btn-warning">
                        <i class="icon-group"></i>
                    </button>

                    <button class="btn btn-danger">
                        <i class="icon-cogs"></i>
                    </button>
                </div>

                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                    <span class="btn btn-success"></span>

                    <span class="btn btn-info"></span>

                    <span class="btn btn-warning"></span>

                    <span class="btn btn-danger"></span>
                </div>
            </div>
            <!-- #sidebar-shortcuts -->

            <ul class="nav nav-list">
                <li>
                    <a href="<?php echo U('Index/index');?>">
                        <i class="icon-dashboard"></i>
                        <span class="menu-text"> 控制台[nav] </span>
                    </a>

                </li>

                <li class="active">
                    <a href="<?php echo U('Member/index');?>">
                        <i class="icon-text-width"></i>
                        <span class="menu-text"> 用户管理 </span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="<?php echo U('Member/index');?>">
                                <i class="icon-double-angle-right"></i>
                                用户列表
                            </a>
                            <a href="<?php echo U('Member/add');?>">
                                <i class="icon-double-angle-right"></i>
                                添加用户
                            </a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a href="<?php echo U('Score/index');?>">
                        <i class="icon-text-width"></i>
                        <span class="menu-text">绩效管理 </span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="<?php echo U('Score/index');?>" class="dropdown-toggle">
                                <i class="icon-double-angle-right"></i>
                                用户排名
                            </a>

                        </li>

                    </ul>
                </li>

                <li>
                    <a href="<?php echo U('LotteryRecord/index');?>" class="dropdown-toggle">
                        <i class="icon-desktop"></i>
                        <span class="menu-text"> 开奖记录管理 </span>

                        <b class="arrow icon-angle-down"></b>

                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="<?php echo U('LotteryRecord/index');?>">
                                <i class="icon-double-angle-right"></i>
                                开奖列表
                            </a>
                            <a href="<?php echo U('LotteryRecord/setRecord');?>">
                                <i class="icon-double-angle-right"></i>
                                设置开奖码
                            </a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-picture"></i>
                        <span class="menu-text"> 图库管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="/admin/PaperList/add">
                                <i class="icon-double-angle-right"></i>
                                添加图库名称
                            </a>
                        </li>
                        <li>
                            <a href="/admin/PaperList/index">
                                <i class="icon-double-angle-right"></i>
                                图库名称管理
                            </a>
                        </li>
                        <li>
                            <a href="/admin/PaperName/add">
                                <i class="icon-double-angle-right"></i>
                                添加图库
                            </a>
                        </li>
                        <li>
                            <a href="/admin/PaperName/index">
                                <i class="icon-double-angle-right"></i>
                                图库列表
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-edit"></i>
                        <span class="menu-text"> 幽默猜测管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="/admin/Joke/index">
                                <i class="icon-double-angle-right"></i>
                                幽默猜测列表
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo U('Admin/Joke/add');?>">
                                <i class="icon-double-angle-right"></i>
                                上传幽默猜测图片
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="widgets.html" class="dropdown-toggle">
                        <i class="icon-list-alt"></i>
                        <span class="menu-text"> 用户投递彩票管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="<?php echo U('Admin/AnimalRecord/index');?>">
                                <i class="icon-double-angle-right"></i>
                                生肖推荐管理
                            </a>

                            <a href="<?php echo U('Admin/SpecialRecord/index');?>">
                                <i class="icon-double-angle-right"></i>
                                号码推荐管理
                            </a>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="<?php echo U('DiscussCategory/index');?>" class="dropdown-toggle">
                        <i class="icon-picture"></i>
                        <span class="menu-text"> 论坛管理 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="<?php echo U('DiscussCategory/index');?>">
                                <i class="icon-double-angle-right"></i>
                                论坛版块管理
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo U('Zhutie/index');?>">
                                <i class="icon-double-angle-right"></i>
                                帖子管理
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo U('ReplayTie/index');?>">
                                <i class="icon-double-angle-right"></i>
                                回复管理
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo U('DiscussCategory/add');?>">
                                <i class="icon-double-angle-right"></i>
                                论坛版块添加
                            </a>
                        </li>
                        <li>
                            <!--  <a href="<?php echo U('Zhutie/add');?>">
                                 <i class="icon-double-angle-right"></i>
                                 帖子发布
                             </a> -->
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-tag"></i>
                        <span class="menu-text"> 词语屏蔽 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="profile.html">
                                <i class="icon-double-angle-right"></i>
                                屏蔽列表
                            </a>
                        </li>

                        <li>
                            <a href="inbox.html">
                                <i class="icon-double-angle-right"></i>
                                填加屏蔽词语
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="<?php echo U('Ip/index');?>" class="dropdown-toggle" class="active">
                        <i class="icon-tag"></i>
                        <span class="menu-text"> ip屏蔽 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>
                </li>

                <li>
                    <a href="<?php echo U('Friend/index');?>" class="dropdown-toggle" class="active">
                        <i class="icon-tag"></i>
                        <span class="menu-text"> 友情链接 </span>

                        <b class="arrow icon-angle-down"></b>
                    </a>
                </li>

                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="icon-file-alt"></i>

								<span class="menu-text">
									其他页面
									<span class="badge badge-primary ">5</span>
								</span>

                        <b class="arrow icon-angle-down"></b>
                    </a>

                    <ul class="submenu">
                        <li>
                            <a href="faq.html">
                                <i class="icon-double-angle-right"></i>
                                帮助
                            </a>
                        </li>

                        <li>
                            <a href="error-404.html">
                                <i class="icon-double-angle-right"></i>
                                404错误页面
                            </a>
                        </li>

                        <li>
                            <a href="error-500.html">
                                <i class="icon-double-angle-right"></i>
                                500错误页面
                            </a>
                        </li>

                        <li>
                            <a href="grid.html">
                                <i class="icon-double-angle-right"></i>
                                网格
                            </a>
                        </li>

                        <li>
                            <a href="blank.html">
                                <i class="icon-double-angle-right"></i>
                                空白页面
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="active">
                    <a href="<?php echo U('Agent/index');?>">
                        <i class="icon-text-width"></i>
                        <span class="menu-text"> 后台用户管理 </span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="<?php echo U('Agent/index');?>">
                                <i class="icon-double-angle-right"></i>
                                用户列表
                            </a>
                            <a href="<?php echo U('Agent/add');?>">
                                <i class="icon-double-angle-right"></i>
                                添加用户
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="active">
                    <a href="<?php echo U('Powe/index');?>">
                        <i class="icon-text-width"></i>
                        <span class="menu-text"> 权限管理 </span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="<?php echo U('Power/rule');?>">
                                <i class="icon-double-angle-right"></i>
                                权限规则
                            </a>
                            <a href="<?php echo U('Power/group');?>">
                                <i class="icon-double-angle-right"></i>
                                权限组
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
            <!-- /.nav-list -->

            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="icon-double-angle-left" data-icon1="icon-double-angle-left"
                   data-icon2="icon-double-angle-right"></i>
            </div>

            <script type="text/javascript">
                try {
                    ace.settings.check('sidebar', 'collapsed')
                } catch (e) {
                }
            </script>
        </div>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try {
                        ace.settings.check('breadcrumbs', 'fixed')
                    } catch (e) {
                    }
                </script>

                
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?php echo U('Admin/Index/index');?>">控制台</a>
        </li>
    </ul>
    <!-- .breadcrumb -->


                <div class="nav-search" id="nav-search">
                    <form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input"
                                           id="nav-search-input" autocomplete="off"/>
									<i class="icon-search nav-search-icon"></i>
								</span>
                    </form>
                </div>
                <!-- #nav-search -->
            </div>

        </div>
        <!-- /.main-content -->

        <div class="ace-settings-container" id="ace-settings-container">
            <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                <i class="icon-cog bigger-150"></i>
            </div>

            <div class="ace-settings-box" id="ace-settings-box">
                <div>
                    <div class="pull-left">
                        <select id="skin-colorpicker" class="hide">
                            <option data-skin="default" value="#438EB9">#438EB9</option>
                            <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                            <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                            <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                        </select>
                    </div>
                    <span>&nbsp; Choose Skin</span>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar"/>
                    <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar"/>
                    <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs"/>
                    <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl"/>
                    <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                </div>

                <div>
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container"/>
                    <label class="lbl" for="ace-settings-add-container">
                        Inside
                        <b>.container</b>
                    </label>
                </div>
            </div>
        </div>
        <!-- /#ace-settings-container -->
    </div>
    <!-- /.main-container-inner -->

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="icon-double-angle-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->




<!-- /left-->

<!--basic scripts -->

<!--[if !IE]> -->

    <div class="main-content">
        <div class="page-content">
            <div class="page-header">
                <h1>
                    控制台
                    <small>
                        <i class="icon-double-angle-right"></i>
                        查看
                    </small>
                </h1>
            </div>
            <!-- /.page-header -->

            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="icon-remove"></i>
                        </button>

                        <i class="icon-ok green"></i>

                        欢迎使用
                        <strong class="green">
                            6hc后台管理系统
                            <small>(v1.2)</small>
                        </strong>
                        ,我们提供全新的6模拟彩票服务,只为您更好的彩票体验
                    </div>
                </div>
            </div>

        </div>
    </div>



<!-- 操作后提示框 -->
<div id="lg-alert" class="hide" style="margin-bottom:-1.5em;"></div>
<!--modal表单弹出框-->
<form id="lg-form" class="modal fade hide form-horizontal" method="post" tabindex="-1" enctype="multipart/form-data"
      onsubmit="return false;"></form>

<!-- <![endif]-->

<!--[if IE]>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<![endif]-->

<!--[if !IE]> -->

<!--<script type="text/javascript">-->
<!--window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"script>");-->
<!--</script>-->

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo C('__STATIC__');?>/assets/js/jquery-1.10.2.min.js'>" + "<" + "script>");
</script>
<![endif]-->

<script type="text/javascript">
    if ("ontouchend" in document) document.write("<script src=<?php echo C('__STATIC__');?>/assets/js/jquery.mobile.custom.min.js'>" + "<" + "script>");
</script>
<script src="<?php echo C('__STATIC__');?>/assets/js/bootstrap.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/typeahead-bs2.min.js"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
<script src="<?php echo C('__STATIC__');?>/assets/js/excanvas.min.js"></script>
<![endif]-->
<script src="<?php echo C('__STATIC__');?>/assets/js/jquery.mobile.custom.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/jquery-ui-1.10.3.full.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/jquery.slimscroll.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/jquery.easy-pie-chart.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/jquery.sparkline.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/flot/jquery.flot.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/flot/jquery.flot.pie.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/flot/jquery.flot.resize.min.js"></script>

<script src="<?php echo C('__STATIC__');?>/layer/layer.js"></script>

<script src="<?php echo C('__STATIC__');?>/assets/js/date-time/bootstrap-datepicker.min.js"></script>
<!-- ace scripts -->

<script src="<?php echo C('__STATIC__');?>/assets/js/ace-elements.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/ace.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo C('__STATIC__');?>/assets/js/jquery.dataTables.bootstrap.js"></script>
<!-- inline scripts related to this page -->
<script src="<?php echo C('__STATIC__');?>/lottery/lottery.global.js"></script>
<script src="<?php echo C('__STATIC__');?>/laydate/laydate.js"></script>

<script type="text/javascript" src="<?php echo C('__STATIC__');?>/validator/jquery.validator.js"></script>
<script type="text/javascript" src="<?php echo C('__STATIC__');?>/validator/local/zh-CN.js"></script>




</body>
</html>