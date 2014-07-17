	var schedule = new Array();
	
	function FillClassList(year, classlist)
	{
		var url = "request.php?/Classes/" + year;
	
		$.getJSON(
			url,
			function(response)
			{
				$.each(response, function(key, value)
				{
					classlist.append("<option>"+value.Name+"</option>");
				});
				
				classlist.selectmenu('refresh');
			});
	}
	
	function FillTeacherList(teacherlist)
	{
		var url = "request.php?/Teacher";
		
		$.getJSON(
			url,
			function(response)
			{
				$.each(response, function(key, value)
				{
					teacherlist.append("<option value=\""+value.Abbreviation+"\">["+value.Abbreviation+"] "+value.Name+"</option>");
				});
				
				teacherlist.selectmenu('refresh');
			});
	}
	
	function LoadScheduleDataForDate(classcode, startdate, async)
	{
		var url = "request.php?/Schedule/Class/" + classcode + "-" + startdate;
		
		$.ajax({
			type: "GET",
			url: url,
			dataType: 'json',
			success: function(response) {
				if("code" in response)
				{
					switch(response.code)
					{
						case "401":
							
							$.mobile.changePage("#login",{
								reverse: direction,
								transition: transition
							});
		
							break;
							
						default: alert(response.message); break;
					}
					
					return;
				}
				
				$.each(response, function(key, value)
				{
					date = value.Date;
					//alert("Received data for " + date);
					schedule[date]=value;
					//CreateScheduleForDay(value);
					//break; // REMOVE! Only for testing!
				});
			},
			async: async
		});
	}
	
	/*
	 * data = JSON-Data
	 */
	function CreateScheduleForDay(data)
	{
		if(!("Date" in data))
		{
			alert("Fehler in CreateScheduleForDay(data)!");
			return;
		}
		
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
		
		headline = content.append('<h1></h1>');
		headline.html(DayOfWeekToName(currentDate.getDay()) + ", " + Zero(currentDate.getDate()) + "."+Zero(currentDate.getMonth()+1));
		
		for(p = 0; p < data.Periods.length; p++)
		{
			if (p == 2)
			{
				//lesson = Crea
			}
			lesson = CreateLesson(data.Periods[p]);
			content.append(lesson)
		}
		
		page.append(header);
		page.append(content);
		page.append(footer);
		
		page.page();
		page.appendTo($.mobile.pageContainer);
	}
	 
	function CreateLesson(data)
	{
	 	lesson = $(document.createElement("div"));
	 	
	 	if (data.Duration == 3)
	 	{
	 		lesson.attr("class", "ui-body ui-body-gray triple hr");
	 	}
	 	else if (data.Duration == 2)
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
	 	pTeach.addClass("teacher");
	 	lesson.append(pTeach);
	 	
	 	pName = $(document.createElement("h3"));
	 	pName.html(data.Subject.Name);
	 	pName.addClass("lesson");
	 	lesson.append(pName);
	 	
	 	pRoom = $(document.createElement("p"));
	 	rooms = "";
	 	for(i = 0; i < data.Rooms.length; i++)
	 	{
	 		if (i > 0) { rooms += "/"; }
	 		rooms += data.Rooms[i];
	 	}
	 	pRoom.html(rooms);
	 	pRoom.addClass("room");
	 	lesson.append(pRoom);
	 	
	 	//pText = $(document.createElement("p"));
	 	//pText.html(data.Subject.Information);
	 	//lesson.append(pText);
	 	
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
	 		
	 		datclasslist = footer.append("<div></div>");
	 			datclasslist.css("text-align","center");
	 			select = $(document.createElement("select"));
	 			datclasslist.append(select);
	 				select.attr("data-native-menu",false);
	 				select.append("<option data-placeholder=\"true\">Klasse ausw&auml;hlen</option>");
	 				groupClasses = select.append("<optgroup label=\"Klassen\"></optgroup>");
	 				//groupTeachers = select.append("<optgroup label=\"Lehrer\"></optgroup>");
	 		
	 		select.selectmenu();
	 		year = currentDate.getUTCFullYear();
	 		if(currentDate.getMonth()+1 < 9)
	 		{
	 			year--;
	 		}
	 		
	 		FillClassList(year, groupClasses);
	 		//FillTeacherList(groupTeachers);
	 		
	 	return footer;
	 	/*
	 	
			
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
		days = ["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag"];
		
		return days[day];
	}
	
	function Zero(num)
	{
		if(num < 10) num = "0"+num;
		
		return num;
	}
	
	function switchToPage(date, direction, async)
	{
		if(!(date in schedule))
		{
			goToDate = new Date(date);
			
			if(goToDate.getTime() < (new Date()).getTime())
			{
				goToDate = new Date(goToDate.getTime() - (86400*1000*6));
			}
			else
			{
				goToDate = new Date(goToDate.getTime());
			}
			
			goToDate = DateToUTC(goToDate);
			//alert("Load date from " + date + " + 7 Days");
			
			LoadScheduleDataForDate("bfi11a", goToDate, true);
		}
		
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
	
	function loginHandler()
	{
		loginname = $("#loginname").val();
		password = $("#password").val();
		
		document.cookie = "loginname="+loginname;
		document.cookie = "password="+password;
	}

	
	/*
	 * Auto-Start
	 */
	
	$(function(){
		$("#loginform").on("submit", loginHandler);
		
		currentDate = DateToUTC(new Date());
		LoadScheduleDataForDate("bfi11a",currentDate,false);
		
		switchToPage(currentDate);
		
		$(document).on("swipeleft", function() {
			currPage = $.mobile.activePage;
			nextPage = currPage.attr("data-next").substring(9);
			switchToPage(nextPage,1);
		});
		
		$(document).on("swiperight", function() {
			currPage = $.mobile.activePage;
			prevPage = currPage.attr("data-prev").substring(9);
			switchToPage(prevPage,-1);
		});
	});