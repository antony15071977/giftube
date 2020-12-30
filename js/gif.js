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
        success: function(dataResult) {
            $('.comment-list').html(dataResult);
            $('#comment').val('');
            checkComments();
            checkParams();
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
        success: function(dataResult) {
            $('body').html(dataResult);
        }
    });
}

function checkParams() {
    var comm = $('#comment').val();
    if (comm.length > 3 && comm.length < 180) {
        $('#submit').removeAttr('disabled');
        $('#submit').removeClass('gif__control--active');
    } else {
        $('#submit').attr('disabled', 'disabled');
        $('#submit').addClass('gif__control--active');
    }
}

function checkComments() {
    var gif_id = $('#show_more').attr('gif_id');
    var btn_less = $('#show_less') ?? '';
    btn_less.remove();
    $.ajax({
        url: "/gif/gif-ajax.php",
        type: "POST",
        dataType: "json",
        data: {
            "comments": "count",
            "gif_id": gif_id
        },
        success: function(data) {
            $('#comment-list__count').text(data.count_comm);
            if (data.count_comm <= 3) {
                $('#show_more').css('display', 'none');
            } else {
                $('#show_more').css('display', 'block');
            }
        }
    });
}
$(document).ready(function() {
    var count_comments = $('.comment').length ?? '';
    var count_comm = $('#comment-list__count').text() ?? '';
    if (count_comm <= 3) {
        $('#show_more').css('display', 'none');
    } else {
        $('#show_more').css('display', 'block');
    }
    $('#show_more').click(function(e) {
        e.preventDefault();
        e.stopImmediatePropagation;
        var btn_more = $(this);
        var count_show = parseInt($(this).attr('count_show'));
        var count_add = $(this).attr('count_add');
        var gif_id = $(this).attr('gif_id');
        btn_more.text('Подождите...');
        $.ajax({
            url: "/gif/gif-ajax.php",
            type: "POST",
            dataType: "json",
            data: {
                "count_show": count_show,
                "gif_id": gif_id,
                "count_add": count_add
            },
            success: function(data) {
                if (data.result == "success") {
                    $('.comment-list').append(data.html);
                    btn_more.text('Еще комментарии');
                    btn_more.attr('count_show', (count_show + 5));
                    var count_comments = $('.comment').length;
                    if (count_comments >= count_comm) {
                        btn_more.css('display', 'none');
                        $("<a class='button gif__control' href='/gif/gif.php?id=" + gif_id + "' id='show_less' gif_id=" + gif_id + ">Больше нечего показывать. Скрыть?</a>").insertAfter(btn_more);
                        $('#show_less').click(function(e) {
                            e.preventDefault();
                            e.stopImmediatePropagation;
                            $('#show_less').text('Подождите...');
                            $.ajax({
                                url: "/gif/gif-ajax.php",
                                type: "POST",
                                data: {
                                    "content": "hide",
                                    "gif_id": gif_id
                                },
                                success: function(dataResult) {
                                    $('.comment-list').html(dataResult);
                                    btn_more.text('Еще комментарии');
                                    btn_more.attr('count_show', 3);
                                    $('#show_less').remove();
                                    btn_more.css('display', 'block');
                                }
                            });
                        });
                    }
                } else {
                    btn_more.text('Нечего показывать.');
                }
            }
        });
    });
});

function goFavLike($url, data) {
    $.ajax({
        url: $url,
        data,
        cache: false,
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Complete();
        },
        success: function(dataResult) {
            $('.gif__controls').html(dataResult);
        }
    });
}
$(document).ready(function() {
    $(".comment-list").on("click", ".inlineEdit", updateText);
    var OrigText, NewText;
   
    $(".comment-list").on("click", ".revert", function() {
        $(this).parent('.comment__text').next('#response').next('.comment__sign').css('display', 'block');
        $(this).parent('.comment__text').html(OrigText).removeClass("selected").addClass('inlineEdit').on("click", updateText);
        $("#response").remove();
    });

    $(".comment-list").on("click", ".save", function() {
        NewText = $(this).prev("form").children(".edit").val();
        var id = $(this).parent('.comment__text').data('id');
        var name = $(this).parent('.comment__text').prev('.comment__author').prev('.comment__author').text();
        $.ajax({
            url: '/gif/update-comment.php',
            dataType: "json",
            method: "POST",
            data: {
                id: id,
                name: name,
                comment: NewText
            },
            cache: false,
            beforeSend: function() {
                Before();
            },
            complete: function() {
                Complete();
            },
            success: function(data) {
                $('#response').html(data.html);
                $("#response").slideDown('slow');
                    setTimeout(function() {
                        $("#response").slideUp("slow", function() {});
                        $("#response").remove();
                    }, 2000);
               if (data.result == "error") {
                NewText = OrigText;
                return false;
               } 
            }
        });
        $(this).parent('.comment__text').removeClass('selected').addClass('inlineEdit');
        $(this).parent('.comment__text').next().next('.comment__sign').css('display', 'block');
        $(this).parent('.comment__text').on('click', updateText).html(NewText);
    });

    function updateText() {
        $(this).removeClass("inlineEdit");
        OrigText = $(this).html();
        $(this).next('.comment__sign').css('display', 'none');
        $(this).addClass("selected").html('<form ><textarea class="edit" maxlength="180" minlength="3">' + OrigText + '</textarea> </form><span class="save"><img src="img/save.png" border="0" width="70" height="25"/></span> <span class="revert"><img src="img/cancel.png" border="0" width="80" height="25"/></span>').off('click', updateText);
        $('.edit').focus();
        $("<div id='response'></div>").insertAfter($(this));
    }
});