function add_to_friends(uid, fid) {
    $.ajax({
        url: '/wbr/engine.php?act=add_to_friends',
        dataType: 'text',
        data: {
            user_id: uid,
            friend_id: fid
        },
        type: 'POST'
    }).done(function (msg) {
            if (msg == '1') {
                $('#send_query').addClass('disabled');
                $('#send_query').html('<span class="glyphicon glyphicon-ok"></span> Wait for approve');
            }
        })
}
function cancel_query(uid, fid) {
    $.ajax({
        url: '/wbr/engine.php?act=friend_ext_query_cancel',
        dataType: 'text',
        data: {
            user_id: uid,
            friend_id: fid
        },
        type: 'POST'
    }).done(function (msg) {
            if (msg == '1') {
                $.ajax({
                    url: "",
                    context: document.body,
                    success: function (s, x) {
                        $(this).html(s);
                    }

                });
            }
        })
}

function wall_delete(mid) {
    $.ajax({
        url: '/wbr/engine.php?act=wall_delete',
        dataType: 'text',
        data: {
            post_id: mid
        },
        type: 'POST'
    }).done(function (msg) {
            if (msg != '0') {
                $.ajax({
                    url: '/wbr/engine.php?act=wall_load',
                    dataType: 'text',
                    data: {
                        user_id: $('#user_id').val(),
                        offset: 0,
                        owner: $('#owner').val()
                    },
                    type: 'POST'
                }).done(function (msg) {
                        $('#wall_content').html(msg);

                    })
            }
        })
}
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}
function getCookie(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset);
            if (end == -1) {
                end = cookie.length;
            }
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return(setStr);
}
var file_uploaded = false;
function wall_like(pid, uid) {

    $.ajax({
        url: '/wbr/engine.php?act=wall_like',
        method: 'POST',
        data: {
            post_id: pid,
            user_id: uid
        }
    });
}
function wall_dislike(pid, uid) {
    $.ajax({
        url: '/wbr/engine.php?act=wall_dislike',
        method: 'POST',
        data: {
            post_id: pid,
            user_id: uid
        }
    });
}
$(function () {

    $('.post_attached_image').click(function (e) {
        var src = $(e.target).attr('src');
        $('.image_taint').append('<img src="' + src + '" class="zoomed_attachment">');
        $('.zoomed_attachment').css({
            "padding-top": (($(window).height() - $(this).outerHeight()) / 2),
            "padding-left": (($(window).width() - $(this).outerWidth()) / 2)
        });
        $('.image_taint').show();

    });
    $('.image_taint').click(function () {
        $(this).hide();
        $(this).html('');
    });
    //
    $('.like .glyphicon').hover(function (e) {
        if ($(e.target).parent().hasClass('liked')) {
            $(e.target).tooltip({title: 'Dislike'});
        } else {
            $(e.target).tooltip({title: 'Like'});
        }
    }).mouseout(function (e) {
            $(e.target).tooltip('hide');
        });
    $('.like .glyphicon').click(function (e) {
        var uid = localStorage.getItem('uid');
        var pid = $(e.target).parent().parent().attr('id');
        var elem = $(e.target).parent();
        var counter = $(e.target).siblings('.counter');
        if ($(elem).hasClass('liked')) {
            wall_dislike(pid, uid);
            $(elem).removeClass('liked');
            if (parseInt(counter.html()) == 1) {
                $(counter).html('');
            } else {
                $(counter).html('&nbsp;' + (parseInt(counter.html()) - 1));
            }
        } else {
            wall_like(pid, uid);
            if ($(elem).hasClass('liked') == false) {
                $(elem).addClass('liked');
                if (counter.html() == '') {
                    $(counter).html('1');
                } else {
                    $(counter).html('&nbsp;' + (parseInt(counter.html()) + 1));
                }
            }
        }
    });

    //Time changing
    $('.wall_post time').hover(function (e) {
        $(e.target).livestamp('destroy');
    });
    $('.wall_post time').mouseleave(function (e) {
        $(e.target).livestamp(new Date($(e.target).attr('datetime')));
    });
    //Resizing
    $('#avatar_pr,#info').css('position', 'fixed');
    $('#avatar_pr').css('left', '0');
    $('#info').css('right', '0');
    $('#wall').css('left', $('#avatar_pr').width());
    $('#wall').css('right', $('#info').width());
    /*$(window).resize(function(){
     $('#wall').css('width', ($(window).width() - ($('#avatar_pr').width() + $('#info').width())));
     $('#wall').css('left', $('#avatar_pr').width());
     $('#wall').css('padding-right', $('#avatar_pr').width());
     });*/
    ///////////////
    var ending = false;
    var scroller = false;
    $(window).scroll(function () {
        if ($(window).scrollTop() >= ($('.navbar').height())) {  //$(document).height() * 20 / 100
            if (!scroller) {
                $('body').append('<div id="scroll_up"></div>');
                $('#scroll_up').click(function () {
                    $('html,body').animate({scrollTop: 0}, 'slow');
                });
                scroller = true;
            }
        } else {
            $('#scroll_up').remove();
            scroller = false;
        }


        if ($(window).scrollTop() == $(document).height() - $(window).height() && !ending) {
            var min = null;
            var max = null;
            $('.wall_post').each(function () {
                var id = parseInt(this.id, 10);
                if ((min === null) || (id < min)) {
                    min = id;
                }
                if ((max === null) || (id > max)) {
                    max = id;
                }
            });
            var offset = min;
            $.ajax({
                url: '/wbr/engine.php?act=wall_load',
                dataType: 'text',
                data: {
                    user_id: $('#user_id').val(),
                    offset: offset,
                    owner: $('#owner').val()
                },
                type: 'POST'
            }).done(function (msg) {
                    if (msg != '0') {
                        var max_msg = null;
                        $(msg).filter('.wall_post').each(function () {
                            var id = parseInt(this.id, 10);
                            if ((max_msg === null) || (id > max_msg)) {
                                max_msg = id;
                            }
                        });
                        if (max_msg >= offset) {
                            ending = true;
                        } else {
                            $('#wall_content').append(msg);
                        }
                        $.livestamp.run();
                    }
                });
        }
    });
    $("#post_msg_form").keyup(function (event) {
        if (event.keyCode == 13) {
            $("#send_wall_post").click();
        }
    });
    var showadd = false;
    $("#showAdditionalInfo").click(function () {
        if (showadd) {
            $("#additionalInfo").hide();
            $(this).html('Show more');
            showadd = false;
        } else {
            $("#additionalInfo").show();
            $(this).html('Hide')
            showadd = true;
        }
    });

    //DROP

    /*$("#post_msg_form").dropzone({
     url: "/wbr/ftp.php",
     acceptedFiles: "image*/
    /*",
     dragover: function(){
     $("#img_input_msg").show();
     },
     dragleave: function(){
     $("#img_input_msg").hide();
     },
     drop: function(){
     $("#img_input_msg").hide();
     },
     createImageThumbnails: false,
     maxFilesize: 4,
     maxFiles: 1
     });*/
    if ($('#user_id').val() == localStorage.getItem('uid')) {
        var fileDrop = $("#post_msg_form"),
            maxFileSize = 5000000;
        fileDrop[0].ondragover = function (e) {
            if (file_uploaded != false) {
                return false;
            }
            $("#img_input_msg").show();
            return false;
        };
        fileDrop[0].ondragleave = function (e) {
            if (file_uploaded != false) {
                return false;
            }
            $("#img_input_msg").hide();
            return false;
        };
        fileDrop[0].ondrop = function (e) {
            e.preventDefault();
            if (file_uploaded != false) {
                return false;
            }
            $("#img_input_msg").hide();
            var file = e.dataTransfer.files[0];
            if (maxFileSize < file.size) {
                console.log('Too big');
                return false;
            }
            $.get('/wbr/ftp.php');
            var data = new FormData();
            data.append('img_attachment', file);
            $.ajax({
                url: '/wbr/ftp.php',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                before: function () {
                    $('#attachment').html('<img src="/wbr/res/ajax-loader.gif">');
                },
                success: function (result) {
                    if (result != '0') {
                        file_uploaded = result;
                        localStorage.setItem('j_w_img', result);
                        $('#attachment').html('<img class="img-rounded" src="/wbr/img_upl/' + file_uploaded + '" height="70px" ' +
                            'data-toggle="tooltip" data-placement="bottom" title="Remove image">').show();
                        $('#attachment').click(function () {
                            $.ajax({
                                url: '/wbr/ftp.php',
                                data: {
                                    act: 'remove_img',
                                    file_name: result
                                },
                                type: 'POST',
                                success: function () {
                                    $('#attachment').html('').hide();
                                }
                            });
                            file_uploaded = false;
                        });
                    } else {
                        console.log('File upload failed!');
                    }
                }
            });

        };
    }
    if (localStorage.getItem('j_w_img') != null) {
        $.ajax({
            url: '/wbr/ftp.php',
            data: {
                act: 'remove_img',
                file_name: localStorage.getItem('j_w_img')
            },
            type: 'POST'
        });
        localStorage.removeItem('j_w_img');
    }
});

window.onbeforeunload = function () {
    if (file_uploaded != false) {
        $.ajax({
            url: '/wbr/ftp.php',
            data: {
                act: 'remove_img',
                file_name: file_uploaded
            },
            type: 'POST'
        });
    }
};
function wall_post(uid) {
    var msg = btoa(encodeURIComponent($('#wall_msg').val().trim()));
    if (msg.length == 0) {
        return false;
    }
    $.ajax({
        url: '/wbr/engine.php?act=wall_post',
        dataType: 'text',
        data: {
            user_id: uid,
            msg: msg,
            file_uploaded: file_uploaded
        },
        type: 'POST',
        beforeSend: function () {
            $('#wall_msg, #send_wall_post').attr('disabled', 'disabled');
        }
    }).done(function (msg) {
            if (msg != '0') {
                $.ajax({
                    url: '/wbr/engine.php?act=wall_load',
                    dataType: 'text',
                    data: {
                        user_id: uid,
                        offset: 0,
                        owner: $('#owner').val()
                    },
                    type: 'POST'
                }).done(function (msg) {
                        $('#wall_content').html(msg);
                        $('#wall_msg').val('');
                        $('#wall_msg, #send_wall_post').removeAttr('disabled');
                        localStorage.removeItem('j_w_img');
                        $('#attachment').html('');
                    });

            }
        })
}
