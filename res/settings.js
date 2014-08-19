var current_settings = {
    first_name: '',
    second_name: '',
    //password: '',
    //about: '',
    //timezone: '',
    wall_everybody: '',
    messages_everybody: '',
    birth: '',
    //city: '',
    skype: '',
    twitter: ''
};
$(function () {
    $('.setting').each(function (value) {
        switch ($(value).attr('id')) {
            case 'first_name':
                current_settings.first_name = value;
                break;
            case 'second_name':
                current_settings.second_name = value;
                break;
            /*case 'password':
             current_settings.password = value;
             break;
             case 'about':
             current_settings.about = value;
             break;
             case 'timezone':
             current_settings.timezone = value;
             break;
             */
            case 'wall_everybody':
                current_settings.wall_everybody = value;
                break;
            case 'messages_everybody':
                current_settings.messages_everybody = value;
                break;
            case 'birth':
                current_settings.birth = value;
                break;
            /*
             case 'city':
             current_settings.city = value;
             break;
             */
            case 'skype':
                current_settings.skype = value;
                break;
            case 'twitter':
                current_settings.twitter = value;
                break;
        }
    });
    $('#st_additional').trackChanges();
    $('#st_personal').trackChanges();
    $('#st_privacy').trackChanges();
    $('#st_wall').trackChanges();

    $('#st_personal').submit(function () {
        if($('#st_personal').isChanged()){
            display_notification('Hola!');
        }
        return false;
    });
    $('#st_wall').submit(function () {
        if($('#st_wall').isChanged()){

        }
        return false;
    });
    $('#st_privacy').submit(function () {
        if($('#st_privacy')){

        }
        return false;
    });
    $('#st_additional').submit(function () {
        if($('#st_additional')){

        }
        return false;
    });
});