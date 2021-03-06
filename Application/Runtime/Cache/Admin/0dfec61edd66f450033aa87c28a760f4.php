<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>登录页面-<?php echo C('ADMIN_NAME');?></title>
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

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/assets/css/ace-ie.min.css"/>
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="<?php echo C('__STATIC__');?>/assets/js/html5shiv.js"></script>
    <script src="<?php echo C('__STATIC__');?>/assets/js/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo C('__STATIC__');?>/validator/jquery.validator.css">
</head>

<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="icon-leaf green"></i>
                            <span class="red">运营平台</span>
                            <span class="white"><?php echo C('ADMIN_NAME');?></span>
                        </h1>
                        <h4 class="blue">&copy;6HC</h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="icon-coffee green"></i>
                                        请输入您的信息
                                    </h4>

                                    <div class="space-6"></div>

                                    <form name="login" action="<?php echo U('Login/dologin');?>" method="POST" autocomplete="off"
                                          data-validator-option="{theme:'yellow_right_effect',stopOnError:true}">
                                        <fieldset>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="email" class="form-control"
                                                                   placeholder="请输入邮箱" data-rule="邮箱:required;email"/>
															<i class="icon-user"></i>
														</span>
                                            </label>

                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="password" class="form-control"
                                                                   placeholder="请输入密码"
                                                                   data-rule="密码:required;password;length[4~]"/>
															<i class="icon-lock"></i>
														</span>
                                            </label>

                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="verify" class="form-control"
                                                                   placeholder="请输入验证码"
                                                                   data-rule="验证码:required;verify"/>
															<i class="icon-lock"></i>
														</span>
                                            </label>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
														    <img class="img_change" src="<?php echo U('Login/verify');?>" alt=""
                                                                 width="200" height="60"/>
														</span>
                                            </label>

                                            <div class="space"></div>

                                            <div class="clearfix">
                                                <label class="inline">
                                                    <input type="checkbox" class="ace"/>
                                                    <span class="lbl"> 记住我</span>
                                                </label>

                                                <button type="sumbit"
                                                        class="width-35 pull-right btn btn-sm btn-primary">
                                                    <i class="icon-key"></i>
                                                    登录
                                                </button>
                                            </div>

                                            <div class="space-4"></div>
                                        </fieldset>
                                    </form>

                                </div>
                                <!-- /widget-main -->

                                <div class="toolbar clearfix">
                                    <div>

                                    </div>

                                </div>
                            </div>
                            <!-- /widget-body -->
                        </div>
                        <!-- /login-box -->


                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </div>
    <!-- /.main-container -->

    <!-- basic scripts -->

    <!--[if !IE]> -->

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <![endif]-->

    <!--[if !IE]> -->

    <script type="text/javascript">
        window.jQuery || document.write("<script src='TATIC/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='<?php echo C('__STATIC__');?>/assets/js/jquery-1.10.2.min.js'>" + "<" + "/script>");
    </script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo C('__STATIC__');?>/validator/jquery.validator.js"></script>
    <script type="text/javascript" src="<?php echo C('__STATIC__');?>/validator/local/zh-CN.js"></script>

    <script type="text/javascript">
        if ("ontouchend" in document) document.write("<script src='<?php echo C('__STATIC__');?>/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
    </script>

    <!-- inline scripts related to this page -->

    <script type="text/javascript">
        function show_box(id) {
            jQuery('.widget-box.visible').removeClass('visible');
            jQuery('#' + id).addClass('visible');
        }

        jQuery(function ($) {

            $.extend({

                changeVerify: function () {
                    var url = $('.img_change').attr('src');
                    if (url.indexOf('?') > 0) {
                        url = url.substr(0, url.indexOf('?'));
                    }
                    url = url + '?' + parseInt(Math.random() * 10000);
                    $('.img_change').attr('src', url);
                },

                sub: function (obj) {
                    var obj = $(obj);

                    $.ajax({
                        type: 'post',
                        url: obj.attr('action'),
                        data: obj.serialize(),
                        success: function (response) {
                            if (response.code > 0) {
                                window.location.href = response.url;
                            } else {
                                alert(response.msg);
                            }

                            $.changeVerify();
                        }
                    });

                    return false;
                }

            });


            $('.img_change').click(function () {
                $.changeVerify();
            })
        })
    </script>

</body>
</html>