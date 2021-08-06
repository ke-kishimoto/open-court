$(function() {
    // 自動ログイン
    let session = window.sessionStorage;
    let isLogin = session.getItem('eventScheduleIsLogin');
    let strage = window.localStorage;

    let user = JSON.parse(strage.getItem('eventScheduleUser'));
    if(user !== null && isLogin !== 'login') {
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
            session.setItem('eventScheduleIsLogin', 'login');
            // location.href = './index.php';
        })
        // Ajaxリクエストが失敗した時発動
        .fail( (data) => {
            // console.log('失敗');
        });
    }
});