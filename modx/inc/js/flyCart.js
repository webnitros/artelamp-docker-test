class FlyCart {

	constructor() {

		this.ms2events()
	}


	ms2events() {
		miniShop2.Callbacks.add('Cart.add.response.success','fly_update',this.updateFlyCar);
		// miniShop2.Callbacks.add('Cart.change.response.success','fly_update',this.updateFlyCar);
		miniShop2.Callbacks.add('Cart.clean.response.success','fly_update',this.updateFlyCar);
	}


	updateFlyCar() {
		$.ajax({
			url: '/flyCart',
			type: 'GET',
			dataType: 'html',
			success: function(data) {
				$('#flyCart_block').html(data)
				$('.jheader_popup_scroll').mCustomScrollbar({
					axis: "y"
				});
			}
		})

	}


	block() {

	}
}

$(document).ready(function() {
	window.flyCart = new FlyCart()
})