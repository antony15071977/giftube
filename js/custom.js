function Before() {
    $('.loading-overlay').show();
}
function Complete() {
    $('.loading-overlay').hide();
}
function logOut() {
    current_url = $(location).attr('href');
    $.ajax({
        url: '/logout.php',
        data: {current_url : current_url},
        cache: false,
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Complete();
        },
        success: function(dataResult) {
            $('.modal__content').html(dataResult);
        }
    });
}
function reStore() {
    $.ajax({
        url: '/restore/reset_password-popup.php',
        cache: false,
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Complete();
        },
        success: function(dataResult) {
            $('.modal__content').html(dataResult);
        }
    });
}
$(document).ready(function(){
    $('#search_box').on('keyup', function() {
        if ($('#search_box').val().length > 2) {
            function load_data(query = '') {
              $.ajax({
                url:"/search/fetch.php",
                method:"POST",
                data:{query:query},
                success:function(data)
                {
                  $('.content').html(data);
                }
              });
            }
        var query = $('#search_box').val();
        load_data(query);
        }
    });
});
