function postData() {
    data = $('#comment-form').serialize();
    $.ajax({
        url: '/gif/gif-ajax.php',
        type: 'POST',
        data,
        dataType: "json",
        cache: false,
        success: function(data) {
            if (data.result == "success") {
                $("#success-respond").html("<p>Спасибо за оставленный ответ, он будет опубликован на сайте в ближайшее время после одобрения модератором.</p>");
                setTimeout(function() {
                        $("#success-respond").html('');
                            }, 4000);
                $('#comment').val('');
                checkComments();
                checkParams();
            }
            if (data.result == "error") {
                $("#success-respond").html(data.error);
                setTimeout(function() {
                $("#success-respond").html('');
                            }, 4000);
                $('#comment').val('');
                checkComments();
                checkParams();
            }
        }
    });
}

function addItem() {
    var formAdd = $('#add-form')[0];
    var formData = new FormData(formAdd);
    $.ajax({
        url: '/gif/add-ajax.php',
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
                $('.content').html(data.message);
                setTimeout(function() {
                    $('.content').html(data.add_form);
                }, 950);                
            } 
            if (data.result == "success") {
                $('.content').html(data.message);
                var url = '/';
                setTimeout(function() {
                    $(location).attr('href', url);
                }, 950);
            }
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
    $('#submit').attr('disabled', 'disabled');
    $('#submit').addClass('gif__control--active');
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
                    btn_more.text('Еще ответы');
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
                                    btn_more.text('Еще ответы');
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
        dataType: "json",
        beforeSend: function() {
            Before();
        },
        complete: function() {
            Complete();
        },
        success: function(data) {
         if (data.result == "success") {
            $(".gif__controls").html(data.page_content);
            var count_fav = $('.favs').text();
            var count_like = $('.gif__likes').text();
            if (data.count_favs) {
                var count_fav = $('.favs').text(data.count_favs);
            }
            if (data.count_likes) {
                var count_like = $('.gif__likes').text(data.count_likes);
            }
        } else {
                $(".gif__controls").html(data.page_content);
            }
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
                if (data.result == "success") {
                    setTimeout(function() {
                        $("#response").slideUp("slow", function() {});
                        $("#response").remove();
                    }, 2000);
                }
               if (data.result == "error") {
                    setTimeout(function() {
                        $("#response").slideUp("slow", function() {});
                        NewText = OrigText;
                        $("#response").prev('.comment__text').html(NewText);
                        $("#response").remove();
                    }, 2000);
                } 
            }
        });
        $(this).parent('.comment__text').removeClass('selected').addClass('inlineEdit');
        $(this).parent('.comment__text').next().next('.comment__sign').css('display', 'block');
        $(this).parent('.comment__text').on('click', updateText).html(OrigText);
    });

    function updateText() {
        $(this).removeClass("inlineEdit");
        OrigText = $(this).html();
        $(this).next('.comment__sign').css('display', 'none');
        $(this).addClass("selected").html('<form ><textarea class="edit" maxlength="1800" minlength="3">' + OrigText + '</textarea> </form><span class="save"><img src="img/save.png" border="0" width="70" height="25"/></span> <span class="revert"><img src="img/cancel.png" border="0" width="80" height="25"/></span>').off('click', updateText);
        $('.edit').focus();
        $("<div id='response'></div>").insertAfter($(this));
    }
});