var current_settings = {};
$(function () {
    $('.setting').each(function (value) {
        current_settings[$(this).attr('id')] = $(this).val();
    });
    $('#st_additional').trackChanges();
    $('#st_personal').trackChanges();
    $('#st_privacy').trackChanges();
    $('#st_wall').trackChanges();
    $('#st_personal').submit(function () {
        if ($('#st_personal').isChanged()) {
            var changes = {};
            var submit = {};
            $("input[type=checkbox]").each(function(current){
                if($(this).is(':checked')){
                    $(this).val('checked');
                } else {
                    $(this).val('unchecked');
                }
            });
            $('#st_personal .setting').each(function (current) {
                changes[$(this).attr('id')] = $(this).val();
            });
            submit['user_id'] = $('#uid').val();
            $.each(changes, function (index, value) {
                if (value != current_settings[index]) {
                    submit[index] = value;
                }
            });
            submit['user_id'] = $('#uid').val();
            $.ajax({
                url: '/wbr/engine.php?act=settings_save',
                data: submit,
                method: 'POST',
                beforeSend: function(){
                    $('#personal_st_save').attr('disabled', 'disabled');
                    $('.success').remove();
                },
                success: function (result) {
                    $('.setting').each(function (value) {
                        current_settings[$(this).attr('id')] = $(this).val();
                    });
                    $('#personal_st_save').removeAttr('disabled');
                    $('#personal_st_save').after(' <span class="success">Saved successful!</span>');
                }
            });
        }
        return false;
    });
    $('#st_wall').submit(function () {
        if ($('#st_wall').isChanged()) {
            var changes = {};
            var submit = {};
            $("input[type=checkbox]").each(function(current){
                if($(this).is(':checked')){
                    $(this).val('checked');
                } else {
                    $(this).val('unchecked');
                }
            });
            $('#st_wall .setting').each(function (current) {
                changes[$(this).attr('id')] = $(this).val();
            });
            $.each(changes, function (index, value) {
                if (value != current_settings[index]) {
                    submit[index] = value;
                }
            });
            submit['user_id'] = $('#uid').val();
            $.ajax({
                url: '/wbr/engine.php?act=settings_save',
                data: submit,
                method: 'POST',
                beforeSend: function(){
                    $('#wall_st_save').attr('disabled', 'disabled');
                    $('.success').remove();
                },
                success: function (result) {
                    $('.setting').each(function (value) {
                        current_settings[$(this).attr('id')] = $(this).val();
                    });
                    $('#wall_st_save').removeAttr('disabled');
                    $('#wall_st_save').after(' <span class="success">Saved successful!</span>');
                }
            });
        }
        return false;
    });
    $('#st_privacy').submit(function () {
        if ($('#st_privacy').isChanged()) {
            var changes = {};
            var submit = {};
            $("input[type=checkbox]").each(function(current){
                if($(this).is(':checked')){
                    $(this).val('checked');
                } else {
                    $(this).val('unchecked');
                }
            });
            $('#st_privacy .setting').each(function (current) {
                changes[$(this).attr('id')] = $(this).val();
            });
            $.each(changes, function (index, value) {
                if (value != current_settings[index]) {
                    submit[index] = value;
                }
            });
            submit['user_id'] = $('#uid').val();
            $.ajax({
                url: '/wbr/engine.php?act=settings_save',
                data: submit,
                method: 'POST',
                beforeSend: function(){
                  $('#privacy_st_save').attr('disabled', 'disabled');
                    $('.success').remove();
                },
                success: function (result) {
                    $('.setting').each(function (value) {
                        current_settings[$(this).attr('id')] = $(this).val();
                    });
                    $('#privacy_st_save').removeAttr('disabled');
                    $('#privacy_st_save').after(' <span class="success">Saved successful!</span>');
                }
            });
        }
        return false;
    });
    $('#st_additional').submit(function () {
        if ($('#st_additional').isChanged()) {
            var changes = {};
            var submit = {};
            $("input[type=checkbox]").each(function(current){
                if($(this).is(':checked')){
                    $(this).val('checked');
                } else {
                    $(this).val('unchecked');
                }
            });
            $('#st_additional .setting').each(function (current) {
                changes[$(this).attr('id')] = $(this).val();
            });
            $.each(changes, function (index, value) {
                if (value != current_settings[index]) {
                    submit[index] = value;
                }
            });
            submit['user_id'] = $('#uid').val();
                $.ajax({
                url: '/wbr/engine.php?act=settings_save',
                data: submit,
                method: 'POST',
                    beforeSend: function(){
                        $('#additional_st_save').attr('disabled', 'disabled');
                        $('.success').remove();
                    },
                    success: function (result) {
                        $('.setting').each(function (value) {
                            current_settings[$(this).attr('id')] = $(this).val();
                        });
                        $('#additional_st_save').removeAttr('disabled');
                        $('#additional_st_save').after(' <span class="success">Saved successful!</span>');
                    }
            });
        }
        return false;
    });
});