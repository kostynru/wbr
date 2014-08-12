function remove_friend_accept(arg, fid) {
    if (arg == 'accepting') {
        $('#rm_fr_controls' + fid).hide();
        $('#rm_fr_accept' + fid).show();
    } else {
        $('#rm_fr_accept' + fid).hide();
        $('#rm_fr_controls' + fid).show();
    }
}
function remove_friend(uid, fid) {
    $.ajax({
        type: "POST",
        data: {
            user_id: uid,
            friend_id: fid
        },
        url: "/wbr/engine.php?act=friend_rmv"
    }).done(function (msg) {
            if (msg == '1') {
                $('friend' + fid).remove();
                $.ajax({
                    url: "",
                    context: document.body,
                    success: function (s, x) {
                        $(this).html(s);
                    }
                });
            } else {
                return false;
            }
        })
}
function friend_query_accept(uid, fid) {
    $.ajax({
        url: '/wbr/engine.php?act=friend_accept',
        data: {
            user_id: uid,
            friend_id: fid
        },
        type: "POST",
        dataType: 'text'
    }).done(function (msg) {
            if (msg == '1') {
                $('#qris_controls' + fid).html('<div class="help-block">Query have been accepted</div>');
                $.ajax({
                    url: "",
                    context: document.body,
                    success: function (s, x) {
                        $(this).html(s);
                    }
                });
            } else {
                return false;
            }
        })
}
function friend_query_decline(uid, fid) {
    $.ajax({
        url: '/wbr/engine.php?act=friend_decline',
        data: {
            user_id: uid,
            friend_id: fid
        },
        type: "POST",
        dataType: 'text'
    }).done(function (msg) {
            if (msg == '1') {
                $('#qris_controls' + fid).html('<div class="help-block">Query have been declined</div>');
                $.ajax({
                    url: "",
                    context: document.body,
                    success: function (s, x) {
                        $(this).html(s);
                    }
                });
            } else {
                return false;
            }
        })

}
function friend_ext_query_cancel(uid, fid) {
    $.ajax({
        url: '/wbr/engine.php?act=friend_ext_query_cancel',
        data: {
            user_id: uid,
            friend_id: fid
        },
        type: "POST",
        dataType: 'text'
    }).done(function (msg) {
            if (msg == '1') {
                $('#qris_controls' + fid).html('<div class="help-block">Query have been declined</div>');
                $.ajax({
                    url: "",
                    context: document.body,
                    success: function (s, x) {
                        $(this).html(s);
                    }
                });
            } else {
                return false;
            }
        })
}
function im_send(uid, fid, msg) {
    $.ajax({
        url: '/wbr/engine.php?act=im_send',
        data: {
            user_id: uid,
            friend_id: fid,
            msg: msg
        },
        type: "POST",
        dataType: 'text'
    }).done(function (msg) {
            if (msg == '1') {
                $("#im_send").modal('hide');
            } else {
            }
        })
}
$(function () {
    $('#page_list').click(function () {
        if (!$('#page_list').parent().hasClass('active')) {
            $('#friends_nav li').removeClass('active');
            $('#page_list').parent().addClass('active');
            $('#friends').children().hide();
            $('#friendlist_wrap').show();
        }

    });
    /*$('#page_maybe').click(function () {
        if (!$('#page_maybe').parent().hasClass('active')) {
            $('#friends_nav li').removeClass('active');
            $('#page_maybe').parent().addClass('active');
            $('#friends').children().hide();
            $('#maybe_wrap').show();
        }
    });*/
    $('#page_queries').click(function () {
        if (!$('#page_queries').parent().hasClass('active')) {
            $('#friends_nav li').removeClass('active');
            $('#page_queries').parent().addClass('active');
            $('#friends').children().hide();
            $('#queries_wrap').show();
        }
    });
    $('#page_ext_qries').click(function () {
        if (!$('#page_ext_qries').parent().hasClass('active')) {
            $('#friends_nav li').removeClass('active');
            $('#page_ext_qries').parent().addClass('active');
            $('#friends').children().hide();
            $('#ext_qries_wrap').show();
        }

    });
    $('#im_send_ctrl').click(function () {
        var uid = $('#uid').val();
        var fid = $('#fid').val();
        var msg = $('#message').val();
        im_send(uid, fid, msg);
    })
});