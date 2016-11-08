$(function () {
    var table = "<table>";
    table = table + "<tr>";
    for (var i = 1; i <= 30; i++) {
        if (i == 11 || i == 21) {
            table = table + "</tr><tr>";
        }
        table = table + "<td>";
        table = table + "<img src='/res/face/ema/" + i + ".gif' data='[ema" + i + "]'/>";
        table = table + "</td>";
    }
    table = table + "</tr></table>";
    jQuery.fn.isChildOf = function (b) {
        return (this.parents(b).length > 0);
    };
    var isOpened1 = false;
    var isOpened2 = false;
    $(document).bind("click", function (event) {
        if (isOpened1) {
            if ($(event.target).isChildOf("#send_wrap .send") == false) {
                $(".face_cont").hide(300);
                isOpened1 = false;
                $(".send .face_cont table img").unbind("click");
            }
        }
        else if (isOpened2) {
            if ($(event.target).isChildOf(".u_content .replay_wrap") == false) {
                $(".face_cont").hide(300);
                isOpened2 = false;
                $(".face_cont table img").unbind("click");
            }
        }
    });
    $(".send .face").click(function () {
        if (!logined) return;
        if (isOpened1 == true) {
            $(".send .face_cont").hide(300);
            $(".send .face_cont table img").unbind("click");
            isOpened1 = false;
            return;
        }
        isOpened1 = true;
        if ($(".send .face_cont").html().length < 50) {
            $(".face_cont").html(table);
            $(".send .face_cont").show(300);
        }
        else {
            $(".send .face_cont").show(300);
        }
        var container = $("#" + $(this).attr("container"));
        $(".send .face_cont table img").bind("click", function (event) {
            var t = container.val();
            var watermarks = container.attr("watermark");
            if (watermarks && t == watermarks) {
                t = "";
            }
            container.focus().val(t + $(this).attr("data"));
            $(".send .face_cont").hide(300);
            isOpened1 = false;
            $(".send .face_cont table img").unbind("click");
        });
    });
    $(".replay_wrap .face").bind("click", function () {
        if (!logined) return;
        var face = $(this).parent().find(".face_cont");
        if (isOpened2 == true) {
            face.hide(300);
            $(".face_cont table img").unbind("click");
            isOpened2 = false;
            return;
        }
        isOpened2 = true;
        if (face.html().length < 50) {
            face.html(table);
            face.show(300);
        } else {
            face.toggle(300);
        }
        var container = $("#" + $(this).attr("container"));
        face.find("table img").bind("click", function (event) {
            var t = container.val();
            container.focus().val(t + $(this).attr("data"));
            face.hide(300);
            isOpened2 = false;
            face.find("table img").unbind("click");
        });

    });
});