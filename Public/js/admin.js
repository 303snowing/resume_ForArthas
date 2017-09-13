$(document).ready(function () {
    // alert(1);
    setConSize();
    setBanner();

    if ($('#login_form')){
        setInput();
        $('#submit').bind('click', function () {
            if ($('#upass').val() == ''){
                $('#myModalLabel').text('请输入密码');
                return;
            }
            submitForm();
        });
        $('#upass').bind('keyup',function (event) {
            if (event.keyCode == 13){
                if($(this).val() == ''){
                    $('#myModalLabel').text('请输入密码');
                    return;
                }
                submitForm();
            }
        });
    }

    $('#exit_login').bind('click', function () {
        exitLogin();
        return false;
    });


    //设置导航点击事件
    $('#banner li').click(function () {
        $('#banner li').removeClass('active');
        $(this).addClass('active');

        var bannerAction = $(this).find('a').attr('href');
        var bannerTarget = $(this).find('a').text();
        var bannertpl = $(this).find('a').attr('template');
        var pageTarget = $(this).find('a').attr('title');
        // alert(bannerAction+bannerTarget);
        getDataByAjax(bannerAction,bannerTarget,bannertpl,pageTarget);

        return false;
    });
});

$(window).on('scroll click resize',function(){
    setConSize();
    setBanner();
});

//设置背景大小
function setConSize(){
	var bodyH = $(document).height();
	// alert(bodyH);
	$('.content').height(bodyH);
}

//设置导航位置
function setBanner(){
    var logoH = $('#logo').height();
    var bannerH = $('#banner').height();
    $('#banner').css({ 'margin-top' : logoH-bannerH });
}

/*ajax登录*/
function submitForm() {
    $.ajax({
        type: $('#login_form').attr('method'),
        cache: false,
        url: $('#login_form').attr('action'),
        data: {user:$('#uname').val(),pass:$('#upass').val()},
        success: function (data, status) {
            //alert(status,  data);
            if (status == 'success' && data != null){
                // $('#uname_form').removeClass('has-error');
                switch (data){
                    case 0: $('#myModalLabel').text('密码错误'); $('#upass_form').addClass('has-error'); $('#upass').focus(); return;
                    case -1: alert('用户名不存在'); return;
                    // default: alert('登录成功');
                }
            }
            window.location.reload();
        }
    });
}
/*监听输入框*/
function setInput() {
    $('#upass').bind('keyup', function () {
        // alert($(this).parent().parent().attr('class'));
        $(this).parent().parent().removeClass('has-error');
        $('#submit').removeClass('disabled');
    });
    $('#uname').bind('keyup', function () {
        // alert($(this).parent().parent().attr('class'));
        $.ajax({
            type: $('#login_form').attr('method'),
            cache: false,
            url: $('#uname').attr('title'),
            data: {user:$('#uname').val()},
            success: function (data, status) {
                // alert(data+status);
                if (status == 'success'){
                    switch (data){
                        case 0: $('#uname_form').addClass('has-error'); $('#submit').addClass('disabled'); break;
                        case 1: $('#uname_form').removeClass('has-error'); break;
                    }
                }
            }
        });
    })
}

function exitLogin() {
    // alert($('#exit_login').attr('href')+$('#exit_login').attr('data'));
    $.ajax({
        type: 'POST',
        cache: false,
        url: $('#exit_login').attr('href'),
        data: {sessionUser: $('#exit_login').attr('data')},
        beforeSend: function () {
            alert(1);
        },
        success: function (data, status) {
            if (status == 'success' && data == 0){
                // alert(data);
                window.location.reload();
            }
        }
    });
}
/*end*/

//ajax请求导航对应内容
function getDataByAjax(bannerAction,bannerTarget,bannertpl,pageTarget) {
    // alert(bannerAction);
    var ajaxSubBanner = ['简历'];
    var ajaxBanner = ['签到'];
    if ($.inArray(bannerTarget, ajaxSubBanner)!= (-1)) {
        $.ajax({
            type: "POST",
            cache: false,
            url: bannerAction,
            data: null,
            dataType: "json",
            beforeSend: function(){
                $('#data-display').empty();
            },
            success: function (data,status) {
                showData(data, status, bannertpl,pageTarget);
            }
        });
    }else if($.inArray(bannerTarget, ajaxBanner)!= (-1)){
        $('#data-display').load(bannerAction);
    }else{
        window.open(bannerAction);
    }
    return false;
}

function showData(data,status,bannertpl,pageTarget) {
    // alert(status+data);
    if (status=='success' && data!=0){
        /*如果请求正确返回数据，开始渲染页面*/
        $('#data-display').load(bannertpl+' .container',function(){
            switch (pageTarget){
                case '简历': jianli(data); break;
            }
        });
        var pageName = data[data.length-1];
        loadPage(pageName,data);

    }else if (status=='success' && data==0){
        window.location.reload();
    }
    else{
        $('#data-display').html('载入失败');
    }
    return false;
}

//渲染简历页面
function jianli(data) {
    for (var i=0; i<data.length-1; i++){
        /*for (var key in data[i]){


            alert(data[i]['sex']);
            alert(data[i]['groups']);
            $('#content').append('<br>'+key+':'+data[i][key]);
        }*/
        switch (data[i]['sex']){
            case "1": data[i]['sex'] = '糙汉子'; break;
            case "0": data[i]['sex'] = '萌妹纸'; break;
            default: data[i]['sex'] = '人妖';
        }
        switch (data[i]['groups']){
            case "1": data[i]['groups'] = '技术组'; break;
            case "2": data[i]['groups'] = '微信组'; break;
            default: data[i]['groups'] = '迷茫';
        }
        $('#jianli-list').append('<div class="col-sm-11 col-md-11" id="list">' +
            '<p>投递时间：'+data[i]['time']+'</p> '+
            '<p><b>姓名：</b>'+data[i]['name']+'</p>' +
            '<p><b>性别：</b>'+data[i]['sex']+'</p>' +
            '<p><b>电话：</b>'+data[i]['phone']+'</p>' +
            '<p><b>邮箱：</b>'+data[i]['email']+'</p>' +
            '<p><b>学院年级专业：</b>'+data[i]['class']+'</p>' +
            '<p><b>所选专业小组：</b>'+data[i]['groups']+'</p>' +
            '<p><b>所选专业方向：</b>'+data[i]['subject']+'</p>' +
            '<p><b>简历：</b>'+data[i]['message']+'</p></div>');
    }
}