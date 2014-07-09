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
	var url = "request.php?/Schedule/Class/" + classcode + "-" + startdate;
	
	$.getJSON(
		url,
		function(response)
		{
			// Weeks
			$.each(response, function(key, value)
			{
				for(d = 0; d < value.Days.length; d++)
				{
					CreateScheduleForDay(value.Days[d]);
				}
			});
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
	page.page();
	
	page.appendTo($.mobile.pageContainer);
	
	page.pagecontainer("change");
}

/*
 * Auto-Start
 */

$(function() {
	FillClassList();
	FillTeacherList();
	LoadScheduleDataForDate("IT11a","2014-07-07");
});