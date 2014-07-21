
	
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
	 	prevpage="schedule-"+DateToUTC(prevdate)+"-"+currentClass;
	 	
	 	nextdate = new Date(currentDate.getTime()+86400000);
	 	nextpage="schedule-"+DateToUTC(nextdate)+"-"+currentClass;
		
		pageid = "schedule-"+DateToUTC(currentDate)+"-"+currentClass;//data.Date.replace(/-/g,'');
		
		page = document.createElement("div");
		page = $(page);
		
		page.attr('data-role','page').attr('id',pageid);
		page.attr('data-prev',prevpage);
		page.attr('data-next',nextpage);
		
		header = document.createElement('div');
		header = $(header);
		
		header.attr('data-role','header').append('<h1>'+DayOfWeekToName(currentDate.getDay()) + ", " + Zero(currentDate.getDate()) + "."+Zero(currentDate.getMonth()+1)+'</h1>');
		header.attr('data-theme','b');
		
		header2 = document.createElement('div');
		header2 = $(header2);
		
		header2.attr('data-role','header');
		
		navbar = $(document.createElement('div'));
		navbar.attr("data-role","navbar");
		header2.append(navbar);
		
		ullinks = $(document.createElement('ul'));
		navbar.append(ullinks);
		
		ullinks.append("<li><a href=\"#"+pageid+"\" data-role=\"button\">Stundenplan</a></li>");
		ullinks.append("<li><a href=\"#events\" onclick=\"loadEvents()\" data-role=\"button\">Termine</a></li>");
		ullinks.append("<li><a href=\"#bla\" data-role=\"button\">Fehltage</a></li>");
		navbar.navbar();
		
		footer = CreateFooter(currentDate);
		
		content = document.createElement('div');
		content = $(content);
		content.attr('data-role','content');
		
		if(data.Periods.length==0)
		{
			content.append("<h1 style=\"text-align:center\">Kein Unterricht an diesem Tag</h1>");
		}
		else
		{
			lNr = 0;
			for(p = 0; p < data.Periods.length; p++)
			{
				if (data.Periods.Start == 1 && data.Periods[i].Duration == 3)
				{
					lesson = CreateLesson(data.Periods[p]);
				}
				else if (data.Periods.Start == 2 && (data.Periods[i].Duration == 3 || data.Periods[i].Duration == 2))
				{
					lesson = CreateLesson(data.Periods[p]);
				}
				else
				{
					switch (lNr)
					{
						case 2: lesson = CreateBreak(false);
								content.append(lesson);
								break;
					
						case 5: lesson = CreateBreak( true);
				 				content.append(lesson);
				 				break;
				 				
						case 7: lesson = CreateBreak(false);
								content.append(lesson);
								break;
					}
					
					lesson = CreateLesson(data.Periods[p]);
					content.append(lesson);
					lNr += data.Periods[p].Duration;
				}
			}
		}
		
		page.append(header);
		page.append(header2);
		page.append(content);
		page.append(footer);
		
		page.page();
		page.appendTo($.mobile.pageContainer);
	}
	
	function CreateBreak(isItALongBreak)
	{
		if (isItALongBreak)
		{
			longBreak = $(document.createElement("div"));
			longBreak.attr("class", "breakLong");
			
			//h5Period = $(document.createElement("h5"));
	 		//h5Period.html((lNr + 1));
	 		//longBreak.append(h5Period);
	 		
			return longBreak;
		}
		else
		{
			shortBreak = $(document.createElement("div"));
	 		shortBreak.attr("class", "breakShort");
	 		return shortBreak;
		}
	} 
	function CreateLesson(data, length)
	{
		if(typeof(length) == 'undefined') { length = 0; }
		
	 	lesson = $(document.createElement("div"));
	 	
	 	if (data.Duration - length == 3)
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
	 		if (data.Teachers[i].Status == "NORMAL")
	 		{
	 			if (i > 0) { teachers += "/"; }
	 			teachers += data.Teachers[i].Teacher.Abbreviation;
	 		}
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
	 	
	 	for(l = 0; l < data.Duration; l++)
	 	{
	 		h5Period = $(document.createElement("h5"));
	 		h5Period.html((data.Start+l));
	 		lesson.append(h5Period);
	 		
	 		if(l>0)
	 		{
	 			hr = $(document.createElement("hr"));
		 		lesson.append(hr);
		 	}
	 	}
	 	
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
	 			datnavbarlist.append(datnavbarnext);
	 		datnavbar.append(datnavbarlist);
	 		footer.append(datnavbar);
	 		
	 		datclasslist = footer.append("<div></div>");
	 			datclasslist.css("text-align","center");
	 			selectClass = $(document.createElement("select"));
	 			datclasslist.append(selectClass);
	 				selectClass.attr("data-native-menu",false);
	 				selectClass.append("<option data-placeholder=\"true\">Klasse ausw&auml;hlen</option>");
	 				groupClasses = $(document.createElement("optgroup"));
	 				groupClasses.attr("label","Klassen");
	 				selectClass.append(groupClasses);
	 				//groupTeachers = select.append("<optgroup label=\"Lehrer\"></optgroup>");
	 		
	 		selectClass.selectmenu();
	 		selectClass.on('change', ChangeCurrentClass);
	 		
	 		year = currentDate.getUTCFullYear();
	 		if(currentDate.getMonth()+1 < 9)
	 		{
	 			year--;
	 		}
	 		
	 		FillClassList(year, groupClasses);
	 		//FillTeacherList(groupTeachers);
	 		//alert(selectClass.parent().html());
	 	return footer;
	 	/*
	 	
			
		</div>
		*/
	}