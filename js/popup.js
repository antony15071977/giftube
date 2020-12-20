$(".modal").each(function() {
	$(this).wrap('<div class="overlay"></div>')
});
$(".open-modal").on('click', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation;
	$('.home').addClass('modal--overflow');
	var $this = $(this),
		modal = $($this).data("modal");
		$url = $(this).data("url");
	$.ajax({
		url: $url,
		cache: false,
		success: function(dataResult) {
			$('.modal__content').html(dataResult);
		}
	});
	$(modal).parents(".overlay").addClass("open");
	setTimeout(function() {
		$(modal).addClass("open");
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