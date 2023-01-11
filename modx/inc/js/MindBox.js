class MindBoxIntegrator {
	sendMindbox
	cart = {}


	constructor() {
		if(typeof window.sendMindbox === 'function') {
			window.sendMindbox()
		}
		window.miniShop2.Callbacks.add('Cart.add.response.success', 'mindbox_cart_add', (response, a, b) => {
			const cart = response.data.cart
			const ids = []
			for(const productKey in cart) {
				const value = cart[productKey]
				ids.push(value.id.toString())
				this.cart[value.id.toString()] = {
					id   : value.id.toString(),
					count: value.count.toString(),
					price: value.price.toString()
				}
			}
			for(const productKey in this.cart) {
				if(ids.indexOf(productKey) === -1){
					this.cart[productKey].count = 0
				}
			}
			console.info('mindbox_cart_add')
			console.info(...arguments)
			this.setCart(this.cart)
		})
		window.miniShop2.Callbacks.add('Cart.remove.response.success', 'mindbox_cart_remove', (response, a, b) => {
			const cart = response.data.cart
			const ids = []
			for(const productKey in cart) {
				const value = cart[productKey]
				ids.push(value.id.toString())
				this.cart[value.id.toString()] = {
					id   : value.id.toString(),
					count: value.count.toString(),
					price: value.price.toString()
				}
			}
			for(const productKey in this.cart) {
				if(ids.indexOf(productKey) === -1){
					this.cart[productKey].count = 0
				}
			}
			console.info('mindbox_cart_remove')
			console.info(...arguments)

			this.setCart(this.cart)
		})
		window.miniShop2.Callbacks.add('Cart.change.response.success', 'mindbox_cart_change', (response, a, b) => {
			const cart = response.data.cart
			const ids = []
			for(const productKey in cart) {
				const value = cart[productKey]
				ids.push(value.id.toString())
				this.cart[value.id.toString()] = {
					id   : value.id.toString(),
					count: value.count.toString(),
					price: value.price.toString()
				}
			}
			for(const productKey in this.cart) {
				if(ids.indexOf(productKey) === -1){
					this.cart[productKey].count = 0
				}
			}
			console.info('mindbox_cart_change')
			console.info(...arguments)

			this.setCart(this.cart)
		})
		window.miniShop2.Callbacks.add('Cart.clean.response.success', 'mindbox_cart_clean', (response, a, b) => {
			const cart = response.data.cart
			const ids = []
			for(const productKey in cart) {
				const value = cart[productKey]
				ids.push(value.id.toString())
				this.cart[value.id.toString()] = {
					id   : value.id.toString(),
					count: value.count.toString(),
					price: value.price.toString()
				}
			}
			for(const productKey in this.cart) {
				if(ids.indexOf(productKey) === -1){
					this.cart[productKey].count = 0
				}
			}
			console.info('mindbox_cart_clean')
			console.info(...arguments)

			this.setCart(this.cart)
		})
	}


	setCart(cart) {
		const productList = []
		for(const productKey in cart) {
			const value = cart[productKey]
			productList.push(
				{
					product     : {
						ids: {
							websiteArteLampRu: value.id
						}
					},
					count       : value.count,
					pricePerItem: value.price
				}
			)
		}
		window.mindbox('async', {
			operation: 'Website.SetCart.ArteLamp', data: {
				productList: productList
			}
		})
	}
}

window.MindBoxIntegratorContainer = undefined
setTimeout(function() {
	clearInterval(MindBoxIntegratorStarter)
}, 5000)
const MindBoxIntegratorStarter = setInterval(() => {
	if(typeof window.mindbox !== 'undefined' && typeof window.miniShop2 !== 'undefined') {
		try {
			window.MindBoxIntegratorContainer = new MindBoxIntegrator()
		} catch(e) {

		}
		clearInterval(MindBoxIntegratorStarter)
	}
}, 200)
