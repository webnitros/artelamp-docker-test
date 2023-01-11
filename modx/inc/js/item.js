function slidersCard() {

	var galleryThumbs = new Swiper('.gallery-thumbs', {
		direction            : 'vertical',
		spaceBetween         : 20,
		slidesPerView        : 4,
		freeMode             : true,
		watchSlidesVisibility: true,
		watchSlidesProgress  : true,
		loop                 : false,
		navigation           : {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		breakpoints          : {
			320: {
				direction    : 'horizontal',
				spaceBetween : 14,
				slidesPerView: 3,
			},
			550: {
				direction    : 'horizontal',
				slidesPerView: 4,
			},
			770: {
				direction: 'vertical',
			},
		},
	})
	var galleryTop = new Swiper('.gallery-top', {
		loop      : true,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		thumbs    : {
			swiper: galleryThumbs
		}
	})
	var slider4 = new Swiper('.card_characters_slider .swiper-container', {
		loop         : true,
		slidesPerView: 1,
		navigation   : {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
	})
	var swiper5 = new Swiper('.card_slider_other_4 .swiper-container', {
		slidesPerView: 4,
		spaceBetween : 20,
		scrollbar    : {
			el  : '.swiper-scrollbar',
			hide: true,
		},
		breakpoints  : {
			320 : {
				slidesPerView: 1.5,
				spaceBetween : 20
			},
			450 : {
				slidesPerView: 1.5,
				spaceBetween : 20
			},
			768 : {
				slidesPerView: 2,
				spaceBetween : 20
			},
			993 : {
				slidesPerView: 3,
				spaceBetween : 20
			},
			1280: {
				slidesPerView: 4,
				spaceBetween : 20
			}
		},
	})
	var swiper6 = new Swiper('.card_slider_other_5 .swiper-container', {
		slidesPerView: 5,
		spaceBetween : 20,
		scrollbar    : {
			el  : '.swiper-scrollbar',
			hide: true,
		},
		breakpoints  : {
			320 : {
				slidesPerView: 1.5,
				spaceBetween : 20
			},
			768 : {
				slidesPerView: 2,
				spaceBetween : 20
			},
			993 : {
				slidesPerView: 3,
				spaceBetween : 20
			},
			1280: {
				slidesPerView: 4,
				spaceBetween : 20
			},
			1440: {
				slidesPerView: 5,
				spaceBetween : 20
			}
		},
	})
	var swiper6 = new Swiper('.card_slider_other_6 .swiper-container', {
		slidesPerView: 5,
		spaceBetween : 20,
		scrollbar    : {
			el  : '.swiper-scrollbar',
			hide: true,
		},
		breakpoints  : {
			320 : {
				slidesPerView: 1.5,
				spaceBetween : 20
			},
			768 : {
				slidesPerView: 2,
				spaceBetween : 20
			},
			993 : {
				slidesPerView: 3,
				spaceBetween : 20
			},
			1280: {
				slidesPerView: 4,
				spaceBetween : 20
			},
			1440: {
				slidesPerView: 5,
				spaceBetween : 20
			}
		},
	})
}

var stop = false

window.cardCharactersOpenList = function() {
	if(!stop) {
		stop = true
		$('.card_characters_list_title .btn').click(function(event) {
			$(this).closest('.card_characters_list').toggleClass('active')
		})
	}
}

function cardInfoBlock() {
	$(document).click(function(e) {
		if(!$(e.target).hasClass('name_ic_info')) {
			$('.name_ic_info').closest('.name').find('.name_inform').removeClass('active')
		} else {
			$(e.target).closest('.name').find('.name_inform').toggleClass('active')
		}
	})
}

function buttonSliderDisable() {

	var a = $('.card_main_sliders_left .swiper-slide-vivsible').length
	var b = $(window).width()
	if(b < 551) {
		if(a < 4) {
			$('.card_main_sliders_left').addClass('no_button')
		} else {
			removeClass('no_button')
		}
	} else {
		if(a < 5) {
			$('.card_main_sliders_left').addClass('no_button')
		} else {
			removeClass('no_button')
		}
	}

	var a2 = $('.card_characters_slider .swiper-slide').length
	var b2 = $('.card_characters_slider .swiper-slide-duplicate').length
	var c2 = +a2 - b2
	if(c2 < 2) {
		$('.card_characters_slider').addClass('no_button')
	} else {
		$('.card_characters_slider').removeClass('no_button')
	}

}

$(document).ready(function() {
	slidersCard()
	cardCharactersOpenList()
	cardInfoBlock()
	//buttonSliderDisable();
	$(window).resize(function() {
		//buttonSliderDisable();
	})
})