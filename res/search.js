var add_opts = false;
function search_get_results(first_run_data) {
    var search_string;
    var data = {};
    if (arguments.length == 1) {
        if (first_run_data.length == 1) {
            $('#srch_str').val(first_run_data[0]);
            search_string = first_run_data[0].trim();
        }
    } else {
        search_string = $('#srch_str').val().trim();
    }
    data = {
        search_string: search_string
    };
    if($('#opt_city').val().trim().length > 0){
        data.city = $('#opt_city').val().trim();
    }
    if(add_opts){
        data.gender = $('#opt_gender').val();
    }
    $.ajax({
        url: '/wbr/engine.php?act=user_search',
        data: data,
        method: 'POST',
        beforeSend: function () {
            $('#start_srch').attr('disabled', 'disabled');
            $('#search_opts').addClass('srch-disabled');
        },
        success: function (data) {
            if (data != '0') {
                $('#search_results').html(data);
            } else {
                $('#search_results').html('<p class="text-center">We found nothing :C Please, check search data' +
                    ' and try again</p>');
            }
            $('#start_srch').removeAttr('disabled');
            $('#search_opts').removeClass('srch-disabled');

        }
    })

}
$(function () {
    $('#start_srch').click(function () {
        if ($('#srch_str').val().trim().replace('/\s/g').length >= 3) {
            search_get_results();
        } else {
            $('#search_results').html('<p class="text-center">Please, enter more than three characters</p>');
        }
    });
    $('#use_add_opt').click(function(){
       if($('#use_add_opt').prop('checked') != false){
           add_opts = true;
           $('#search_add_opt').show();
       } else {
           add_opts = false;
           $('#search_add_opt').hide();
       }
    });
});