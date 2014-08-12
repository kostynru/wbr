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