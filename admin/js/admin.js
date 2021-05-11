function getData($url, data) {
        $.ajax({
            url: $url,
            data,
            cache: false,
            beforeSend: function() {
                $('.loading-overlay').show();
            },
            complete: function() {
                $('.loading-overlay').hide();
            },
            success: function(dataResult){
                $('#content').html(dataResult);
            }
        });
}
function Before() {
    $('.loading-overlay').show();
}
function Complete() {
        $('.loading-overlay').hide();
}
function Delete(id) {
    url = $('.btn-danger').data("url");
    if(!confirm('Удалить?')){
        return false;
    }
    else{
        $.ajax({
            url: url,
            data: {"del": id},
            cache: false,
            dataType: "json",
            beforeSend: function() {
                Before();
            },
            complete: function() {
                Complete();
            },
            success: function(data) {
                if (data.result == "success") {
                    $('#' + 'tr_' + id).remove();
                }
                if (data.result == "error") {
                     $(".overlay").addClass("open");
                     $('.modal').addClass("open");
                    $('.modal__content').html(data.error);
                    setTimeout(function() {
                        $('.modal').removeClass("open");
                        $('.modal').parents(".overlay").removeClass("open");
                        $('.modal__content').html('');
                    }, 1050);
                }
            }
        });      
    }
}

function EditItem(id) {
    url = $('#submit_add').data("url");
    var formAdd = $('#edit-form')[0];
    var formData = new FormData(formAdd);
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Complete();
        },
        success: function(data) {
            if (data.result == "success") {
                $('#' + 'tr_' + id).html(data.html);
                $('.modal__content').html('Успешно обновлено');
                setTimeout(function() {
                    $('.modal').removeClass("open");
                    $('.home').removeClass('modal--overflow');
                   $('.modal').parents(".overlay").removeClass("open");
                    $('.modal__content').html('');
                }, 650);
            }
            if (data.result == "error") {

                $('.modal__content').html(data.edit_form );
            }
        }
    });
}
function AddEdit(url, id='') {
    $('.home').addClass('modal--overflow');
    $.ajax({
        url: url,
        cache: false,
        data: {edit: id},
        method:"POST",
        dataType: "json",
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Complete();
        },
        success: function(data) {
            if (data.result == "error") {
                $('.modal__content').html(data.page_content);
            }
            if (data.result == "simple") {
                 $('.modal__content').html(data.page_content);
            }
        }
    });
    $(".overlay").addClass("open");
    setTimeout(function() {
        $('.modal').addClass("open");
    }, 350);
    $(document).on('click', function(e) {
        var target = $(e.target);
        if ($(target).hasClass("overlay")) {
            $(target).find(".modal").each(function() {
                $(this).removeClass("open");
                $('.home').removeClass('modal--overflow');
            });
            setTimeout(function() {
                $(target).removeClass("open");
                $('.modal__content').html('');
            }, 350);
        }
    });
    $(".close-modal").on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation;
        var $this = $(this),
            modal = $($this).data("modal");
        $(modal).removeClass("open");
        $('.home').removeClass('modal--overflow');
        setTimeout(function() {
            $(modal).parents(".overlay").removeClass("open");
            $('.modal__content').html('');
        }, 350);
    });
}
function addItem() {
    var formAdd = $('#add-form')[0];
    var formData = new FormData(formAdd);
    $.ajax({
        url: '/admin/Item/add-ajax.php',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Complete();
        },
        success: function(data) {
            if (data.result == "error") {
                $('.modal__content').html(data.add_form);
            } 
            if (data.result == "success") {
                $('.modal__content').html('Успешно добавлено!');
                var url = '/admin/Item/item.php?tab=new';
                $(location).attr('href', url);
            }
        }
    });
}
$(document).ready(function(){
    $('#search_box').on('keyup', function() {
        $search = $('#search_box').data("search");
        if ($('#search_box').val().length > 0) {
            function load_data(query = '', option ='') {
              $.ajax({
                url:"/admin/search/fetch.php",
                method:"POST",
                data:{query:query,
                    search: $search,
                    option: option
                },
                success:function(data)
                {
                  $('.box-content').html(data);
                }
              });
            }
        var query = $('#search_box').val();
        var option = $('#names').val();
        load_data(query, option);
        }
    });
});

$(document).ready(function() { 
    $('#modal1').on('keyup', '#author_input', function(){
        var $result = $('#search_author-result');
        var search = $(this).val();
        if ((search != '') && (search.length > 1)){
            $.ajax({
                type: "POST",
                url: "/admin/search/search_author.php",
                data: {'search': search},
                success: function(msg){
                    $result.html(msg);
                    if(msg != ''){  
                        $result.fadeIn();
                    } else {
                        $result.html('<div class="search_result"><table><tr><td class="search_result-name" style="color:red;">Ничего не найдено!</td></tr></table></div>');
                    }
                }
            });
         } else {
            $result.html('');
            $result.fadeOut(100);
         }
            $(document).on('click', function(e){
            if (!$(e.target).closest('.author').length){
                $result.html('');
                $result.fadeOut(100);
            }
        }); 
    });
    $('#modal1').on('click', 'a[class^="search_result-name"]', function(e){
        e.preventDefault();
        id = $(this).data("id");
        value = $(this).text();
        result = $('#search_author-result');
        document.getElementById('author').value = id;
        document.getElementById('author_input').value = value;
        result.html('');
        result.fadeOut(100);
    });
});

$(document).ready(function(){
   $('#content').on('change', '#InputFile', function(){
   var files;
    files = this.files;
    $('.upload_files').on( 'click', function( event ){
        event.stopPropagation(); 
        event.preventDefault();
        // ничего не делаем если files пустой
        // if( typeof files == 'undefined' ) return;
        var data = new FormData();
        // заполняем объект данных файлами в подходящем для отправки формате
        $.each( files, function( key, value ){
            data.append( key, value );
        });
        $path = $('#select').val();
        data.append( 'select', $path );
        $.ajax({
            url         : '/admin/upload/submit.php',
            type        : 'POST', // важно!
            data        : data,
            cache       : false,
            dataType    : 'json',
            processData : false,
            contentType : false, 
            success     : function( respond, status, jqXHR ){
                 // выведем пути загруженных файлов в блок '.ajax-reply'
                    var files_path = respond.files;
                    var html = 'Успешно загружено по следующим адресам: <br>';
                    $.each( files_path, function( key, val ){
                         html += val +'<br>';
                    } )
                    if (respond.result == "error") {
                        var html = respond.data;
                    }
                    $('#InputFile').val('');
                    $('.ajax-reply').html(html);
             },
            // функция ошибки ответа сервера
            error: function( jqXHR, status, errorThrown ){
                console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
            }
        });
    });
   });
});


