function im_send(uid) {
        var fid = $("#select_target_id").data('ddslick').selectedData.value;
        var msg = $("#message").val()
        $.ajax({url: "/wbr/engine.php?act=im_send",
            data: { msg: msg,
                user_id: uid,
                friend_id: fid,
                from: 'im'
            },
            type: "POST"
        }).done(function(msg) {
                if (msg == '1') {
                    $("#im_send_form").modal('hide');
                    $.ajax({
                        url: "",
                        context: document.body,
                        success: function (s, x) {
                            $(this).html(s);
                        }
                    });
                } else {
                }
            })
}
function mark_all_as_read(uid){
    $.ajax({url: "/wbr/engine.php?act=mark_all_im_as_read",
        data: {
            user_id: uid
        },
        type: "POST"
    }).done(function(msg) {
            if (msg == '1') {
                $('.unread').remove();
            } else {
            }
        })
}
function dialog_send_im(uid, sid) {
    var msg = $("#msg_input_text").val();
    $.ajax({url: "/wbr/engine.php?act=im_send",
        data: {
            friend_id: sid,
            user_id: uid,
            msg: msg
        },
        type: "POST"
    }).done(function(msg) {
            if (msg == '1') {
                $.ajax({
                    url: "",
                    context: document.body,
                    success: function (s, x) {
                        $(this).html(s);
                    }
                });
            } else {
            }
        })
}