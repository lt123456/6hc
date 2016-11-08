var regUrl = new RegExp("(https?://[a-zA-Z0-9\\.\\/\\?&=_%\\-#:]{8,150})", "ig");
var regSelf = new RegExp("^https?://(www\\.)?(6hck\\.com|1396\\.me|1396me\\.com|1393\\.me|boworld\\.net)/", "i");
var regImg = new RegExp("\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$", "i");
var regJS = new RegExp("^https?://javascript:", "i");
var regUbb = new RegExp("\\[(/?[a-zA-Z0-9#=]{1,20})\\]", "ig");
var regReplyName = new RegExp(":回复 @([^:]+):", "i");

function isImageUrl(url) {
    var p = url.indexOf('?');
    if (p != -1) {
        url = url.substring(0, p);
    }
    return regImg.test(url) && !regJS.test(url);
}
function parseContent(obj) {
    var text = obj.html();
    text = parseUrlImage(text);
    text = parseUbbCode(text);
    obj.html(text);
}
function parseUrlImage(text) {
    text = text.replace(regUrl, function ($0) {
        if (isImageUrl($0)) {
            return '<a href="' + $0 + '" target="_blank" title="点击查看原图">' +
                           '<img src="' + $0 + '" border="0" title="' + $0 + '" onload="if(this.width>920)this.width=920;" style="max-width:920px;max-height:1200px;border:1px solid #D8CACA;background-color:#f0f0f0;padding:6px;" class="postimg" />' +
                           '</a>';
        } else if (regSelf.test($0)) {
            return '<a href="' + $0 + '" target="_blank">' + $0 + '</a>';
        } else {
            return $0;
        }
    });
    return text;
}

function parseUbbCode(text) {
    text = text.replace(regUbb, function ($0, code) {
        if (code == "b") {
            return "<strong>";
        } else if (code == "/b") {
            return "</strong>";
        } else if (code.startWith("ema")) {
            return "<img src='" + faceUrl + '/ema/' + parseInt(code.substring(3)) + ".gif'>";
        } else if (code.startWith("color=")) {
            return "<span style=\"color:" + code.substring(6) + ";float:none;\">";
        } else if (code == "/color") {
            return "</span>";
        }
        return $0;
    });
    return text;
}