var member = {};
member.userLogined = false;
member.bindMail = false;
member.hasMail = false;
member.isPhoneActivate = false;
member.checkBindPhone = false;
if (document.cookie.match(new RegExp("(^| )HJMK=([^;]*)(;|$)")) != null) {
    $.post('/sso/ticket', null, function (data) {
        if (data) {
            $('#mname').text(data.name);
            $('#signin').show();
            $('#unsignin').hide();
            $(".pageFanY").removeClass("btnUserFeedback");
            $(".pageFanY").append('<a href="/feedback/index" target="_blank" style="height: 110px;width: 100%;display: block;"></a>');
            memberstats();
            member.userLogined = true;
            member.bindMail = data.isBindMail;
            member.isPhoneActivate = data.isPhoneActivate;
            //member.hasMail = data.hasMail;
            member.hasPhone = data.hasPhone;

        } else {
            $('#unsignin').show();
            $('#signin').hide();
            if (IsMobile()) {
                $(".loginbtn").bind("click", function () {
                    $.dialog.alert("提示", "请登录后再回复！", function () {
                        location.href = "/SSO/Login";
                    });
                });
            } else {
                loadJs("http://rescsj.56hx.com/mc/js/loginframe.js?class=loginbtn");
            }
        }
    }, 'json');
}
else {
    $('#unsignin').show();
    $('#signin').hide();
    if (IsMobile()) {
        $(".loginbtn").bind("click", function () {
            $.dialog.alert("提示", "请登录后再回复！", function () {
                location.href = "/SSO/Login";
            });
        });
    } else {
        loadJs("http://rescsj.56hx.com/mc/js/loginframe.js?class=loginbtn");
    }
}
$(".keyword,.keyword2").click(function () {
    if ($(this).val() == "请输入您要查找的图纸名称...") {
        $(this).val("");
    }
});
$(".keyword,.keyword2").blur(function () {
    if ($(this).val() == "") {
        $(this).val("请输入您要查找的图纸名称...");
    }
});
function clearStr(_type) {
    var txt;
    if (_type == 1) {
        txt = $(".keyword").val();
    } else {
        txt = $(".keyword2").val();
    }
    if (txt == "请输入您要查找的图纸名称...") {
        alert("请输入您要查找的图纸名称。");
        return false;
    }
    txt = clearString(txt).trim();
    location.href = "/paper/search?keyword=" + escape(txt);
}
//去除特殊字符
function clearString(s) {
    var pattern = new RegExp("[`%~!@#$^&*()=|{}':;,\\[\\].<>/?~;]")
    var rs = "";
    for (var i = 0; i < s.length; i++) {
        rs = rs + s.substr(i, 1).replace(pattern, '');
    }
    return rs;
}
//图标统计跳转
function GoStat(name) {
    window.location.href = "/" + name + $('.qishu').val() + "#chart";
}
/**
* 添加收藏夹
*/
function addFavorite(sUrl, sTitle) {
    try {
        window.external.addFavorite(sUrl, sTitle);
    } catch (e) {
        try {
            window.external.addToFavoritesBar(sUrl, sTitle);
        } catch (e) {
            try {
                window.sidebar.addPanel(sTitle, sUrl, "");
            }
            catch (e) {
                alert("加入收藏失败，请使用Ctrl+D进行添加");
            }
        }
    }
}
function SetHome(obj) {
    try {
        obj.style.behavior = 'url(#default#homepage)';
        obj.setHomePage('www.6hck.com');
    } catch (e) {
        if (window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            } catch (e) {
                alert("抱歉，此操作被浏览器拒绝！\n\n请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为'true'");
            };
        } else {
            alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将'www.6hck.com'设置为首页。");
        };
    };
};

function memberstats() {
    var lastCookie = getCookie("dz_last_time");

    var lastTime = new Date(lastCookie);
    var now = new Date();
    var t = (now - lastTime) / 1000;
    if (!lastCookie || t > 1800) {
        $.post("/member/stats", null, null);
    }
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=")
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1
            c_end = document.cookie.indexOf(";", c_start)
            if (c_end == -1) c_end = document.cookie.length
            return unescape(document.cookie.substring(c_start, c_end))
        }
    }
    return ""
}

function checkPhoneActivate() {
    if (!member.isPhoneActivate) {
        $.dialog({
            title: "提示",
            padding: '5px',
            content: "<div>您的账号未进行手机激活，暂时不能进行发帖操作，请激活账号后再进行操作。<br/><br/></div>",
            button: [
            {
                value: '马上激活', callback: function () {
                    $.dialog.loading.show();
                    window.open('/usercenter/index/2', 'newWin');
                }, focus: true
            }
            ]
        });
    }
    return member.isPhoneActivate;

}



var mails = { "@163.com": "http://email.163.com/", "@126.com": "http://email.163.com/", "@yeah.net": "http://email.163.com/", "@qq.com": "http://mail.qq.com/", "@gmail.com": "http://gmail.google.com/", "@yahoo.com": "http://mail.yahoo.com/", "@sina.com": "https://mail.sina.com.cn/", "@sohu.com": "http://mail.sohu.com/", "@outlook.com": "http://www.outlook.com/", "@hotmail.com": "http://www.outlook.com/", "@tom.com": "http://mail.tom.com/", "@21cn.com": "http://mail.21cn.com/", "@139.com": "http://mail.10086.cn/", "@189.cn": "http://webmail16.189.cn/webmail/", "@aliyun.com": "http://mail.aliyun.com/alimail/" }; function getMailAddr(mail) {
    if (mail && mail.indexOf("@") > 0) { var suffix = mail.substr(mail.indexOf("@")); if (mails[suffix]) { return mails[suffix]; } }
    return "";
}

$(document).ready(function (e) {
    $("#pushSx").click(function () {
        $("#sxBetData").show();
        $("#hmBetData").hide();
        $("#pushHm").removeClass("hover");
        $("#pushSx").addClass("hover");
    });
    $("#pushHm").click(function () {
        $("#sxBetData").hide();
        $("#hmBetData").show();
        $("#pushSx").removeClass("hover");
        $("#pushHm").addClass("hover");
    });



    $("#changeHover a").click(function () {

        $("#changeHover a").removeClass("hover");
        $(this).addClass("hover");

        if ($(this).attr("id") == "week") {
            $("#sxWeekRank").css({ "display": "block" });
            $("#hmWeekRank").css({ "display": "none" });
            $("#sxTotalRank").css({ "display": "none" });
            $("#hmTotalRank").css({ "display": "none" });

            $("#changeHover2 a").removeClass("hover");
            $("#sx").addClass("hover");

            $("#spanWeek").show();
            $("#spanQuarter").hide();
        }
        else if ($(this).attr("id") == "total") {

            $("#sxTotalRank").css({ "display": "block" });
            $("#hmTotalRank").css({ "display": "none" });
            $("#sxWeekRank").css({ "display": "none" });
            $("#hmWeekRank").css({ "display": "none" });

            $("#changeHover2 a").removeClass("hover");
            $("#sx").addClass("hover");

            $("#spanWeek").hide();
            $("#spanQuarter").show();
        }

    });


    $("#changeHover2 a").click(function () {

        $("#changeHover2 a").removeClass("hover");
        $(this).addClass("hover");

        if ($("#week").hasClass("hover")) {
            $("#sxTotalRank").css({ "display": "none" });
            $("#hmTotalRank").css({ "display": "none" });

            if ($("#sx").hasClass("hover")) {
                $("#hmWeekRank").css({ "display": "none" });
                $("#sxWeekRank").css({ "display": "block" });
            } else {
                $("#hmWeekRank").css({ "display": "block" });
                $("#sxWeekRank").css({ "display": "none" });
            }

        } else if ($("#total").hasClass("hover")) {
            $("#sxWeekRank").css({ "display": "none" });
            $("#hmWeekRank").css({ "display": "none" });

            if ($("#hm").hasClass("hover")) {
                $("#sxTotalRank").css({ "display": "none" });
                $("#hmTotalRank").css({ "display": "block" });
            } else {
                $("#sxTotalRank").css({ "display": "block" });
                $("#hmTotalRank").css({ "display": "none" });
            }
        }

    });

    var clickTimes = getCookie("click_times");
    clickTimes = clickTimes ? parseInt(clickTimes) + 1 : 1;
    document.cookie = "click_times=" + clickTimes + ";path=/";
});
jQuery.cookie = function (a, b, c, d) { var e, f, g, h, i, j, k, l, m, n, o, p, q, r, s; if ("undefined" == typeof c) { if (m = null, document.cookie && "" != document.cookie) for (n = document.cookie.split(";"), o = 0; o < n.length; o++) if (p = jQuery.trim(n[o]), p.substring(0, a.length + 1) == a + "=") { if (m = decodeURIComponent(p.substring(a.length + 1)), "undefined" != typeof b && null != b && "" != b) for (q = m.toString().split("&"), r = 0; r < q.length; r++) { if (s = jQuery.trim(q[r]), s.substring(0, b.length + 1) == b + "=") { m = decodeURIComponent(s.substring(b.length + 1)); break } m = void 0 } break } return m } if (d = d || {}, null === c && (c = "", d.expires = -1), e = "", d.expires && ("number" == typeof d.expires || d.expires.toUTCString) && ("number" == typeof d.expires ? (f = new Date, f.setTime(f.getTime() + 1e3 * d.expires)) : f = d.expires, e = "; expires=" + f.toUTCString()), g = d.path ? "; path=" + d.path : ";path=/", h = d.domain ? "; domain=" + d.domain : "", i = d.secure ? "; secure" : "", "object" == typeof c) { j = 0, k = ""; for (l in c) j > 0 && (k += "&"), k += l + "=" + encodeURIComponent(c[l]), j++; c = k } else c = encodeURIComponent(c); document.cookie = [a, "=", c, e, g, h, i].join("") };
