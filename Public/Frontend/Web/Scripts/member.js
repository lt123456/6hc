function UpdateInfo(_type) {
    var title;
    var url;
    switch (_type) {
        case "info":
            title = "更改资料";
            url = "/Member/info";
            break;
        case "password":
            title = "修改密码";
            url = "/Member/password";
            break;
        case "bindmail":
            title = "绑定郵箱";
            url = "/Member/email";
            break;
        case "bindphone":
            title = "绑定手机";
            url = "/Member/phone";
            break;
        default:
            return;
    } 
    $.dialog({
        title: title,
        padding: '5px',
        width: 800,
        height: 500,
        content: "<iframe height='500' width='800' id='queryUserDialog' name='"+this.location.href+"'  frameborder='0' framepadding='0' src=" + url + " ></iframe>",
        ok: false
    });
}