// 六合彩庫 hc.js JavaScript Document
/*base by jQuery v1.7.2*/
$(function () {
    var ie86 = ! -[4, ];

    //輸入框placeholder-----IE
    if (ie86) {
        $('#contrs input.enteryourname')
		.val('请输入用户名').css({ color: '#9A9A9A' })
		.focus(function () {
		    $(this).val() == '请输入用户名' && $(this).val('').css({ color: '#444' });
		})
		.blur(function () {
		    $(this).val() == '' && $(this).val('请输入用户名').css({ color: '#9A9A9A' });
		});
    }

    $('.fb_ft tbody textarea')
	.blur(function () {
	    $(this).val().match(/^\s*$/g) && $(this).css({ backgroundPosition: 'center center' }).val('');
	})
	.focus(function () {
	    $(this).css({ backgroundPosition: 'right bottom' });
	})

    //號碼榜select小組件
    var $select_wrap = $('li.bang, .drawlist, #theme_lt ul li.last_child');
    $select_wrap.on({
        mouseenter: function () {
            $(this).children('a').addClass('hover').siblings().css({ display: 'block' });
        },
        mouseleave: function () {
            $(this).children('a').removeClass('hover').siblings().css({ display: 'none' });
        }
    });

    $(".paginator a").live("click", function () {
        updateHistory(betType, $(this).attr("data-page"));
        // console.log($(this).attr("data-page"));
    });

    function updateHistory(type, page, period, jump, isTotle) {
        if (page < 1 || type < 0 || type > 2) return;

        var memberName = $("#searchUser").val() == "请输入用户名" ? "" : $("#searchUser").val();
        var year = $("#searchYear").val();
        period = period ? period : $("#searchPeriod").val();
        var isCurPeriod = $("#searchCurrent").attr("class").indexOf("checked") > -1 ? true : false;
        if (isCurPeriod) {
            period = betPeriod % 1000;
        }

        var sort = 1;
        var param = {
            type: type,
            period: period,
            year: year,
            result: $("#searchResult").val(),
            memberName: memberName,
            sort: sort,
            isMind: ($("#searchMine").attr("class").indexOf("checked") > -1 ? true : false),
            isCurPeriod: isCurPeriod,
            page: page,
            userId: $("#searchUserId").val()
        };
        ajax_remind_start();
        $.post("/leitai/historydata", param, function (result) {
            if (result) {
                $(".paginator").html(rebuildPage(result.page, result.pagecount));
                $("#historyTable tbody tr").remove();
                var datas = result.datas;
                if (datas.length > 0) {
                    var html = '';
                    for (var i = 0; i < datas.length; i++) {
                        html = '<tr class="' + (i % 2 == 0 ? "even" : "odd") + '">';
                        if (datas[i].QuarterRanking > 0) {
                            var title = "总榜参与" + datas[i].QuarterBetCount + "期|中" + datas[i].QuarterWinCount + "期|现在胜率" + datas[i].WinRateOfYear + "|排名" + datas[i].QuarterRanking;
                            html += '<td><span class="membername" memberid="' + datas[i].MemberId + '"><a class="medal" title="' + title + '">' + datas[i].MemberName + '</a></span></td>';
                        }
                        else {
                            html += '<td><span class="membername" memberid="' + datas[i].MemberId + '">' + datas[i].MemberName + '</span></td>';
                        }
                        html += '<td><span><time>' + datas[i].BetDate + '&nbsp;&nbsp;' + datas[i].BetTime + '</time></span></td>';
                        html += '<td><span>第' + datas[i].Period + '期</span></td>';
                        html += '<td><span>' + datas[i].BetTarget + '</span></td>';
                        html += '<td><span class="result_' + datas[i].Result + '">' + datas[i].ResultText + '</span></td>';
                        html += '<td><span>' + datas[i].QuarterWinCount + '</span></td>';
                        html += '<td><span>' + datas[i].QuarterBetCount + '</span></td>';
                        if (datas[i].WinRateOfYear == "0.00%") {
                            html += '<td><span>' + '0%' + '</span></td>';
                        }
                        else {
                            html += '<td><span>' + datas[i].WinRateOfYear + '</span></td>';
                        }
                        html += '</tr>';
                        $("#historyTable").append(html);
                    }
                } else {
                    $("#historyTable tbody").append("  <tr><td colspan='8'>没有找到相关结果</td></tr>");
                }
                ajax_remind_closed();

                if (jump != undefined) {
                    location.href = "#aSelectPeriod";
                }
            }

            //显示投注结果统计
            if (isTotle) {
                getCurrentTotleLog(betPeriod, betType);
            }

        }, 'json').error(function () {
            $.dialog.alert("提示", "获取数据失败，请稍后再试");
        });
    }

    //选择期数、状态
    //1下拉
    $('.sel a.sel_btn').on({
        click: function (e) {
            e.preventDefault();
            e.stopPropagation(); //阻止冒泡到document
            $(this).siblings('span').fadeIn(100).css({ display: 'block' }).end()
			.parent().siblings('.sel').find('span').fadeOut(100);
        }
    });
    //2選擇
    $('p.sel span a').on({
        click: function (e) {
            e.preventDefault();
            e.stopPropagation(); //阻止冒泡到document
            var strSelect = $(this).text();
            $(this).parent().fadeOut(230).siblings('a.sel_btn').html(strSelect + '<i></i>');
            $(this).parent().siblings('input').val($(this).attr("value")).trigger('change');
        }
    });
    //3任意位置點擊(document)消失下拉框
    $(document).on('click', function () {
        $('p.sel span').css({ display: 'none' });
    });
    $("#contrs input[type='hidden']").on("change", function () {
        $("#searchUserId").val(0);
        $("#searchMine").removeClass("checked");
        $("#searchCurrent").removeClass("checked");
        updateHistory(betType, 1);
    });
    $("#searchBtn").on("click", function () {
        $("#searchUserId").val(0);
        $("#searchMine").removeClass("checked");
        $("#searchCurrent").removeClass("checked");
        updateHistory(betType, 1);
    });
    $("#searchCurrent").parent('.ckb').on("click", 'b,label', function () {
        $("#searchCurrent").toggleClass("checked");
        $("#searchUserId").val(0);
        $("#searchUser").val("");
        $(".selectPeriod a.sel_btn").html("全部期数<i></i>");
        $("#searchPeriod").val(0);
        $(".selectResult a.sel_btn").html("全部状态<i></i>");
        $("#searchResult").val(-1);
        updateHistory(betType, 1);
    });
    $("#searchMine").parent('.ckb').on("click", 'b,label', function (e) {
        if (!member.userLogined) {
            $.dialog.confirm("提示", "请先登录！", function () {
                window.location.href = "/sso/login";
            });
            return false;
        }
        $("#searchMine").toggleClass("checked");
        $("#searchUserId").val(0);
        $("#searchUser").val("");
        $(".selectPeriod a.sel_btn").html("全部期数<i></i>");
        $("#searchPeriod").val(0);
        $(".selectResult a.sel_btn").html("全部状态<i></i>");
        $("#searchResult").val(-1);
        updateHistory(betType, 1);
    });
    $(".membername").live("click", function () {

        $("#searchUserId").val($(this).attr("memberid"));
        $("#searchMine").removeClass("checked");
        $("#searchCurrent").removeClass("checked");
        $(".selectPeriod a.sel_btn").html("全部期数<i></i>");
        $("#searchPeriod").val(0);
        $(".selectResult a.sel_btn").html("全部状态<i></i>");
        $("#searchResult").val(-1);
        $("#searchUser").val("");
        $("#searchUser").attr("placeholder", "请输入用戶名");

        updateHistory(betType, 1, null, true);
    });

    function ajax_remind_start() {
        $('#lt_tbl_wrap .shade_table,#lt_tbl_wrap .ajax_loading_img').css({ display: "block", opacity: 1 });
    };
    function ajax_remind_closed() {
        $('#lt_tbl_wrap .shade_table,#lt_tbl_wrap .ajax_loading_img').fadeOut(220);
    };

    $(".gameExplanation").mouseenter(function (e) {
        $(this).addClass("gameExplanationHover");
    });
    $(".gameExplanation").mouseleave(function () {
        $(this).removeClass("gameExplanationHover");
    });

    $(".order_f tbody tr").live("mouseenter", function () {
        $(this).addClass("trHover");
    });
    $(".order_f tbody tr").live("mouseleave", function () {
        $(this).removeClass("trHover");
    });

    //若本期已推荐，显示推荐选择；否则绑定各相关事件  
    if ($("#periodBet").length > 0) {
        getCurrentTotleLog(betPeriod, betType);
    }
    else {
        //推號碼點擊選中
        $('#twl_zodiac ul.t_num a').live('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('choose');
            var choose = $('#twl_zodiac ul.t_num .choose');
            if (choose.length > 6) {
                $.dialog.alert("提示", "每次最多推荐6个号码！");
                $(this).removeClass('choose');
                return false;
            }
            $("#selectCount").html(choose.length);
        });

        //推荐生肖点击选中
        $("#shengxiao li").live("click", function () {
            $(this).toggleClass("hover");
            var choose = $("#shengxiao li.hover");
            if (choose.length > 4) {
                $.dialog.alert("提示", "每次最多推荐4個生肖！");
                $(this).removeClass("hover");
                return false;
            }
            $("#selectCount").html(choose.length);
        });

        //推荐生肖hover事件
        $("#shengxiao li").mouseenter(function () {
            $(this).addClass("over");
        });
        $("#shengxiao li").mouseenter(function () {
            $(this).removeClass("over");
        });

        //重设
        $(".reset").on("click", function () {
            $('#twl_zodiac ul.t_num .choose').removeClass("choose");
            $("#shengxiao li").removeClass("hover");
            $("#selectCount").html("0");
        });

        //提交号码
        $("#numSubmit").on("click", function () {
            if (!member.userLogined) {
                $.dialog.confirm("提示", "请先登录！", function () {
                    window.location.href = "/sso/login";
                });
                return false;
            }
            var choose = $('#twl_zodiac ul.t_num .choose');
            if (choose.length > 6) {
                $.dialog.alert("提示", "每次最多推荐6个号码！");
                return false;
            }
            var postdata = { type: 1, target: "" };
            for (var i = 0; i < choose.length; i++) {
                postdata.target += "," + choose.eq(i).children("span").html();
            }
            postData(postdata);
        });

        //提交生肖
        $("#sxSubmit").on("click", function () {
            if (!member.userLogined) {
                $.dialog.confirm("提示", "请先登录！",
                    function () {
                        window.location.href = "/sso/login";
                    });
                return false;
            }
            var choose = $("#shengxiao li.hover");
            if (choose.length > 4) {
                $.dialog.alert("提示", "每次最多推荐4個生肖！");
                return false;
            }
            var postdata = { type: 2, target: "" };
            for (var i = 0; i < choose.length; i++) {
                postdata.target += "," + choose.eq(i).children("span").html();
            }
            postData(postdata);
        });

        function postData(obj) {
            obj.period = betPeriod;
            $.dialog.loading.show();
            $.post("/leitai/periodbet", obj, function (result) {
                $.dialog.loading.hide();
                if (result.success) {
                    $.dialog.alert("提示", "推荐成功！");
                    $(".js_notice2").hide();
                    $(".js_notice3").hide();
                    $('#twl_zodiac ul.t_num a').die("click");
                    $("#shengxiao li").die("click");

                    //记录投注结果
                    periodBetval = obj.target;

                    updateHistory(betType, 1, betPeriod % 1000, null, true);
                } else {
                    $.dialog.alert("提示", result.message);
                }
            }, "json").error(function () {
                $.dialog.alert("提示", "推荐失败，请稍后重新尝试！");
                $.dialog.loading.hide();
            })
        }

    }

    //开奖倒计时
    if ($("#leftTime").length > 0) {
        window.setInterval(function () {
            var leftTime = $("#leftTime").html();
            var lHour = parseInt(leftTime.split(":")[0]);
            var lMin = parseInt(leftTime.split(":")[1]);
            var lSec = parseInt(leftTime.split(":")[2]);
            if (lSec > 0) {
                lSec--;
            }
            else {
                if (lMin > 0) {
                    lMin--;
                    lSec = 59;
                }
                else {
                    if (lHour > 0) {
                        lHour--;
                        lMin = 59;
                        lSec = 59;
                    }
                    else {
                        $("#leftTime").html("正在开奖");
                        return;
                    }
                }
            }
            $("#leftTime").html(pad(lHour, 2) + ":" + pad(lMin, 2) + ":" + pad(lSec, 2));
        }, 1000);
    }
});

//统计数据
function getCurrentTotleLog(p, t) {
    $.post("/leitai/GetCurrentPayTotleLog", { period: p, type: t }, function (result) {
        var htmltotle = '', tempHtml = "", tempHtmlCopy = "", clsArry = ["red_ball", "blue_ball", "green_ball"], colArry = ["blue", "gray", "red"];
        var clsIndex = 0, j = 1, colIndex = 0;   //没有百分比的字色是灰色，有百分比的字色是蓝色，百分比最高的前6个字色是红色
        var datas = result.datas;

       

        $("#vote_chart").empty();
        if (datas != null) {
            var len = len = datas.length;
            var ratio = 0;
            if (t == 2) {
                tempHtml = '<li style="width:45px;" ><p style="margin:0px;" >{ratio}%</p><p style="position: relative;margin:0px;height:0px;"></p><div class="percent" style="cursor:pointer" ><div class="percent_top" style="height: {surplus}%;"></div><div class="percent_bottom" style="height: {ratio}%;"></div></div><p style="cursor:pointer;margin:0px;">{name}</p></li>';

            }
            else {
                tempHtml = '<li><a data="{ratioOri}" href="javascript:;"><span class="{cls}">{name}</span></a><span class="ratiospan" style="color:{cor};padding: 0px 6px;text-align: center;">{ratio}%</span></li>';

            }
            //遍历  
            for (var i = 0; i < len; i++) {
                tempHtmlCopy = tempHtml;
                ratio = Number(datas[i].Ratio * 100).toFixed(2);
                tempHtmlCopy = tempHtmlCopy.replaceAll("{ratioOri}", datas[i].Ratio);
                tempHtmlCopy = tempHtmlCopy.replaceAll("{ratio}", ratio);
                tempHtmlCopy = tempHtmlCopy.replaceAll("{surplus}", (100 - ratio));
                tempHtmlCopy = tempHtmlCopy.replaceAll("{name}", datas[i].TotleName);

                if (t == 1) {
                    if (j == 1 || j == 2) {
                        clsIndex = 0;
                    }
                    else if (j == 3 || j == 4) {
                        clsIndex = 1;
                    }
                    else if (j == 5 || j == 6) {
                        clsIndex = 2;
                        if (j == 6) {
                            j = 0;
                        }
                    }
                    tempHtmlCopy = tempHtmlCopy.replaceAll("{cls}", clsArry[clsIndex]);
                    j++;

                    //字体颜色 
                    colIndex = 0
                    if (parseInt(ratio) == 0) {
                        colIndex = 1;
                    }
                    tempHtmlCopy = tempHtmlCopy.replaceAll("{cor}", colArry[colIndex]);

                }
                htmltotle += tempHtmlCopy
            }
            if (t == 2) {
                $("#vote_chart").append("<ul>" + htmltotle + "</ul>").show();
            }
            else {
                $(".t_num").html(htmltotle).show();
            }
        }

        //标识已投注记录
        var bet = $("#periodBet").val();
        if (bet == "" || bet == undefined || bet == null) {
            bet = periodBetval;
        }
        if (t == 2) {
            $("#shengxiao li").each(function () {
                var sx = $(this).children("span").html();
                if (bet.indexOf(sx) > -1) {
                    $(this).addClass("hover");
                }
            });
        }
        else {
            datas = result.datas.sort(function (a, b) {
                return b.TotleCount > a.TotleCount;
            }).slice(0, 6);
            //datas = datas.SortJson("desc", "TotleCount").slice(0, 6);
            $("#twl_zodiac ul.t_num a").each(function () {
                var num = $(this).children("span").eq(0).html();
                if (bet.indexOf(num) > -1) {
                    $(this).addClass("choose");
                }
                var data = Number($(this).attr("data"));
                //计算是否是最高6项之1
                for (var i = 0, len = datas.length; i < len; i++) {
                    if (data != "0" && Number(datas[i].Ratio) == data) {
                        $(this).next(".ratiospan").css({ "color": "red" })
                        break;
                    }
                }
            });
        }
    }, 'json').error(function () {
        $.dialog.alert("提示", "获取数据失败，请稍后再试");
    });
}

//补零
function pad(num, n) {
    var len = num.toString().length;
    while (len < n) {
        num = "0" + num;
        len++;
    }
    return num;
}
function rebuildPage(page, pagecount) {
    var firstno = page - 5;
    firstno = firstno > 1 ? firstno : 1;
    var lastno = page + 9;
    lastno = lastno > pagecount ? pagecount : lastno;
    var html = "";
    if (page > 1) {
        html += '<a href="javascript:void(0);"  data-page="1" class="jp-previous" >首页</a><a href="javascript:void(0);"  data-page="' + (page - 1) + '" class="jp-previous" >上一页</a>';
    }
    for (var i = firstno; i <= lastno; i++) {
        if (i == page) {
            html += '<a class="jp-current">' + page + '</a>';
        } else {
            html += '<a href="javascript:void(0);"  data-page="' + i + '" class="">' + i + '</a>';
        }
    }
    if (page < pagecount) {
        html += '<a href="javascript:void(0);"   data-page="' + (page + 1) + '" class="jp-next" >下一页</a><a href="javascript:void(0);"  data-page="' + pagecount + '" class="jp-next">尾页</a>';
    }
    return html;
}