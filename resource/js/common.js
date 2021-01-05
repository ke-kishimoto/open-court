'use strict';
$(function() {
    // ドロワーメニュー
    $('.drawer').drawer();
    // キャンセル画面
    if($('#user-mode').val() === 'guest') {
        $('#password-area').remove();
    }
    // 同伴者追加
    $('#btn-companion-add').on('click', function() {
        var num = Number($('#companion').val());
        if(num > 9){
            $('#douhanErrMsg').css('display','block');
            return
        }
        var current = $('#douhan-' + num);
        num++;
        var div = $('<div>').attr('id', 'douhan-' + num).text(num + '人目');
        div.append($('#occupation').clone().attr('id', 'occupation-' + num).attr('name', 'occupation-' + num));
        div.append($('#sex').clone().attr('id', 'sex-' + num).attr('name', 'sex-' + num));
        div.append($('#name').clone().attr('id', 'name-' + num).attr('name', 'name-' + num).attr('placeholder', '名前').val(''));
        div.append($('<br>'));
        current.after(div);
        $('#companion').val(num);
    });
    // 同伴者削除
    $('#btn-companion-del').on('click', function() {
        var num = Number($('#companion').val());
        if(num > 0) {
            $('#douhan-' + num).remove();
            num--;
        }
        $('#companion').val(num);
    });
    // 参加者登録
    $('#btn-partisipant-regist').on('click', function() {
        if($('#name').val() === '') {
            return true;
        }
        if($('#email').val() === '') {
            return true;
        }
        var msg = '以下の内容で登録します\n' + 
        '名前：' + $('#name').val() + '\n';
        // '職種：' + $('#companion').val() + '\n' +
        // '性別：' + $('#sex').val();
        var num = Number($('#companion').val());
        for(let i = 0; i < num; i++) {
            msg += '同伴者' + (i + 1) + '：' + $('#name-' + (i + 1)).val() + '\n';
        }
        return confirm(msg);
    });
    // カレンダーの日付
    $('.link').on('click', function(event) {
        event.preventDefault(),
        $.ajax({
            url:'/api/event/getEventListByDate',
            type:'POST',
            data:{
                'date':$('#year').text() + '/' + ('00' + $('#this-month').text()).slice(-2) + '/' +( '00' + $(this).text().trim()).slice(-2),
                'type':''
                // 'date':$(this).attr('href')
            }
        })
         // Ajaxリクエストが成功した時発動
        .done( (data) => {
            $('#event-list').html(data);
        })
        // Ajaxリクエストが失敗した時発動
        .fail( (data) => {
            $('#event-list').html(data);
        })
        // Ajaxリクエストが成功・失敗どちらでも発動
        .always( (data) => {
        })
    });

    // ログインボタン
    $('#btn-login').on('click', function() {
        var userMail = $('#email').val();
        var password = $('#password').val();
        if(userMail === '' || password === '') {
            return true;
        }
        // if (typeof(Strage) === "undefined") {
        //     console.log('サポートされていません。')
        // }
        if($('#autoLogin').val() === 'on') {
            var strage = window.localStorage;
            var user = {
                email: userMail,
                pass: password,
            };
            strage.removeItem('eventScheduleUser');
            // ローカルストレージに保存
            strage.setItem('eventScheduleUser', JSON.stringify(user));
            // console.log(JSON.parse(strage.getItem('eventScheduleUser')));
        }
    });
    let strage = window.localStorage;
    let user = JSON.parse(strage.getItem('eventScheduleUser'));
    if(user !== null) {
        $('#email').val(user.email);
        $('#password').val(user.pass);
    }

    // $('#btn-companion-add').on('click', function() {
    //     var num = Number($('#companion').val());
    //     if(num > 9){
    //         $('#douhanErrMsg').css('display','block');
    //         return
    //     }
    //     var current = $('#douhan-' + num);
    //     num++;
    //     var div = $('<div>').attr('id', 'douhan-' + num).text(num + '人目');
    //     div.append($('#occupation').clone().attr('id', 'occupation-' + num).attr('name', 'occupation-' + num));
    //     div.append($('#sex').clone().attr('id', 'sex-' + num).attr('name', 'sex-' + num));
    //     div.append($('#name').clone().attr('id', 'name-' + num).attr('name', 'name-' + num).attr('placeholder', '名前').val(''));
    //     div.append($('<br>'));
    //     current.after(div);
    //     $('#companion').val(num);
    // });
    // $('#btn-companion-del').on('click', function() {
    //     var num = Number($('#companion').val());
    //     if(num > 0) {
    //         $('#douhan-' + num).remove();
    //         num--;
    //     }
    //     $('#companion').val(num);
    // });
    $('#button-user-del').on('click', function() {
        return confirm('削除してもよろしいですか');
    });
    if($('#update-mode').val() === 'update') {
        $('#password-area').remove();
    }

    const gameId = document.getElementById("game_id");
    if(gameId === null || gameId.value === '') {
        const joinForm = document.getElementById("join_form");
        if (joinForm !== null) {
            joinForm.classList.add('hidden');
        }
    }



});