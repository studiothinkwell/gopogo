// ====CALLING FOR AJAX PAGE====//
function showPage(url, area_id, page,activeform,tech_id) {
	var str='/area/' + area_id + '/p/' + page;
	
	$.get(url + str, function(data) {
		$("." + area_id).html(data);
	});
}
