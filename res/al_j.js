$(function () {
    $.fn.preloadImg = function () {
        this.each(function () {
            $('<img/>')[0].src = this;
        });
    }
});

// Thanks to James at Stack Overflow
function sound_notification(type) {
    var audio;
    switch (type) {
        case 'message':
            audio = '<audio id="chatAudio">' +
                '<source src="/wbr/res/notify.mp3" type="audio/mpeg">' +
                '<source src="/wbr/res/notify.wav" type="audio/wav">' +
                '</audio>';
            break;
        default:
            audio = '<audio id="chatAudio">' +
                '<source src="/wbr/res/notify.mp3" type="audio/mpeg">' +
                '<source src="/wbr/res/notify.wav" type="audio/wav">' +
                '</audio>';
    }
    $(audio).appendTo('body');
    $('audio')[0].play();
    $('audio').bind('ended', function () {
        $('audio').remove();
    });
}

function display_notification(message) {
    var message = '<div style="opacity: 1">' + message + '</div>';
    var html = '<div class="modal-info" style="border-radius: 2px; min-width:10%; height: auto; padding: 15px; position: fixed; background-color: rgb(192, 192, 192); top: 4px; left: 4px; opacity: 0.9">' + message + '</div>';
    $('html').append(html);
    setTimeout(function () {
        $('.modal-info').hide(600).remove();
    }, 3000);
}

$.fn.extend({
    trackChanges: function () {
        $(":input", this).change(function () {
            $(this.form).data("changed", true);
        });
    },
    isChanged: function () {
        return this.data("changed");
    }
});