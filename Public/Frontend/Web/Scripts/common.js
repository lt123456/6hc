(function (d) {
    d['okValue'] = '确定';
    d['cancelValue'] = '取消';
    d['title'] = '消息';
    d['lock'] = true;
})($.dialog.defaults);


function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}


$(function () {

    //防止网页嵌套
    try {
        if (self != top && top.location.host == self.location.host) {
            top.location.href = self.location.href;
        }

    } catch (e) {
    }


    $.dialog.loading = {
        show: function (content) {
            var text = '<img src="http://resck.56hx.com/images/loading.gif" align="absmiddle" /> ' + (content ? content : '正在处理，请稍候……');
            if ($.dialog.get('_diloag_loading')) {
                $.dialog.get('_diloag_loading').content(text).visible();
            } else {
                $.dialog({
                    title: false,
                    id: '_diloag_loading',
                    padding: '5px',
                    content: text,
                    ok: false,
                    cancel: false
                });
            }
        },
        hide: function () {
            $.dialog.get('_diloag_loading').hidden();
        }
    };
    $.dialog.alert = function (title, content, okfunc) {
        $.dialog({
            title: title,
            padding: '5px',
            content: content,
            ok: function () {
                if (okfunc) return okfunc();
            }
        });
    };
    $.dialog.confirm = function (title, content, okfunc, cancelfunc) {
        $.dialog({
            title: title,
            padding: '5px',
            content: content,
            ok: function () {
                if (okfunc) return okfunc();
                return true;
            },
            cancel: function () {
                if (cancelfunc) return cancelfunc();
                return true;
            }
        });
    };

    $('[jvcorrecttip]').each(function () {
        var o = $(this);
        if (o.attr('jvcorrecttip') == '') {
            o.attr('jvcorrecttip', '<img src="/template/images/true.gif" align="absmiddle" width="24" height="24" border="0" />');
        }
    });

    $(".top_ad .closeBtns").click(function () {
        $(".top_ad").fadeOut(100);

        //元旦元素
        $(".ydImg").css({ "top": "-30px" });

    });

    $(".topselectBtns").hover(function () {
        $(".hiddenBlock").show();
    }, function () {
        //$(".hiddenBlock").hide();
    });

    $(".top_jiamaoBackground").mouseleave(function () {
        $(".hiddenBlock").hide();
    });
    $(".hiddenBlock span").hover(function () {
        $(this).addClass("hover");
    }, function () {
        $(this).removeClass("hover");
    });


    //广告相关
    var utm = getQueryString("utm");  
    utm = utm ? "utm=" + utm : "";
    if (utm.length > 0) {
        var allink = $("a[href]");
        for (var i = allink.length - 1; i > -1; i--) {
            var href = $(allink[i]).attr("href");
            if (href.indexOf("javascript") == -1 && href.indexOf("utm=") == -1) {
                if (href.indexOf("?") == -1) {
                    $(allink[i]).attr("href", href + "?" + utm);
                } else {
                    $(allink[i]).attr("href", href + "&" + utm);
                }
            }
        }
    }
});





//水印
(function ($) {
    $.fn.extend({
        "watermark": function (options) {
            var word = this.attr("watermark");
            var opts = $.extend({}, defualts, options);
            if (this.val() == "") {
                this.css("color", defualts.emptycolor);
                this.val(word);
            }
            this.focus(function () {
                if (this.value == word) {
                    $(this).css("color", defualts.emptycolor);
                    $(this).val("");
                } else {
                    if ($(this).getColor() == defualts.emptycolor.toUpperCase()) {
                        $(this).css("color", defualts.color);
                    }
                }
            });
            this.keyup(function () {
                if ($(this).getColor() == defualts.emptycolor.toUpperCase()) {
                    $(this).css("color", defualts.color);
                }
            });
            this.blur(function () {
                if (this.value == "") {
                    $(this).css("color", defualts.emptycolor);
                    $(this).val(word);
                } else {
                    if ($(this).getColor() == defualts.emptycolor.toUpperCase()) {
                        $(this).css("color", defualts.color);
                    }
                }
            });
            return this;
        }
    });
    var defualts = {
        emptycolor: "#333333",
        color: "#000000"
    };
})(window.jQuery);

//去除特殊字符
function clearString(s) {
    var pattern = new RegExp("[`%~!@#$^&*()=|{}':;,\\[\\].<>/?~;]")
    var rs = "";
    for (var i = 0; i < s.length; i++) {
        rs = rs + s.substr(i, 1).replace(pattern, '');
    }
    return $.trim(rs);
}

$.fn.getColor = function () {
    var rgb = $(this).css('color');
    if (!rgb) {
        return '#FFFFFF'; //default color
    }
    var hex_rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) { return ("0" + parseInt(x).toString(16)).slice(-2); }
    if (hex_rgb) {
        return ("#" + hex(hex_rgb[1]) + hex(hex_rgb[2]) + hex(hex_rgb[3])).toUpperCase();
    } else {
        return rgb.toUpperCase(); //ie8 returns background-color in hex format then it will make                 compatible, you can improve it checking if format is in hexadecimal
    }
}
//回复文本编码换行
function multiLineHtmlEncode(content) {
    return $("<div/>").text(content).html().replace(/\r\n|\r|\n/g, "<br />");
}
// Checkbox全选
function selectAll(obj, tagname) {    //全部选中
    var chks = document.getElementsByName(tagname);
    for (var i = 0; i < chks.length; i++) {
        chks[i].checked = obj.checked;
    }
}
//  获取所有选择的Checkbox的值
function getAllSelected(tagname) {
    var chks = document.getElementsByName(tagname);
    var ids = [];
    for (var i = 0; i < chks.length; i++) {
        if (chks[i].checked == true) {
            ids.push(chks[i].value);
        }
    }
    return ids.join(",");
}



///替换全部
if (!String.prototype.replaceAll) {
    String.prototype.replaceAll = function (t, v) {
        return this.replace(new RegExp(t, 'g'), v);
    };
}

//去除首位空白
if (!String.prototype.Trim) {
    String.prototype.Trim = function () {
        return this.replace(/(^\s*)|(\s*$)/g, "");
    }
}

String.prototype.startWith = function (text) {
    if (this.length >= text.length) {
        return this.substring(0, text.length) == text;
    } else {
        return false;
    }
}
function loadJs(src, callback) {
    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = src;
    if (typeof callback == "function") {
        s.onreadystatechange = function () {
            if (s.readyState == "loaded" || s.readyState == "complete") {
                callback();
                s.onreadystatechange = null
            }
        };
        s.onload = K
    }
    document.body.appendChild(s)
}

function IsMobile() {
    if (/Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))) {
        try {
            if (/Android|Windows Phone|webOS|iPhone|iPod|iPad|BlackBerry/i.test(navigator.userAgent)) {
            }
        } catch (e) {
        }
        return true;
    } else {
        return false;
    }
}