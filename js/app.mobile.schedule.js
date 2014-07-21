
	
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
		
		header.attr('data-role','header').append('<h1>'+DayOfWeekToName(currentDate.getDay()) + ", " + Zero(currentDate.getDate()) + "."+Zero(currentDate.getMonth()+1)+'</h1>')
			  .on("dblclick",function() {
			  	gototoday = DateToUTC(new Date());
			  	switchToPage(gototoday,-1);
			  });
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
		ullinks.append("<li><a href=\"#events\" data-role=\"button\">Termine</a></li>").on("click",function(){loadEvents()});
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
			addToContent = $("<div></div>");
			
			lNr = 0;
			for(p = 0; p < data.Periods.length; p++)
			{
				if(data.Periods[p].SplitPeriod == -1)
				{
					content.append(addToContent);
					addToContent = $("<div class=\"ui-grid-a\"></div>");
				}
				
				if (data.Periods[p].Start == 1 && data.Periods[p].Duration == 3)
				{
					lesson = CreateLesson(data.Periods[p], 2, 1); addToContent.append(lesson);
					
					breakShort = CreateBreak(false); addToContent.append(breakShort);
					
					lesson = CreateLesson(data.Periods[p], 1, 3); addToContent.append(lesson);
				}
				else if (data.Periods[p].Start == 2 && (data.Periods[p].Duration >= 2))
				{
					if (data.Periods[p].Duration == 3)
					{
						lesson = CreateLesson(data.Periods[p], 1, 2); addToContent.append(lesson);
					
						breakShort = CreateBreak(false); addToContent.append(breakShort);
						
						lesson = CreateLesson(data.Periods[p], 2, 3); addToContent.append(lesson);
					}
					else
					{
						lesson = CreateLesson(data.Periods[p], 1, 2); addToContent.append(lesson);
					
						breakShort = CreateBreak(false); addToContent.append(breakShort);
						
						lesson = CreateLesson(data.Periods[p], 1, 3); addToContent.append(lesson);
					}
				}
				else
				{
					switch (lNr)
					{
						case 2: lesson = CreateBreak(false);
								addToContent.append(lesson);
								break;
					
						case 5: lesson = CreateBreak( true);
				 				addToContent.append(lesson);
				 				break;
				 				
						case 7: lesson = CreateBreak(false);
								addToContent.append(lesson);
								break;
					}
					
					lesson = CreateLesson(data.Periods[p]);
					addToContent.append(lesson);
					lNr += data.Periods[p].Duration;
				}
				
				if(data.Periods[p].SplitPeriod == 1)
				{
					content.append(addToContent);
					addToContent = $("<div></div>");
				}
			}
			
			content.append(addToContent);
		}
		
		alert(content.html());
		
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
	function CreateLesson(data, length, start)
	{
		duration = 0; startVal = 0;
		
		if (typeof(length) != 'undefined') { duration = 	   length; }
		else 							   { duration = data.Duration; }
		
		if (typeof(start) != 'undefined') { startVal =      start; }
		else 							  { startVal = data.Start; }
		
		lesson = $(document.createElement("div"));
	 	
	 	if (duration == 3)
	 	{
	 		lesson.attr("class", "ui-body triple hr");
	 	}
	 	else if (duration == 2)
	 	{
	 		lesson.attr("class", "ui-body double hr");
	 	}
	 	else
	 	{
	 		lesson.attr("class", "ui-body single hr");
	 	}
	 	
	 	if 		(data.Type == 	    "NORMAL") { lesson.addClass("ui-body-gray"); }
	 	else if (data.Type == "SUBSTITUTION") { lesson.addClass("ui-body-yellow"); }
	 	else if (data.Type ==     "CANCELED") { lesson.addClass("ui-body-red"); }
	 	
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
	 	
	 	if(data.SplitPeriod == -1)
	 	{
	 		lesson.addClass("ui-block-a");
	 	}
	 	else if(data.SplitPeriod == 1)
	 	{
	 		lesson.addClass("ui-block-b");
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
	 	
	 	for(l = 0; l < duration; l++)
	 	{
	 		h5Period = $(document.createElement("h5"));
	 		h5Period.html((startVal+l));
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