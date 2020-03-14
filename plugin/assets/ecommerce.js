function exm_ecom_domagic(templateElementId, type, count) {
	console.log("do magic on " + templateElementId);
	console.log("type" + type);
	
	var source = document.getElementById(templateElementId + "_template").innerHTML;
	var template = Handlebars.compile(source);

	fetch(exm_ecom.ajax_url, {
		method: "POST",
		mode: "cors",
		cache: "no-cache",
		credentials: "same-origin",
		body: "action=exm_ecom_load_products&count=" + count + "&type=" + type,
		headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
	}).then((response) => response.json()
	).then((response) => {
		let insertProductFunction = (product) => {
			let product_html = template({product: product});
			document.querySelector("#" + templateElementId + "_container .products").insertAdjacentHTML('beforeend', product_html);
		}
		if ("recently-viewed-products" === type) {
			response.recentlyViewedProducts.forEach(insertProductFunction);
		} else if ("frequently-purchased-products" === type) {
			response.frequentlyPurchasedProducts.forEach(insertProductFunction);
		} else if ("popular-products" === type) {
			response.popularProducts.forEach(insertProductFunction);
		}
		
	});
}