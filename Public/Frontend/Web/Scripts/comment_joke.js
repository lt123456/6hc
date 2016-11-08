$(function () {
    $("#post_content").keyup(function () {
        var count = $("#post_content").val().replace($("#post_content").attr("watermark"), "").length;
        $("#wordtotal").text(count);
        if (count > 300) {
            $("#wordtotal").css("color", "red");
        } else {
            $("#wordtotal").css("color", "#5eb0e6");
        }
    });
    var tmpCount = 1;
    $("#post_content").watermark();

    $(".u_content pre").each(function () {
        $(this).html(parseUbbCode($(this).html()));
    });

    $("#submit_btn").on('click', function () {
        var content = $("#post_content").val().replace($("#post_content").attr("watermark"), "");
        var captcha = $("#captcha").val();
        if (logined) {
            if (!checkPhoneActivate()) return;
            if (content.length < 5 || content.length > 300) {
                $.dialog.alert("提示", "请填写跟帖内容，内容长度为5-300个字符");
                return;
            }
            else if (captcha == "") {
                $.dialog.alert("提示", "请填入验证码");
                return;
            } else {
                $.dialog.loading.show();
                var data = { id: commentid, type: type, title: title, content: content, captcha: captcha, tag: voteTag };

                $.post('/comment/subject', data, function (result) {
                    $.dialog.loading.hide();
                    if (result.success) {
                        if (result.needcheck) {
                            $.dialog.alert("提示", "您的跟帖已发表成功，请耐心等待审核！");
                        } else {
                            $.dialog.alert("提示", "您的跟帖已发表成功！", function () {
                                var postdiv = '<div class="owner_title"><div class="info"><span class="name">' + result.member + '</span>' +
                                    '<span>发表于：</span> <span class="time">' + result.createtime + '</span></div>' +
                                    '<div class="floor">' + (totalpost + tmpCount) + '楼</div></div>' +
                                    '<div class="u_content"><p>' + parseUbbCode(multiLineHtmlEncode(content)) + '</p></div>';
                                $("#reply_list").prepend(postdiv);
                                $(".postcount").text(totalpost + tmpCount);
                                tmpCount++;
                                location.href = "#comments";

                            });
                        }
                        $("#post_content").val($("#post_content").attr("watermark"));
                        $("#wordtotal").text(0);
                        $("#quick_reply_txt").val("");
                        $("#captcha").val("");
                        $("#imgcaptcha").click();
                    } else {
                        $.dialog.alert("提示", result.message, function () {
                            if (result.needActivate) { //如果手机没有激活
                                location.href = "/usercenter/index/2";
                            }
                        });
                    }
                }, 'json').error(function () {
                    $.dialog.alert("提示", "发帖失败，请稍后重新尝试！");
                    $.dialog.loading.hide();
                });
            }
        } else {
            $.dialog.alert("提示", "需要登录后才可以发帖");
        }
    });
    $('#post_content').keydown(function (e) {
        if (e.ctrlKey && e.keyCode == 13) {
            $("#submit_btn").click();
        }
    });

    //检查用户是否登录状态
    if (document.cookie.match(new RegExp("(^| )HJMK=([^;]*)(;|$)")) != null) {
        $.post('/sso/ticket', null, function (data) {
            if (data) {
                logined = true;
                $("#post_content").show();
                $("#un_login_txt").hide();
            } else {
                $("#un_login_txt").show();
                $("#post_content").hide();
            }
        }, 'json');
    }
    else {
        $("#un_login_txt").show();
        $("#post_content").hide();
    }
});