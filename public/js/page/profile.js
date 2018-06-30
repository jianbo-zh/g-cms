
$(function () {
   $('#update-profile').click(function (event) {

       let data = $('#profile-form').serializeJSON();

       let ladda = Ladda.create(this).start();

       z_ajax('patch', '/api/user/profile', data, function (reponse) {
           ladda.stop();
           let data = reponse.data;
           if(data.code === 0){
               z_notify_success(data.message ? data.message : '操作成功！');
           }else{
               z_notify_fail(data.message ? data.message : '操作失败！')
           }

       });
   });
});