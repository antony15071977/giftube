function getData($url, data) {
    $.ajax({
        url: $url,
        data,
        cache: false,
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Comlete();
        },
        success: function(dataResult){
            $('.content').html(dataResult);
        }
    });
}

