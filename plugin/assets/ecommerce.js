function exm_ecom_load_products(templateElementId, type, count) {
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
			document.querySelector("#" + templateElementId + "_container .products .spinner").style.display = "none";
			document.querySelector("#" + templateElementId + "_container .products").insertAdjacentHTML('beforeend', product_html);
		};
		if ("recently-viewed-products" === type) {
			response.recentlyViewedProducts.forEach(insertProductFunction);
		} else if ("frequently-purchased-products" === type) {
			response.frequentlyPurchasedProducts.forEach(insertProductFunction);
		} else if ("popular-products" === type) {
			response.popularProducts.forEach(insertProductFunction);
		}

	});
}

function exm_ecom_add_to_basket($target, product_id, product_sku) {
	var $thisbutton = jQuery($target);

//	$thisbutton.addClass('loader');

	var data = {
		'product_sku': product_sku,
		'product_id': product_id,
		'quantity': 1
	};

	// Ajax action.
	jQuery.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), data, function (response) {
		if (!response) {
			return;
		}

		if (response.error && response.product_url) {
			window.location = response.product_url;
			return;
		}

		// Redirect to cart option
		if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
			window.location = wc_add_to_cart_params.cart_url;
			return;
		}

//		$thisbutton.remove('loader');

		jQuery(".product[data-exm-product-id=" + product_id + "] .button").addClass("loader");
		jQuery(".product[data-exm-product-id=" + product_id + "] .notify").toggleClass("active");
		jQuery(".product[data-exm-product-id=" + product_id + "] #exm_ecom_notifyType").toggleClass("success");

		setTimeout(function () {
			jQuery(".product[data-exm-product-id=" + product_id + "] .notify").removeClass("active");
			jQuery(".product[data-exm-product-id=" + product_id + "] #exm_ecom_notifyType").removeClass("success");
			jQuery(".product[data-exm-product-id=" + product_id + "] .button").removeClass("loader");
		}, 2000);


		// Trigger event so themes can refresh other areas.
		jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
	});

}
