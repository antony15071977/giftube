function postData() {
    data = $('#comment-form').serialize();
    $.ajax({
        url: '/gif/gif-ajax.php',
        type: 'POST',
        data,
        cache: false,
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Complete();
        },
        success: function(dataResult){
            $('.content').html(dataResult);
        }
    });
}
function addItem($url, data) {
    var formAdd = $('#add-form')[0];
    var formData = new FormData(formAdd);
    $.ajax({
        url: '/gif/add.php',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Complete();
        },
        success: function(dataResult){
            $('body').html(dataResult);
        }
    });
}
function checkParams() {
    var comm = $('#comment').val();
    if(comm.length > 3 && comm.length < 180) {
        $('#submit').removeAttr('disabled');
        $('#submit').removeClass('gif__control--active');
    } else {
        $('#submit').attr('disabled', 'disabled');
        $('#submit').addClass('gif__control--active');
    }
}
