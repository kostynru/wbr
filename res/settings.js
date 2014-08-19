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
                success: function (result) {

                }
            });
        }
        return false;
    });
    $('#st_wall').submit(function () {
        if ($('#st_wall').isChanged()) {
            var changes = {};
            var submit = {};
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
                success: function (result) {

                }
            });
        }
        return false;
    });
    $('#st_privacy').submit(function () {
        if ($('#st_privacy').isChanged()) {
            var changes = {};
            var submit = {};
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
                success: function (result) {

                }
            });
        }
        return false;
    });
    $('#st_additional').submit(function () {
        if ($('#st_additional').isChanged()) {
            var changes = {};
            var submit = {};
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
                success: function (result) {

                }
            });
        }
        return false;
    });
});