
// 给Url模版绑定参数
export function z_bind_url_params(url, obj) {

    if(typeof obj !== 'object'){
        throw '错误的参数类型！';
    }

    for( let i in obj){
        url = url.replace(encodeURIComponent(i), obj[i]);
    }

    return url;
}

// 通用提示组件
function z_notify(message, type, cbClose, delay=5000) {
    let icon = '', title = '';
    switch(type){
        case 'success':
            title = '成功： ';
            icon = 'glyphicon glyphicon-ok-sign'; break;
        case 'info':
            title = '提示： ';
            icon = 'glyphicon glyphicon-info-sign'; break;
        case 'warning':
            title = '警告：';
            icon = 'glyphicon glyphicon-warning-sign'; break;
        case 'fail':
            title = '失败：';
            type = 'danger';
            icon = 'glyphicon glyphicon-remove-sign'; break;
        default:
            type = 'info';
            icon = 'glyphicon glyphicon-info-sign'; break;
    }
    if(typeof cbClose !== 'function'){
        cbClose = null
    }

    $.notify({
        title: title,
        message: message
    },{
        icon: icon,
        type: type,
        placement: {
            from: 'top',
            align: 'center'
        },
        animate: {
            enter: 'animated bounceInDown',
            exit: 'animated bounceOutDown'
        },
        mouse_over : 'pause',
        delay: delay,
        onClosed : cbClose
    });
}
// 成功提示
export function z_notify_success(message, cbClose) {
    z_notify(message, 'success', cbClose, 2000);
}
// 普通提示
export function z_notify_info(message, cbClose) {
    z_notify(message, 'info', cbClose);
}
// 警告提示
export function z_notify_warning(message, cbClose) {
    z_notify(message, 'warning', cbClose);
}
// 失败提示
export function z_notify_fail(message, cbClose) {
    z_notify(message, 'fail', cbClose);
}

// Ajax 请求调用
export function z_ajax(type, url, data, cbThen, cbCatch) {
    if(typeof cbCatch !== 'function'){
        cbCatch = function (error) {
            let msg = '';
            if (error.response) {
                switch(error.response.status){
                    case 422:
                        if(error.response.data.hasOwnProperty('errors')){
                            let errors = error.response.data.errors;
                            let errorArr = [];
                            for(let key in errors){
                                for(let j = 0; j < errors[key].length; j++) {
                                    errorArr.push(errors[key][j]);
                                }
                            }
                            for(let i = 0; i < errorArr.length; i++){
                                msg += (i===0) ? errorArr[i] : ("\n" + errorArr[i]);
                            }
                        }else{
                            msg = '['+ error.response.status +']' + ' ' + error.response.statusText;
                        }
                        break;
                    default:
                        if(error.response.data.message){
                            msg = '['+ error.response.status +']' + ' ' + error.response.data.message;
                        }else{
                            msg = '['+ error.response.status +']' + ' ' + error.response.statusText;
                        }
                }

            } else {
                msg = error.message;
            }
            z_notify_fail(msg);
        }
    }
    switch(type){
        case 'get':
        case 'post':
        case 'put':
        case 'patch':
        case 'delete':
            break;
        default:
            throw '错误的类型!';
    }
    if(typeof cbThen !== 'function'){
        throw '错误的回调类型！';
    }
    if(typeof cbCatch !== 'function'){
        throw '错误的回调类型！';
    }
    axios({
        method: type,
        url: url,
        data: data,
        validateStatus: function (status) {
            return status >= 200 && status < 300; // 默认的
        }
    }).then(cbThen).catch(cbCatch);
}