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
	pageid = "schedule-"+data.Date.replace(/-/g,'');
	
	page = document.createElement("div");
	page = $(page);
	
	page.attr('data-role','page').attr('id',pageid);
	
	header = document.createElement('div');
	header = $(header);
	
	header.attr('data-role','header').append('<h1>Digikabu.App</h1>');
	
	footer = document.createElement('div');
	footer = $(footer);
	
	footer.attr('data-role','footer').append('lol');
	
	content = document.createElement('div');
	content = $(content);
	
	content.attr('data-role','content');
	
	page.append(header);
	page.append(content);
	page.append(footer);
	
	page.page();
	
	page.appendTo($.mobile.pageContainer);
	
	$.mobile.changePage("#"+pageid);
}

/*
 * Auto-Start
 */

$(function() {
	FillClassList();
	FillTeacherList();
	LoadScheduleDataForDate("IT11a","2014-07-07");
});