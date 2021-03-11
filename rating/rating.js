$(document).ready(function(){
   $(".star").on("mouseover", function(){
        $(".star").slice(0, $(".star").index(this)+1).addClass("star2");
    });
    $(".star").on("mouseout", function(){
        $(".star").slice(0, $(".star").index(this)+1).removeClass("star2");
    });
    $(".star").on("click", function(){
        jQuery.post("/rating/change_rating.php", {
            obj_id: $(this).parent().attr("id").substr(3),
            stars: $(".star").index(this)+1
        }, notice, "json");
    });
    function notice(data){
        $("#star_rating, #star_votes, #star_message, .rating").fadeOut(500, function(){
            $("#star_rating").text(data.points);
            $("#star_votes").text(data.votes);
            $rating = parseFloat(data.rating);
            $(".star").each(function(){
                $ratingHtml = $(this).attr('data-rating');
                parseFloat($ratingHtml);
                if ($ratingHtml<=$rating){
                    $(this).addClass('star3');
                } else {
                    $(this).removeClass('star3');
                };
            });
            $("#star_message").text(data.message);
        }).fadeIn(1500);
    }
});
