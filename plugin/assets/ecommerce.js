function exm_ecom_domagic(templateElementId) {
	console.log("do magic on " + templateElementId);
	var source = document.getElementById(templateElementId + "_template").innerHTML;
	var template = Handlebars.compile(source);

	fetch(ajaxurl, {
		method: "POST",
		mode: "cors",
		cache: "no-cache",
		credentials: "same-origin",
		body: "action=exm_ecom_load_products",
		headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
	}).then((response) => response.json()
	).then((response) => {
		response.recentlyViewedProducts.forEach((product) => {
			console.log(product);
			let product_html = template({product: product});
			document.querySelector("#" + templateElementId + "_container .products").insertAdjacentHTML('beforeend', product_html);
		});
	});
}