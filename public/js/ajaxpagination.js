
function showPage(url,area_id,page)
{
	$.get(url,function(data){
		$("#"+area_id).html(data);	
	});
}
