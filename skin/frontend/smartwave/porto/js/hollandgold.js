 /* EDDY for shipping desriptions */
/*
function toggle_visibility(id) {
   var e = document.getElementById(id);
   if(e.style.display == 'block')
	  e.style.display = 'none';
   else
	  e.style.display = 'block';
}
*/
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
	// toggle_shippingdesc
	$('.toggle_shippingdesc').click(function(event){
		// $('input[class^="toggle_"]')
		$('.shipping-method').hide();
		// console.log('geklikt');
		this.next(".shipping-method").show();
	})
})
/* EDDY tot hier */

