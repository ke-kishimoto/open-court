'use strict';
$(function() {
    // ドロワーメニュー
    $('.drawer').drawer();
    // 日付をクリックした場合
    $('.link').on('click', function(event) {
        event.preventDefault(),
        $.ajax({
            url:'/api/event/getEventListByDate',
            type:'POST',
            data:{
                'date':$('#year').text() + '/' + ('00' + $('#this-month').text()).slice(-2) + '/' +( '00' + $(this).text().trim()).slice(-2),
                'type':'admin'
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

    let id = $('#participant_id').val();
    if(id === '') {
        // 新規の時は削除ボタンは非表示に
        $('#btn-delete').addClass('hidden');
    } else {
        // 修正の時は削除ボタンに確認処理のイベント追加
        $('#btn-delete').on('click', function() {
            return confirm('削除してもよろしいですか');
        });
    }

    // // テンプレのプルダウン選択時
    // $('#template').change(function() {
    //     $.ajax({
    //     url:'/api/event/getEventTemplate',
    //     type:'POST',
    //     data:{
    //         'id':$('#template').val(),
    //     }
    //     })
    //     // Ajaxリクエストが成功した時発動
    //     .done( (data) => {
    //         $('#template_name').val(data.template_name);
    //         $('#title').val(data.title);
    //         $('#short_title').val(data.short_title);
    //         $('#place').val(data.place);
    //         $('#limit_number').val(data.limit_number);
    //         $('#detail').val(data.detail);
    //         $('#price1').val(data.price1);
    //         $('#price2').val(data.price2);
    //         $('#price3').val(data.price3);
    //     })
    //     // Ajaxリクエストが失敗した時発動
    //     .fail( (data) => {
    //     })
    //     // Ajaxリクエストが成功・失敗どちらでも発動
    //     .always( (data) => {
    //     })
    // });

    // キャンセル待ち⇔解除の処理
    $('.waiting').on('click', function() {
        $.ajax({
        url:'/api/event/updateWaitingFlg',
        type:'POST',
        data:{
            'id':$(this).val(),
            'game_id':$('#game_id').val(),
        }
        })
        .done( (data) => {
            $('#cnt').text(data.cnt);
            $('#sya_all').text(data.sya_all);
            $('#sya_women').text(data.sya_women);
            $('#sya_men').text(data.sya_men);
            $('#dai_all').text(data.dai_all);
            $('#dai_women').text(data.dai_women);
            $('#dai_men').text(data.dai_men);
            $('#kou_all').text(data.kou_all);
            $('#kou_women').text(data.kou_women);
            $('#kou_men').text(data.kou_men);
            $('#waiting_cnt').text(data.waiting_cnt);
            if(data.waiting_flg == '0') {
                $(this).attr('class', 'warning btn btn-success').text('キャンセル待ちに変更');
            } else {
                $(this).attr('class', 'warning btn btn-warning').text('キャンセル待ちを解除');
            }

        })
        // Ajaxリクエストが失敗した時発動
        .fail( (data) => {
        })
        // Ajaxリクエストが成功・失敗どちらでも発動
        .always( (data) => {
        })
    });
    // 参加者削除処理
    $('.btn-participant-delete').on('click', function() {
        if(window.confirm('削除してもよろしいですか')) {
            $.ajax({
                url:'/api/event/deleteParticipant',
                type:'POST',
                data:{
                    'participant_id':$(this).val(),
                    'game_id':$('#game_id').val(),
                }
            })
            .done( (data) => {
                console.log('OK');
                $('#cnt').text(data.cnt);
                $('#sya_all').text(data.sya_all);
                $('#sya_women').text(data.sya_women);
                $('#sya_men').text(data.sya_men);
                $('#dai_all').text(data.dai_all);
                $('#dai_women').text(data.dai_women);
                $('#dai_men').text(data.dai_men);
                $('#kou_all').text(data.kou_all);
                $('#kou_women').text(data.kou_women);
                $('#kou_men').text(data.kou_men);
                $('#waiting_cnt').text(data.waiting_cnt);
                $('#participant-' + $(this).val()).remove();
            })
            // Ajaxリクエストが失敗した時発動
            .fail( (data) => {
                console.log('NG');
            })
            // Ajaxリクエストが成功・失敗どちらでも発動
            .always( (data) => {
            })
        }
    });
    // イベント削除
    $('#btn-event-delete').on('click', function() {
        return confirm('削除してもよろしいですか');
    });
    // 同伴者追加
    $('#btn-companion-add').on('click', function() {
        let num = Number($('#companion').val());
        let current = $('#douhan-' + num);
        num++;
        let btn = $('<button>').attr('class', 'btn btn-danger btn-companion-delete').text('削除');
        let div = $('<div>').attr('id', 'douhan-' + num).text(num + '人目  ');
        div.append(btn);
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
    $('#btn-participant-regist').on('click', function() {
        if($('#name').val() === '') {
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
    // ユーザー選択時
    $('#user').change(function() {
        $.ajax({
            url:'/api/event/getUserInfo',
            type:'POST',
            data:{
                'user_id':$('#user').val()
            }
        })
        .done( (user) => {
            // 同伴者削除
            for(let i = Number($('#companion').val()); i > 0; i--) {
                $('#douhan-' + i).remove();
            }
            $('#companion').val(0);

            // ユーザー情報セット
            $('#name').val(user.name);
            $('#occupation').val(user.occupation);
            $('#sex').val(user.sex);
            $('#email').val(user.email);
            $('#remark').val(user.remark);
            // 同伴者情報追加
            $.ajax({
                url:'/api/event/GetDefaultCompanionList',
                type:'POST',
                data:{
                    'id':user.id
                }
            })
            .done((conpanionList) => {
                // console.log(conpanionList);
                
                for(let i = 0; i < conpanionList.length; i++) {
                    var current = $('#douhan-' + i);
                    let num = i + 1;
                    var div = $('<div>').attr('id', 'douhan-' + num).text(num + '人目');
                    div.append($('#occupation').clone().attr('id', 'occupation-' + num).attr('name', 'occupation-' + num).val(conpanionList[i].occupation));
                    div.append($('#sex').clone().attr('id', 'sex-' + num).attr('name', 'sex-' + num).val(conpanionList[i].sex));
                    div.append($('#name').clone().attr('id', 'name-' + num).attr('name', 'name-' + num).attr('placeholder', '名前').val(conpanionList[i].name));
                    div.append($('<br>'));
                    current.after(div);
                    $('#companion').val(num);
                }
            })
        })
        .fail( (data) => {
        })
        .always( (data) => {
        })
    });
    // 権限の変更
    $('.change-authority').on('click', function() {
        $.ajax({
            url:'/api/event/updateAdminFlg',
            type:'POST',
            data:{
                'id':$(this).val()
            }
        })
        .done( (data) => {
            $('#authority-name-' + $(this).val()).text(data.authority_name);
        })
        .fail( (data) => {
        })
        .always( (data) => {
        })
        
    });
    // 問い合わせのステータス変更
    $('.btn-inquiry-status').on('click', function() {
        $.ajax({
            url:'/api/event/updateInquiryStatusFlg',
            type:'POST',
            data:{
                'id':$(this).val()
            }
        })
        .done( (data) => {
            $(this)[0].disabled = true;
        })
        .fail( (data) => {
        })
        .always( (data) => {
        })
    });

    // お知らせのプルダウン選択時
    $('#notice').change(function() {
        $.ajax({
        url:'/api/event/getNotice',
        type:'POST',
        data:{
            'id':$('#notice').val(),
        }
        })
        .done( (data) => {
            $('#title').val(data.title);
            $('#content').val(data.content);
        })
        .fail( (data) => {
        })
        .always( (data) => {
        })
    });
});