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
	//alert(url);
	$.getJSON(
		url,
		function(response)
		{
			// Weeks
			$.each(response, function(key, value)
			{
				CreateScheduleForDay(value);
				//break; // REMOVE! Only for testing!
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
	currentDate=new Date(data.Date);
	
	pageid = "schedule-"+data.Date.replace(/-/g,'');
	
	page = document.createElement("div");
	page = $(page);
	
	page.attr('data-role','page').attr('id',pageid);
	
	header = document.createElement('div');
	header = $(header);
	
	header.attr('data-role','header').append('<h1>Digikabu.App</h1>');
	
	footer = CreateFooter(currentDate);
	
	content = document.createElement('div');
	content = $(content);
	content.attr('data-role','content');
	
	for(p = 0; p < data.Periods.length; p++)
	{
		lesson = CreateLesson(data.Periods[p]);
		
		content.append(lesson)
	}
	
	page.append(header);
	page.append(content);
	page.append(footer);
	
	page.page();
	page.appendTo($.mobile.pageContainer);
	
	alert(page.html());
	$.mobile.changePage("#"+pageid);
	
	
}
 
 function CreateLesson(data)
 {
 	lesson = $(document.createElement("div"));
 	
 	if (data.Subject.Duration = 3)
 	{
 		lesson.attr("class", "ui-body ui-body-gray triple hr");
 	}
 	else if (data.Subject.Duration = 2)
 	{
 		lesson.attr("class", "ui-body ui-body-gray double hr");
 	}
 	else
 	{
 		lesson.attr("class", "ui-body ui-body-gray single hr");
 	}
 	
 	pTeach = $(document.createElement("p"));
 	teachers = "";
 	for(i = 0; i < data.Teachers.length; i++)
 	{
 		if (i > 1) { teachers += "/"; }
 		teachers += data.Teachers.Abbreviation;
 	}
 	pTeach.html(teachers);
 	lesson.append(pTeach);
 	
 	pName = $(document.createElement("p"));
 	pName.html(data.Subject.Name);
 	lesson.append(pName);
 	
 	pRoom = $(document.createElement("p"));
 	pRoom.html(data.Subject.Rooms);
 	lesson.append(pRoom);
 	
 	pText = $(document.createElement("p"));
 	pText.html(data.Subject.Information);
 	lesson.append(pText);
 	
 	return lesson;
 }
 
 function CreateFooter(currentDate)
 {
 	prevdate = new Date(currentDate-86400000);
 	prevdate_id=DateToUTC(prevdate);
 	
 	nextdate = new Date(currentDate+86400000);
 	nextdate_id=DateToUTC(nextdate);
 	
 	nextID="schedule-"+nextdate_id;
 	prevID="schedule-"+prevdate_id;
 	footer=$(document.createElement("div")); 	
 	footer.attr("data-role","footer").attr("data-position","fixed");
 	
	 	datnavbar=$(document.createElement("div"));	 	
	 	datnavbar.attr("data-role","navbar");
	 	
		 	datnavbarlist=$(document.createElement("ul"));
		 		datnavbarprev=$(document.createElement("li"));
		 			datnavbarprev.append("<a></a>").attr("href","#"+prevID).attr("data-role","button").html(prevdate.getUTCDate());
		 		datnavbarnext=$(document.createElement("li"));
		 			datnavbarnext.append("<a></a>").attr("href","#"+nextID).attr("data-role","button").html(nextdate.getUTCDate());
 			datnavbarlist.append(datnavbarprev);
 			datnavbarlist.append(datnavbarnext);
 		datnavbar.append(datnavbarlist);
 	footer.append(datnavbar);
 	return footer;
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
	LoadScheduleDataForDate("bfi11a","2014-07-07");
});

function DateToUTC(date)
{
	utc = date.getUTCFullYear()+"-";
	month=date.getUTCMonth();
	day=date.getUTCDate();
	if(month<10)
	{
		month="0"+month;
	}
	if(day<10)
	{
		day = "0"+day;
	}
	utc+=month+"-"+day;
	return utc;
}
