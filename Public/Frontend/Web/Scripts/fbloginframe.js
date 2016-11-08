(function () {
    var f = geturl(), buttonId = getParam(f, "id"), buttonClass = getParam(f, "class"); if (buttonId) { $("#" + buttonId).bind("click", buttonClick); }
    if (buttonClass) { $("." + buttonClass).bind("click", buttonClick); }
    $("#vipC_loginBlock_close").live("click", function () { $("#loginBlock").remove(); $(".loginframe").fadeOut("300", function () { $(this).remove(); }); }); function buttonClick() {
        var style = document.createElement("style"); var stylesTxt = " .vipC_loginBlock_close {background-image: url(http://rescsj.56hx.com/themes/images/vipc_close.png);background-position: 0 0;background-repeat: no-repeat;width: 34px;height: 34px;position: absolute;left: 300px;top: -10px;cursor: pointer;z-index: 10002;}.loginframe {background: url(http://rescsj.56hx.com/themes/images/stat/busy_anim.gif) no-repeat center #fff; width: 314px;height: 370px; position: fixed;_position: absolute;top: 50%; left: 50%;margin: -145px 0 0 -157px;border-radius: 4px;z-index: 10001; }.loginBlock { width: 100%;height: 100%;background: #000;filter: alpha(opacity=50);-moz-opacity: 0.5;opacity: 0.5;position: fixed;_position: absolute;z-index: 10000;top: 0;left: 0;}"; (document.getElementsByTagName("head")[0] || document.body).appendChild(style); if (style.styleSheet) { style.styleSheet.cssText = stylesTxt; } else { style.appendChild(document.createTextNode(stylesTxt)); }
        document.body.appendChild(style); var div = document.createElement("div"); div.width = "314px"; div.height = "370px"; div.className = "loginframe"; var closebutton = document.createElement("div"); closebutton.id = "vipC_loginBlock_close"; closebutton.className = "vipC_loginBlock_close"; div.appendChild(closebutton); var iframe = document.createElement("iframe"); iframe.className = "loginframe"; iframe.name = "loginframe"; iframe.id = "loginframe"; iframe.src = "/sso/feedbacklogin"; iframe.scrolling = "no"; iframe.width = "314px"; iframe.height = "370px"; iframe.frameBorder = 0; iframe.allowTransparency = "true"; div.appendChild(iframe); var loginBlock = document.createElement("div"); loginBlock.className = "loginBlock"; loginBlock.id = "loginBlock"; document.body.appendChild(loginBlock); document.body.appendChild(div);
    }
    function geturl() {
        var K = ""; try { throw Error("获取js路径有误"); } catch (L) { if (L.fileName) { K = L.fileName; } else { if (L.stack) { K = (L.stack.match(/at\s+(.*?):\d+:\d+/) || ["", ""])[1]; } else { if (L.sourceURL) { K = L.sourceURL; } } } }
        if (K) { return K; }
        var s = document.getElementsByTagName("script"), J = s[s.length - 1]; K = document.querySelector ? J.src : J.getAttribute("src", 4); return K;
    }
    function getParam(J, K) {
        var s; if (J.indexOf(K) > 0) { K = new RegExp("(\\?|&)" + K + "=([^&]+)(&|$)", "i"); var M = J.match(K); if (M.length > 1) s = M[2]; else s = false; } else { s = false; }
        return s;
    }
})();