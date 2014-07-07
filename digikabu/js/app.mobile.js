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
			});
			
			$("#ClassTeacherList").selectmenu('refresh');
		});
}

function FillTeacherList()
{
	var url = "request.php?/Teacher";
	
	$.getJSON(
		url,
		function(response)
		{
			$.each(response, function(key, value)
			{
				$("#TeacherList").append("<option value=\""+value.Abbreviation+"\">["+value.Abbreviation+"] "+value.Name+"</option>");
			});
			
			$("#ClassTeacherList").selectmenu('refresh');
		});
}

function LoadScheduleDataForDate(classcode, startdate)
{
	var url = "request.php?/Schedule/" + classcode + "-" + startdate + ".xml";
	
	$.getJSON(
		url,
		function(response)
		{
			
		}
	)
}

/*
 * data = JSON-Data
 */
function CreateScheduleForDay(data)
{
	date = Date.parse(data.Date);
	pageid = "schedule-"+date;
	
	page = document.createElement("div");
	page = $(page);
	
	page.attr('data-role','page').attr('id',pageid);
	
	$(document).append(page);
}

/*
 * Auto-Start
 */

$(function() {
	FillClassList();
	FillTeacherList();
	CreateScheduleForDay({Date:'2014-07-08'});
});