var schedule = new Array();

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
					break; // REMOVE! Only for testing!
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
	schedule[data.Date]=data;
	
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
	currentDate=new Date(data.Date);
	CreateFooter(currentDate);
}
 
 function CreateFooter(currentDate)
 {
 	download=false;
 	//Prüfen ob akt. Tag == Montag
 	if(currentDate.getDay()==1)
 	{
 		prevdate = new Date(currentDate-86400000);
 		prevdate=DateToUTC(prevdate);
 		if(!(prevdate in schedule))
 		{
 			download=new Date(currentDate-86400000*7);
 		}
 	}
 	//Prüfen ob Freitag
 	else if(currentDate.getDay()==5)
 	{
 		
 	}
 	else
 	{
 		
 	}
 	
 	
 	prevID="schedule-";
 	footer=$(document.createElement("div")); 	
 	footer.attr("data-role","footer").attr("data-position","fixed");
 	
	 	datnavbar=$(document.createElement("div"));	 	
	 	datnavbar.attr("data-role","navbar");
	 	
		 	datnavbarlist=$(document.createElement("ul"));
		 		datnavbarprev=$(document.createElement("li"));
		 		datnavbarprev.append("<a></a>").attr("href","#"+prevID).attr("data-role","button");
 	
 		datnavbar.append(datnavbarlist);
 	footer.append(datnavbar);
 	/*
 	<div data-role="footer" data-position="fixed">
				<div data-role="navbar">
					<ul>
						<li><a href="#Previous" data-role="button" data-iconpos="left">Montag (30.06)</a></li>
						<li><a href="#schedule-20140707">Dienstag (01.07)</a></li>
						<li><a href="#Next" data-role="button" data-iconpos="right">Mittwoch (02.07)</a></li>
					</ul>
				</div>
				
				<div style="text-align:center">
					<select data-native-menu="false" id="ClassTeacherList">
						<option data-placeholder="true">Klasse oder Lehrer ausw&auml;hlen</option>
						<optgroup id="ClassList" label="Klassen"></optgroup>
						<optgroup id="TeacherList" label="Lehrer"></optgroup>
					</select>
				</div>
			</div>
			*/
 }
/*
 * Auto-Start
 */

$(function() {
	FillClassList();
	FillTeacherList();
	LoadScheduleDataForDate("IT11a","2014-07-07");
});
function DateToUTC(date)
{
	utc = date.getFullYear()+"-";
	month=date.getMonth();
	day=date.getDate();
	if(date.getMonth()<10)
	{
		month="0"+month;
	}
	if(date.getDate()<10)
	{
		day = "0"+day;
	}
	utc+=month+"-"+day;
	return utc;
}
