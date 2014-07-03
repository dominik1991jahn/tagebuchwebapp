function FillClassList()
{
	var url = "request.php?/Class";
	
	$.getJSON(
		url,
		function(response)
		{
			$.each(response, function(key, value)
			{
				$("#ClassList").append("<option>"+value.Name+"</option>");
			})
			
			$("#ClassTeacherList").selectmenu('refresh');
		});
}