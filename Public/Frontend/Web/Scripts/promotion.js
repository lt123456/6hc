$(function () {
    var innerHtml = "";
    $.ajax({
        cache: false,
        type: "GET",
        async: false,
        dataType: "jsonp",
        jsonpCallback: "promotion_jsonpCallback",
        url: "http://www.1396app.com/promotionapplication/GetAppListFor6hck?utm=lhck",
        success: function (result) {
            var strHtml = "";

            if (result.AndroidAppList.length > 0) {
                strHtml += ' <div class="shck_mg_title">' +
                                '<span>休闲手游</span>' +
                                '<a class="shck_mg_more" href="http://www.1396app.com/?yth" target="_blank">更多>></a>' +
                                '<div style="clear: both;"></div>' +
                                '<a class="shck_mg_android shck_mg_hover" href="javascript:void(0);"><font>安卓版</font></a>' +
                                '<a class="shck_mg_ios" href="javascript:void(0);"><font>苹果版</font></a>' +
                            '</div>';

                strHtml += '<div class="shck_mg_ul">';

                strHtml += '<ul id="ulPromotionAndroid">';
                for (var i = 0; i < result.AndroidAppList.length; i++) {
                    if (i == 7) {
                        strHtml += '<li class="shck_mg_lastLi">';
                    }
                    else {
                        strHtml += '<li>';
                    }
                    var href = result.AndroidAppList[i].Url + "?yth";
                    strHtml += '<a target="_blank" title="' + result.AndroidAppList[i].FullName + '" href="' + href + '"><img src="' + result.AndroidAppList[i].Icon + '" alt=""/></a>' +
                                '<a class="shck_mg_ul_name" target="_blank" title="' + result.AndroidAppList[i].FullName + '" href="' + href + '">' + result.AndroidAppList[i].Name + '</a>' +
                                '</li>';
                }
                strHtml += '</ul>';

                strHtml += '<ul id="ulPromotionIos" style="display:none;">';
                for (var i = 0; i < result.IosAppList.length; i++) {
                    if (i == 7) {
                        strHtml += '<li class="shck_mg_lastLi">';
                    }
                    else {
                        strHtml += '<li>';
                    }
                    var href = result.IosAppList[i].Url + "?yth";
                    strHtml += '<a target="_blank" title="' + result.IosAppList[i].FullName + '" href="' + href + '"><img src="' + result.IosAppList[i].Icon + '" alt=""/></a>' +
                                '<a class="shck_mg_ul_name" title="' + result.IosAppList[i].FullName + '" target="_blank" href="' + href + '">' + result.IosAppList[i].Name + '</a>' +
                                '</li>';
                }
                strHtml += '</ul>';
                strHtml += '<div class="clear"></div>';
                strHtml += '</div>';

                $(".shck_mobileGames").html(strHtml);
            }
        },
        error: function (xhr) {
            //alert(xhr.responseText);
        }
    });

    $(".shck_mg_android").live("mouseenter", function () {
        if ($("#ulPromotionAndroid").is(":hidden")) {
            $("#ulPromotionAndroid").show();
            $(".shck_mg_android").addClass("shck_mg_hover");

            $("#ulPromotionIos").hide();
            $(".shck_mg_ios").removeClass("shck_mg_hover");
        }
    });

    $(".shck_mg_ios").live("mouseenter", function () {
        if ($("#ulPromotionIos").is(":hidden")) {
            $("#ulPromotionIos").show();
            $(".shck_mg_ios").addClass("shck_mg_hover");

            $("#ulPromotionAndroid").hide();
            $(".shck_mg_android").removeClass("shck_mg_hover");
        }
    });

    $(".shck_mg_ul img").live("mouseenter", function () {
        $(this).parent().next().addClass("hover");
    });
    $(".shck_mg_ul img").live("mouseout", function () {
        $(this).parent().next().removeClass("hover");
    });
});

