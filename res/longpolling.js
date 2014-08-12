var LongPolling = {
    im_count: function (id, current) {
        setTimeout(function () {
            var source = new EventSource('/wbr/msg.php?act=im_count&uid=' + id);

            source.addEventListener('message', function (event) {
                var data = JSON.parse(event.data);
                console.log('Received');
                if (data.msg != '0') {
                    if (data.msg != current) {
                        $('#im_counter').hide();
                        $('#im_counter').slideDown('slow').html(data.msg);

                    }
                } else {
                    $('#im_counter').hide();
                }
            }, false);
            source.addEventListener('open', function (e) {
                // Opened
            });
            source.addEventListener('error', function (e) {
                if (e.eventPhase == EventSource.CLOSED) {
                    // Closed
                }
            });
        }, 50);
    },
    chat_update: function (uid, cid) {
        setTimeout(function () {
            var source = new EventSource('/wbr/msg.php?act=im_upd&uid=' + uid + '&cid=' + cid);

            source.addEventListener('message', function (event) {
                var data = JSON.parse(event.data);
                var msgs = {};
                var avatar;
                $(data.msg).children().each(function (index, value) {
                    msgs['"' + index++ + '"'] = {value: value.innerHTML, id: $(value).attr('id')}; // Have to make simply?
                });
                if ($(msgs).length > 0) {
                    var email_hash;
                    var f_name;
                    $.ajax({
                       url: '/wbr/engine.php?act=set_read',
                        type: 'POST',
                        data: {
                            uid: uid,
                            sid: cid
                        },
                        success: function(data){
                            if(data == '1'){
                                console.log('Updated');
                            } else {
                                console.warn('Failed');
                            }
                        }
                    });
                    if (sessionStorage.getItem(cid) != null && sessionStorage.getItem('name' + cid) != null) {
                        email_hash = sessionStorage.getItem(cid);
                        f_name = sessionStorage.getItem('name' + cid);
                    } else {
                        $.ajax({
                            url: '/wbr/engine.php',
                            type: 'POST',
                            data: {
                                act: 'get_user_by_id',
                                uid: cid
                            },
                            async: false,
                            success: (function(data){
                                sessionStorage.setItem(cid, hex_md5(data.email));
                                sessionStorage.setItem('name'+cid, data.first_name);
                                email_hash = hex_md5(data.email);
                                f_name = data.first_name;

                            })
                        });
                    }
                    avatar = new Image();
                    if($('.dialog .media img').attr('src').length > 0){
                        avatar.src = $('.dialog .media img').attr('src');
                    } else {
                        avatar.src = 'http://gravatar.com/avatar/' + email_hash + '?d=mm&s=60';
                    }
                    $(msgs).each(function(){
                        for(var i in this){
                            var html = '<div class="media im_list" id="msg' + this[i].id + '" sid="' + cid + '">' +
                                '<a class="pull-left" href="/wbr/show/' + cid + '">' +
                                '<img class="media-object img-circle" src="http://gravatar.com/avatar/' + email_hash + '?d=mm&s=60" alt=""></a>' +
                                '<div class="media-body pull-left"><h4 class="media-heading" style="text-align: left">Michael</h4><br><div class="media-text pull-left" style="text-align: left">' + this[i].value + '</div>' +
                                '</div>' +
                                '</div>';
                            if($('.dialog').find('div').attr('sid') != cid){
                                html += '<hr>';
                            }
                            $('.dialog').prepend($(html).fadeIn('fast'));
                            sound_notification('message');
                        }
                    });
                }
            }, false);
            source.addEventListener('open', function (e) {
                // Opened
            });
            source.addEventListener('error', function (e) {
                if (e.eventPhase == EventSource.CLOSED) {
                    // Closed
                }
            });
        }, 50);
    }
}