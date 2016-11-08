function replyFormat(obj) {
    var text = $(obj).html();
    text = text.replace(regReplyName, function ($0, $1) {
        return "<span>回复</span><a>" + $1 + "</a>:";
    });
    text = parseUbbCode(text);
    $(obj).html(text);
}

function rebuildPage(id, page, pagecount) {
    var firstno = page - 5;
    firstno = firstno > 1 ? firstno : 1;
    var lastno = page + 9;
    lastno = lastno > pagecount ? pagecount : lastno;
    var html = "";
    if (page > 1) {
        html += '<a href="javascript:void(0);" data-id="' + id + '" data-page="1">首页</a><a href="javascript:void(0);" data-id="' + id + '" data-page="' + (page - 1) + '">上一页</a>';
    }
    if (firstno != 1) {
        html += '<a href="javascript:void(0);" data-id="' + id + '" data-page="' + firstno + '">..</a>';
    }
    for (var i = firstno; i <= lastno; i++) {
        if (i == page) {
            html += '<span class="tp">' + page + '</span>';
        } else {
            html += '<a href="javascript:void(0);" data-id="' + id + '" data-page="' + i + '">' + i + '</a>';
        }
    }
    if (page < pagecount) {
        html += '<a href="javascript:void(0);"  data-id="' + id + '" data-page="' + (page + 1) + '"> 下一页</a> <a href="javascript:void(0);"  data-id="' + id + '" data-page="' + pagecount + '">尾页</a>';
    }
    return html;
}

$(function () {
    $("#post_content").keyup(function () {
        var count = $("#post_content").val().replace($("#post_content").attr("watermark"), "").length;
        $("#wordtotal").text(count);
        if (count > 300) {
            $("#wordtotal").css("color", "red");
        } else {
            $("#wordtotal").css("color", "#AAA");
        }
    });

    $('pre[id^=pre_]').each(function () {
        parseContent($(this));
    });

    $('pre[id^=reply_pre_]').each(function () {
        replyFormat($(this));
    });

    $("#post_content").watermark();

    $("#submit_btn").on('click', reply);

    $("#quick_reply_btn").on('click', function () {
        if (!logined) {
            //$.dialog.alert("提示", "请登录后再回复！", function () {
            //    location.href = "/SSO/Login";
            //});
            return;
        }
        var txt = $("#quick_reply_txt").val();
        var captchaFast = $("#captchaFast").val();
        if (txt.length < 5 || txt.length > 300) {
            $.dialog.alert("提示", "请填写回帖内容，内容长度5-300字");
            return;
        }
        $("#post_content").val(txt);
        if (captchaFast == "") {
            $.dialog.alert("提示", "请填入验证码");
            return;
        }
        reply(2);
    });

    function reply(capValue) {
        var content = $("#post_content").val().replace($("#post_content").attr("watermark"), "");
        var captcha = $("#captcha").val();
        if (capValue == 2) captcha = $("#captchaFast").val(); ; //快速回复  
        if (logined) {
            if (!checkPhoneActivate()) return;
            if (content.length < 5 || content.length > 300) {
                $.dialog.alert("提示", "请填写回帖内容，内容长度5-300字");
                return;
            }
            else if (captcha == "") {
                $.dialog.alert("提示", "请填入验证码");
                return;
            } else {
                $.dialog.loading.show();
                $.post('/discuss/post', { id: postid, content: content, captcha: captcha }, function (result) {
                    $.dialog.loading.hide();
                    var replycount = parseInt($("#reply_count").val()) + 1;
                    if (result.success) {
                        if (result.needcheck) {
                            $.dialog.alert("提示", "您的回帖已发表成功，请耐心等待审核！");
                        } else {
                            $.dialog.alert("提示", "您的回帖已发表成功！", function () {
                                var pid = 'pre_p_' + (replycount + 1);
                                var hd = '<div class="owner_title"><div class="info"><span class="name">' + result.member + '</span>';
                                if ($("#creator").text() == result.member) {
                                    hd += '<span class="owner">&nbsp;</span>';
                                }
                                hd += '<span>发表于:</span><span class="time">' + result.createtime + '</span></div><div class="floor">' + (replycount + 1) + '楼</div></div><div class="u_content">'
                                    + '<pre id="' + pid + '"> ' + multiLineHtmlEncode(content) + '</pre></div>';
                                $("#reply_list").append(hd);
                                parseContent($('#' + pid));
                            });
                        }
                        $("#post_content").val($("#post_content").attr("watermark"));
                        $("#wordtotal").text(0);
                        $("#quick_reply_txt").val("");
                        $("#reply_count").val(replycount);
                        $("#captcha").val("");
                        $("#captchaFast").val("");
                        $("#imgcaptcha").click();
                        $("#imgcaptchaFast").click();
                    } else {
                        if (result.message == "验证码输入错误") {
                            if (capValue == 2) {
                                $("#imgcaptchaFast").click();
                            } else {
                                $("#imgcaptcha").click(); 
                            }
                          
                        }
                        $.dialog.alert("提示", result.message, function () {
                            if (result.needActivate) {//如果手机没有激活
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
    }

    $('#post_content').keydown(function (e) {
        if (e.ctrlKey && e.keyCode == 13) {
            reply();
        }
    });

    $(".replay_info").keydown(function (e) {
        if (e.ctrlKey && e.keyCode == 13) {
            $(this).parent().find(".button2").click();
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

    $("#agree").bind("click", function () {
        $.post('/discuss/useragrees', { id: postid }, function (r) {
            if (r && r.success) {
                $("#agree").text(parseInt($("#agree").text()) + 1);
            } else {
                $.dialog.alert("提示", "您已经赞过了");
            }
        });

        $("#agree").unbind("click").bind("click", function () { $.dialog.alert("提示", "您已经赞过了"); });
    });

    $(".replay_cont .button").click(function () {
        if (!logined) {
            //$.dialog.alert("提示", "请登录后再回复！", function () {
            //    location.href = "/SSO/Login";
            //});
            return;
        }
        $(this).parent().parent().find(".replay_wrap").show();
        $(this).parent().parent().find(".replay_info").focus().html("");
    });
    $(".replay_button").live("click", function () {
        if (!logined) {
            //$.dialog.alert("提示", "请登录后再回复！", function () {
            //    location.href = "/SSO/Login";
            //});
            return;
        }
        $(this).parent().parent().parent().parent().find(".replay_wrap").show();
        $(this).parent().parent().parent().parent().find(".replay_info").focus().val("回复 @" + $(this).parents().find(".c a").html() + ":");
    });
    $(".replay_owner").click(function () {
        if (!logined) {
            //$.dialog.alert("提示", "请登录后再回复！", function () {
            //    location.href = "/SSO/Login";
            //});
            return;
        }
        var id = $(this).attr("data");
        $(".replay_" + id).show();
        $(".replay_" + id).find(".replay_wrap").show();
        $(".replay_" + id).find(".replay_info").html("").focus();
    });

    $(".replay_wrap .button2").click(function () {
        var id = $(this).attr("data-id");
        var textinput = $(this).parent().find(".replay_info");
        var text = textinput.val();
        var textCaptchaInput = $(this).parent().find(".replaycaptcha_info");
        var imgCaptcha = $(this).parent().find(".imgRepalyCaptcha");
        var replaycaptcha = textCaptchaInput.val();
        var ul = $(this).parent().parent().parent().find("ul");
        var h2 = $(this).parent().parent().parent().find("h2");

        if (!logined) {
            //$.dialog.alert("提示", "请登录后再回复！", function () {
            //    location.href = "/SSO/Login";
            //});
            return;
        }
        if (!checkPhoneActivate()) return;
        if (text.length > 200) {
            $.dialog.alert("提示", "您输入的内容太多!");
            return;
        }
        if (text.length < 5) {
            $.dialog.alert("提示", "内容不能少于5字符");
            return;
        }
        if (replaycaptcha == "") {
            $.dialog.alert("提示", "请填入验证码");
            return;
        }
        if (id) {
            $.dialog.loading.show();
            $.post('/discuss/postreply', { postid: id, content: text, captcha: replaycaptcha }, function (result) {
                $.dialog.loading.hide();
                if (result.success) {
                    if (result.needcheck) {
                        $.dialog.alert("提示", "您的回帖已发表成功，请耐心等待审核！");
                    } else {
                        $.dialog.alert("提示", "您的回帖已发表成功！", function () {
                            var pid = 'reply_pre_p_' + result.id;
                            var hd = '<li><div class="c"><a class="n">' + result.member + '</a><pre id="' + pid + '">:' + multiLineHtmlEncode(text) + '</pre></div><div class="t"><span>' + result.createtime + '</span></div></li>';
                            if (ul && ul.length > 0) {
                                ul.append(hd);
                            } else {
                                h2.after("<ul>" + hd + "</ul>");
                            }
                            replyFormat($('#' + pid));
                        });
                    }
                    textinput.val("");
                    textCaptchaInput.val("");
                    imgCaptcha.click();

                }
                else {
                    if (result.message == "验证码输入错误") imgCaptcha.click();
                    $.dialog.alert("提示", result.message, function () {
                        if (result.needActivate) {//如果手机没有激活
                            location.href = "/usercenter/index/2";
                        }
                    });

                }
            }, 'json').error(function () {
                $.dialog.alert("提示", "发帖失败，请稍后重新尝试！");
                $.dialog.loading.hide();
            });
        }
    });


    $(".pager p a").live("click", function () {
        var id = $(this).attr("data-id");
        var page = $(this).attr("data-page");
        var p = $(this).parent();
        var ul = p.parent().parent().find("ul");
        if (id && page) {
            $.post("/discuss/postreplylist", { postId: id, page: page }, function (result) {
                var json = eval(result);
                var page = json.pagination.PageNumber;
                var pagecount = json.pagination.PageCount;
                p.html(rebuildPage(id, page, pagecount));

                var data = json.data;
                var html = "";
                for (var i = 0; i < data.length; i++) {
                    var d = data[i];
                    if (d.CreatorId == -2) {
                        html += '<li id="reply_li_' + d.Id + '"><div class="c"><a class="n">' + d.Creator + '</a><div style=\"border:1px solid #d5d5aa;text-align:center;padding:5px;background-color:#feffec;color:#ff0000\">抱歉，该会员已回火星，此帖子内容不可见。</div></div><div class="t"><span>' + formatDate(d.CreateTime, 'YYYY-MM-dd HH:mm') + '</span><a class="replay_button">回复</a>';
                    } else {
                        html += '<li id="reply_li_' + d.Id + '"><div class="c"><a class="n">' + d.Creator + '</a><pre id="reply_pre_' + d.Id + '">:' + d.Content + '</pre></div><div class="t"><span>' + formatDate(d.CreateTime, 'YYYY-MM-dd HH:mm') + '</span><a class="replay_button">回复</a>';
                    }
                    if (master == "true") {
                        html += '<a href="javascript:deletePostReply(' + d.Id + ')">删除</a>';
                    }
                    html += '</div></li>';
                }
                ul.html(html);

                ul.find('pre[id^=reply_pre_]').each(function () {
                    replyFormat($(this));
                });
            });
        }
    });
});

function formatDate(str, format) {
    if (!str) return '';

    var i = parseInt(str.match(/[-]*\d+/g)[0]);
    if (i < 0) return '';
    var d = new Date(i);
    if (d.toString() == 'Invalid Date') return '';

    //处理客户端时区不同导致的问题
    //480 是UTC+8
    var utc8Offset = 480;
    d.setMinutes(d.getMinutes() + (d.getTimezoneOffset() + 480));

    format = format || 'MM/dd hh:mm:ss tt';

    var hour = d.getHours();
    var month = FormatNum(d.getMonth() + 1)

    var re = format.replace('YYYY', d.getFullYear())
    .replace('YY', FormatNum(d.getFullYear() % 100))
    .replace('MM', FormatNum(month))
    .replace('dd', FormatNum(d.getDate()))
    .replace('hh', hour == 0 ? '12' : FormatNum(hour <= 12 ? hour : hour - 12))
    .replace('HH', FormatNum(hour))
    .replace('mm', FormatNum(d.getMinutes()))
    .replace('ss', FormatNum(d.getSeconds()))
    .replace('tt', (hour < 12 ? 'AM' : 'PM'));

    return re;

    function FormatNum(num) {
        num = Number(num);
        return num < 10 ? ('0' + num) : num.toString();
    }
}