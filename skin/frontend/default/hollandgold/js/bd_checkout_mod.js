document.observe('dom:loaded', function() {
	if($('billing:region')){
		var factuur_regio = $('billing:region').up('.field');
		factuur_regio.hide();
	}
	if($('shipping:region')){
		var verzend_regio = $('shipping:region').up('.field');
		verzend_regio.hide();
	}
});