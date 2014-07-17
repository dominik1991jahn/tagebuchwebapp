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
		
		$.ajax({
			type: "GET",
			url: url,
			dataType: 'json',
			success: function(response) {
				$.each(response, function(key, value)
				{
					date = value.Date;
					schedule[date]=value;
					//CreateScheduleForDay(value);
					//break; // REMOVE! Only for testing!
				});
			},
			async: false
		});
	}
	
	/*
	 * data = JSON-Data
	 */
	function CreateScheduleForDay(data)
	{
		currentDate=new Date(data.Date);
		
		prevdate = new Date(currentDate.getTime()-86400000);
	 	prevpage="schedule-"+DateToUTC(prevdate);
	 	
	 	nextdate = new Date(currentDate.getTime()+86400000);
	 	nextpage="schedule-"+DateToUTC(nextdate);
		
		pageid = "schedule-"+DateToUTC(currentDate);//data.Date.replace(/-/g,'');
		
		page = document.createElement("div");
		page = $(page);
		
		page.attr('data-role','page').attr('id',pageid);
		page.attr('data-prev',prevpage);
		page.attr('data-next',nextpage);
		
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
		//alert($("body").html());
		//alert(page.html());
		
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
	 		if (i > 0) { teachers += "/"; }
	 		teachers += data.Teachers[i].Teacher.Abbreviation;
	 	}
	 	
	 	pTeach.html(teachers);
	 	lesson.append(pTeach);
	 	
	 	pName = $(document.createElement("p"));
	 	pName.html(data.Subject.Name);
	 	lesson.append(pName);
	 	
	 	pRoom = $(document.createElement("p"));
	 	rooms = "";
	 	for(i = 0; i < data.Rooms.length; i++)
	 	{
	 		if (i > 0) { rooms += "/"; }
	 		rooms += data.Rooms[i];
	 	}
	 	pRoom.html(rooms);
	 	lesson.append(pRoom);
	 	
	 	pText = $(document.createElement("p"));
	 	pText.html(data.Subject.Information);
	 	lesson.append(pText);
	 	
	 	return lesson;
	}
	 
	function CreateFooter(currentDate)
	{
	 	prevdate = new Date(currentDate.getTime()-86400000);
	 	prevdate_id=DateToUTC(prevdate);
	 	
	 	nextdate = new Date(currentDate.getTime()+86400000);
	 	nextdate_id=DateToUTC(nextdate);
	 	
	 	nextID="schedule-"+nextdate_id;
	 	prevID="schedule-"+prevdate_id;
	 	footer=$(document.createElement("div")); 	
	 	footer.attr("data-role","footer").attr("data-position","fixed");
	 	
		 	datnavbar=$(document.createElement("div"));	 	
		 	datnavbar.attr("data-role","navbar");
		 	
			 	datnavbarlist=$(document.createElement("ul"));
			 		datnavbarprev=$(document.createElement("li"));
			 			link = $(document.createElement("a"));
			 			link.attr("href","#"+prevID).attr("data-role","button").html(DayOfWeekToName(prevdate.getDay()) + " (" + Zero(prevdate.getDate()) + "." + Zero((prevdate.getMonth()+1))  + ")");
			 			link.on("click",function(data)
			 			{
			 				link = data.target.href.split('#');
			 				link = link[1].substring(9);
			 				switchToPage(link,-1);
			 				return false;
			 			});
			 			datnavbarprev.append(link);
			 		datnavbarcurr=$(document.createElement("li"));
			 			link = $(document.createElement("a"));
			 			link.attr("href","#"+prevID).attr("data-role","button").html(DayOfWeekToName(currentDate.getDay()) + " (" + Zero(currentDate.getDate()) + "." + Zero((currentDate.getMonth()+1))  + ")");
			 			link.attr("data-theme","yellow");
			 			link.on("click",function(data)
			 			{
			 				return false;
			 			});
			 			datnavbarcurr.append(link);
			 		datnavbarnext=$(document.createElement("li"));
			 			link = $(document.createElement("a"));
			 			link.attr("href","#"+nextID).attr("data-role","button").html(DayOfWeekToName(nextdate.getDay()) + " (" + Zero(nextdate.getDate()) + "." + Zero((nextdate.getMonth()+1))  + ")");
	 					link.on("click",function(data)
			 			{
			 				link = data.target.href.split('#');
			 				link = link[1].substring(9);
			 				switchToPage(link,1);
			 				return false;
			 			});
	 					datnavbarnext.append(link);
	 			datnavbarlist.append(datnavbarprev);
	 			datnavbarlist.append(datnavbarcurr);
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
	
	function DateToUTC(date)
	{
		utc = date.getUTCFullYear()+"-";
		
		month=date.getUTCMonth()+1;
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
	
	function DayOfWeekToName(day)
	{
		days = ["Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag","Sonntag"];
		
		return days[day];
	}
	
	function Zero(num)
	{
		if(num < 10) num = "0"+num;
		
		return num;
	}
	
	function switchToPage(date, direction)
	{
		if(!(date in schedule))
		{
			alert(unescape("F%FCr dieses Datum sind keine Daten vorhanden!"));
		}
		else
		{
			transition = "none";
			
			if(direction<0)
			{
				transition = "slide";
				direction=true;
			}
			else if(direction>0)
			{
				transition = "slide";
				direction=false;
			}
			CreateScheduleForDay(schedule[date]);
			$.mobile.changePage("#schedule-"+date,{
				reverse: direction,
				transition: transition
			});
		}
	}

	
	/*
	 * Auto-Start
	 */
	
	$(function(){
		currentDate = DateToUTC(new Date());
		LoadScheduleDataForDate("bfi11a",currentDate);
		
		switchToPage(currentDate);
	});