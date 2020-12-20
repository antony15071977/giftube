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