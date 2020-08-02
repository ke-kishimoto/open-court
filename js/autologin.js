$(function() {
    // 自動ログイン
    var strage = window.localStorage;
    var user = JSON.parse(strage.getItem('eventScheduleUser'));
    if(user !== null) {
        // event.preventDefault(),
        $.ajax({
            url:'../controller/api/SignInApi.php',
            type:'POST',
            data:{
                'email':user.email,
                'password':user.pass
            }
        })
        // Ajaxリクエストが成功した時発動
        .done( (data) => {
            // $('#user_name').text(data.name);
            // console.log(data);
        })
        // Ajaxリクエストが失敗した時発動
        .fail( (data) => {
            // console.log('失敗');
        });
    }
});