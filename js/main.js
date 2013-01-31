$(document).ready(function(){
	$('a').click(function(){
		var href = $(this).attr('href');
		var patt = /#/g;
		if(patt.test(href)){
			return
		}
		$('body').addClass('wait');
	});
});
