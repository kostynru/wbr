var email = password = first_name = last_name = timezone = brthd = false;
$(function () {

    $('#register').submit(function(){
        if($('#password').val() != $('#re_password').val()){
            return false;
        }
        if($('#gender_male').prop('checked') == false && $('#gender_female').prop('checked') == false){
            return false;
        }
        if($('#emailGroup').hasClass('has-error')){
            return false;
        }
        if($('#accept_terms').prop('checked') == false){
            return false;
        }
    });

    $('#invite').click(function () {
        var reg = $('#registration');
        reg.animate({
            left: -reg.outerWidth() - 15
        }, 1000, function () {
            reg.hide();
            $('.activate_invite').show();
        });
    });
    $('#terms').click(function () {
        terms_highlight();
    });
    $('#email').focusout(function () {
        check_email_exists($('#email').val());
    });
    $('#password').keyup(function () {
        if ($('#password').val().length != 0) {
            $('#re_passwordGroup').show();
        } else {
            $('#re_passwordGroup').hide();
        }
    });
    $('#re_password').focusout(function () {
        if ($('#re_password').val() == $('#password').val() && $('#password').val() != '') {
            $('#passwordGroup,#re_passwordGroup').addClass('has-success');
        } else {
            $('#re_passwordGroup').addClass('has-error');
        }
    });
    $('#register').submit(function(){

    });
});
function terms_highlight() {
    $('html, body').animate({scrollTop: $('.reg_terms').offset().top}, function () {
        $('.reg_terms').effect('highlight', {color: 'rgba(255, 255, 255, 0.2)'}, 500);
    });
}
function check_email_exists(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (re.test(email)) {
        $.ajax({
            url: '/wbr/engine.php?act=check_email_exists',
            dataType: 'text',
            data: {
                email: email
            },
            type: 'POST'
        }).done(function (msg) {
                if (msg == '1') {
                    $('#emailGroup').addClass('has-success');
                    $('#email').prop('readonly', true);
                    $('#email').after('<a href="#" class="pull-right" id="email_edit">Edit</a>');
                    $('#email_edit').click(function () {
                        $('#email').prop('readonly', false);
                        $('#emailGroup').removeClass('has-success');
                        $('#email').val('');
                        $(this).remove();
                    });
                } else {
                    $('#email').after('<p class="text-danger" id="email_error">Email address already used</p>');
                    $('#email').on('change.error', function () {
                        $('#email_error').remove();
                        $(this).off('change.error');
                    })
                }
            });
    } else {
        $('#emailGroup').addClass('has-error');
        $('#email').keyup(function () {
            $('#emailGroup').removeClass('has-error');
            $('$email').unbind('keyup');
        });
    }
}