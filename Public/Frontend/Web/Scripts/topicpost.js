$(function () {
    $("#topic_content").watermark();
    $("#topic_caption").watermark();
    $("#submit_btn").on("click", function () {

        var caption = $("#topic_caption").val().replace($("#topic_caption").attr("watermark"), "");
        var content = $("#topic_content").val().replace($("#topic_content").attr("watermark"), "");
        var captcha = $("#captcha").val();
        var hid = $("#hid").val();
        hid = isNaN(hid) ? 0 : hid;
        if (logined) {
            if (!checkPhoneActivate()) return;
            if (caption.length < 5) {
                $.dialog.alert("提示", "请填写主题标题，长度最少5个字");
                return;
            } else if (caption.length > 30) {
                $.dialog.alert("提示", "主题标题内容过长，不能超过30字");
                return;
            } else if (content.length < 15) {
                $.dialog.alert("提示", "请填写主题内容，最少15字");
                return;
            }
            else if ($("#topictags").val() == -1) {
                $.dialog.alert("提示", "请选择主题");
                return;
            } else if ($("#captcha").val() == "") {
                $.dialog.alert("提示", "请填入验证码");
                return;
            }
            else {
                $.dialog.loading.show();
                $.post("/discuss/subject", {
                    forumId: forumId,
                    id: hid,
                    caption: caption,
                    content: content,
                    color: $("#captioncolor").val(),
                    bold: $("#ckbbold").attr("checked") == "checked" ? 1 : 0,
                    top: $("#ckbtop").attr("checked") == "checked" ? 1 : 0,
                    tagid: $("#topictags").val() ? $("#topictags").val() : 0,
                    captcha: captcha
                }, function (result) {
                    $.dialog.loading.hide();
                    if (result.success) {
                        if (result.needcheck) {
                            $("#topic_caption").val("");
                            $("#topic_content").val("");

                            $.dialog.alert("提示", "您的主题已发表成功，请耐心等待审核！");
                            $("#topic_caption").css("backgroundColor", "#fff");
                        }
                        else if (result.verifybuffer) {
                            $.dialog.alert("提示", "为防止广告帖泛滥，交流大厅已开启审核机制，<br/>帖子审核通过将于5分钟后正常显示。", function () {
                                location.href = "/discuss/forum";
                            });
                        }
                        else {
                            $.dialog.alert("提示", hid > 0 ? "主题已修改成功！" : "您的主题已发表成功！", function () {
                                location.href = "/discuss/post/" + result.id;
                            });
                        }
                    } else {
                        $.dialog.alert("提示", result.message, function () {
                            if (result.reback) {
                                location.href = "/discuss";
                            }
                            if (result.needActivate) {
                                location.href = "/usercenter/index/2";
                            }
                        });
                    }
                }, "json").error(function () {
                    $.dialog.alert("提示", "操作失败，请稍后重新尝试！");
                    $.dialog.loading.hide();
                });
            }
        } else {
            $.dialog.alert("提示", "需要登录后才可以发帖");
        }
    });
    if (document.cookie.match(new RegExp("(^| )HJMK=([^;]*)(;|$)")) != null) {
        $.post('/sso/ticket', { forumid: forumId }, function (data) {
            if (data) {
                logined = true;
                $("#topic_content").show();
                $("#un_login_txt").hide();
                $("#votediv").show();
                if (data.isMaster) {
                    showoptions();
                    $("#illustration").show();
                }
                if (data.topics != undefined && data.topics == 0) {
                    if (!data.isMaster) {
                        $("#topictags").val(1).attr('disabled', 'disabled');
                    }
                } else {
                    $("#illustration").show();
                }
            } else {
                $("#un_login_txt").show();
                $("#topic_content").hide();
            }
        }, "json");
    }
    else {
        $("#un_login_txt").show();
        $("#topic_content").hide();
    }

});

function showoptions() {
    $("#topic_caption_option").show();
    $("#icolor").icolor({
        flat: true,
        colors: ["8F2A90", "3C9D40", "996600", "EE1B2E", "EE5023", "2897C5", "EC1282", "000000"],
        col: false,
        onSelect: function (c) {
            $("#topic_caption").css("color", c);
            $("#captioncolor").val(c);
        }
    });
}