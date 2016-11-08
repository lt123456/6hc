// 抽獎活動 JavaScript Document
$(function(){
//cookie
	//創建cookie
    function setCookie(c_name,value,expiredays){    //cookie 的名称、值以及过期天数
        var exdate=new Date();
        exdate.setDate(exdate.getDate()+expiredays);
        document.cookie=c_name+"="+escape(value)+((expiredays==null)?"":";expires="+exdate.toGMTString());
    }
    
    //取出指定的cookie
    function getCookie(c_name){
        if(document.cookie.length>0){
            c_start=document.cookie.indexOf(c_name+"=");
            if(c_start!=-1){
                c_start=c_start+c_name.length+1;
                c_end=document.cookie.indexOf(";",c_start);
                if(c_end==-1){
                    c_end=document.cookie.length;
                }
                return unescape(document.cookie.substring(c_start,c_end));
            }
        }
        return "";
    }

/*
***********************
*/
	
	//書角效果
	$('#lucky_entrrance').hover(
		function(){
			$('#lucky_entrrance .msg_block').stop().animate({
				width:103
			},280);
			$('#lucky_entrrance img').stop().animate({
				right:33
			},280);
		},
		function(){
			$('#lucky_entrrance .msg_block').stop().animate({
				width:70
			},280);
			$('#lucky_entrrance img').stop().animate({
				right:0
			},280);
		}
	);
function init(){
	//點擊關閉到....
	$('#f_huodong .close').on('click',function(e){
		e.preventDefault();
		//設置cookie 一星期
		setCookie("index_diaocha_150626", "no", 70);
		$('#f_huodong .close, .shade_layer').css({display:'none'});
		$('#f_huodong').fadeOut();
		$('#f_huodong .img').css({ display: "none" });
	});
	
}
function show() {
    $('.shade_layer, #f_huodong').css({ display: "block" });
    $(".shade_layer").css({ "height": $(document).height() + "px" });
}



    //获取浏览器参数
function GetQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}var utm = GetQueryString("utm");
if (utm == "6hck") {
    $("#f_huodong .img").css("background-image", "url(http://resck.56hx.com/themes/default/images/huodong/domain_warn.png)");
    $("#f_huodong .link1").attr("href", "javascript:addFavorite('http://www.6hck.co', '六合彩库')");
    setTimeout(function () { init(); show(); }, 300);
} else {
    //手机不出弹出窗口
    if (!(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent)))) {
        //cookie判斷
        var isNew = getCookie("index_diaocha_150626");
        if (isNew != "no") {
            show();
            setTimeout(function () {
                init();
            }, 30)
        }
    }
}
});


