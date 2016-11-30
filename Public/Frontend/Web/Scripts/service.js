$(document).ready(function () {
    $("#floatShow").bind("click", function () {
        $('#onlineService').animate({ width: 'show', opacity: 'show' }, 'normal', function () { $('#onlineService').show(); }); $('#floatShow').attr('style', 'display:none'); $('#floatHide').attr('style', 'display:block');
        return false;
    });
    $("#floatHide").bind("click", function () {
        $('#onlineService').animate({ width: 'hide', opacity: 'hide' }, 'normal', function () { $('#onlineService').hide(); }); $('#floatShow').attr('style', 'display:block'); $('#floatHide').attr('style', 'display:none');
    });
    $(document).bind("click", function (event) {
        if ($(event.target).isChildOf("#online_qq_layer") == false) {
            $('#onlineService').animate({ width: 'hide', opacity: 'hide' }, 'normal', function () { $('#onlineService').hide(); }); $('#floatShow').attr('style', 'display:block'); $('#floatHide').attr('style', 'display:none');
        }
    });
    jQuery.fn.isChildAndSelfOf = function (b) {
        return (this.closest(b).length > 0);
    };
    jQuery.fn.isChildOf = function (b) {
        return (this.parents(b).length > 0);
    };
    $(".lot-items").hover(function () {
        $(this).find(".vertical-line").css("display", "none");
        $(".items-cont").css("display", "block");
    }, function () {
        $(this).find(".vertical-line").css("display", "block");
        $(".items-cont").css("display", "none");
    });
    $(".items-cont").hover(function () {
        $(".lot-item").addClass("lot-item-hover");
        $(".lot-item b").addClass("b-hover");
    }, function () {
        $(".lot-item").removeClass("lot-item-hover");
        $(".lot-item b").removeClass("b-hover");
    });
    $("#bookmarkli").hover(function () {
        if ($("#inbox li").length > 0)
            $("#inbox").stop(true, true).slideDown(0);
    }, function () {
        $("#inbox").stop(true, true).slideUp(150);
    });
    $("#inbox").hover(function () {
        $("#bookmarkli b").addClass("b-hover");
        $("#bookmarkli a").addClass("bookmarkli-hover");
    }, function () {
        $("#bookmarkli b").removeClass("b-hover");
        $("#bookmarkli a").removeClass("bookmarkli-hover");
    });

    $.post('/service.ashx', {
        "ajaxhandler": 'getmember'
    }, function (result) {
        if (result.success) {
            $(".signin").show();
            $(".member-name").html(result.name);
        } else {
            $(".unsignin").show();
        }
    }, 'json');
    $.post('/service.ashx', {
        "ajaxhandler": 'getbookmark'
    }, function (result) {
        if (result.success) {
            $("#inbox").html(result.cont);
        }
    }, 'json');
    $('#logout').click(function () {
        if (confirm('是否确认要退出？')) {
            $.post('/login', { ajaxhandler: 'logout' }, function () {
                self.location.href = '/';
            }, 'json');
        }
        return true;
    });
}); 
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