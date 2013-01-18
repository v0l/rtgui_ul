function toHumanReadable(val){
	var txt = ['B','KB','MB','GB','TB','PB']
	var a = val;
	var x = 0;
	while(a>1024){
		a /= 1024;
		x++;
	}
	
	return a.toFixed(2) + " " + txt[x];
}
function updateList(){
	var start = new Date();
	$.getJSON('php/ajax.php?q=min', function(json) {
		$.each(json, function(key, val) {
			//Check is it the global values
			if(key == 'GLOBAL'){
				$('#ratedown').html(toHumanReadable(val['ratedown']) + "/s");
				$('#rateup').html(toHumanReadable(val['rateup']) + "/s");
			}else{
				$('#' + key + '_up_rate').html(toHumanReadable(val['up_rate']) + "/s");
				$('#' + key + '_down_rate').html(toHumanReadable(val['down_rate']) + "/s");
				$('#' + key + '_up_total').html(toHumanReadable(val['up_total']));
				$('#' + key + '_completed_bytes').html(toHumanReadable(val['completed_bytes']));
				$('#' + key + '_ratio').html(val['ratio'] / 1000);
				$('#' + key + '_percent_complete').width(val['percent_complete'] + "%");
			}
		});
	});
	//alert("JS RUN TIME: " + start.getMilliseconds() + "ms");
}
function loadActiveAjax(){
	$('.doAjax').each(function(){
		$(this).click(function() {
			var href = $(this).attr('href');
			$.getJSON(href, function(json) {
				var n = noty({
					text	:json.msg,
					timeout	:3000,
					type	:'information',
					layout	:'bottomLeft'
				});
				$.each(json.extra,function(i,item) {
					switch(item.type){
						case '0':{
							$(item.change).attr(item.attr,item.value);
							break;
						}
						case '1':{
							$(item.change).each(function(){
								$(this).fadeOut(300);
							});
							break;
						}
					}
				});
			});
			return false;
		});
	});
}
$(document).ready(function() { 
	loadActiveAjax();
	
	$('.fancy_show').fancybox({
		width		: 350,
		height		: 400,
		autoSize	: false
	});
	$('.fancy_show_admin').fancybox({
		width		: 900,
		height		: 500,
		autoSize	: false
	});
});