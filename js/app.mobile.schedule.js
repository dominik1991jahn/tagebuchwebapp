
	
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
		
		header.attr('data-role','header').append('<h1 style=\"text-align:center\">'+DayOfWeekToName(currentDate.getDay()) + ", " + Zero(currentDate.getDate()) + "."+Zero(currentDate.getMonth()+1)+'</h1>')
			  .on("click",function() {
			  	var $this = $(this);
				$this.empty();
				
				datePicker = $("<input type=\"text\" data-role=\"date\" data-mini=\"true\" data-theme=\"b\" id=\"datepicker\" value=\""+DateToGermanFormat(currentDate)+"\" style=\"text-align:center\" />");
				datePicker.datepicker(
					{
						dateFormat: "dd.mm.yy",
						showOtherMonths: true,
						changeMonth:true,
						onSelect: function(selectedDate, dpinst)
						{
							date = DateToUTC(GermanDateToDate(selectedDate));
							
							switchToPage(date, -1);
							
							alert($this.html());
						}
					});
				
				$this.append(datePicker);
				$this.trigger("create");
			  });
		header.attr('data-theme','b');
		
		header.append("<a href=\"#settings\" data-icon=\"gear\" data-iconpos=\"notext\" data-role=\"button\" class=\"ui-btn-right\" data-ajax=\"false\">Einstellungen</a>");
		
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
		navbar.navbar();
		
		header3 = $("<div data-role=\"header\" class=\"status offline\"><h5>Offline! Daten sind nicht auf dem aktuellsten Stand</h5></div>");
		
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
				endLastLesson = null;
				lesson = null;
				
				if(p>0 && data.Periods[p-1].Start != data.Periods[p].Start)
				{
					endLastLesson = data.Periods[p-1].Start + data.Periods[p-1].Duration;
				}
				else if(p == 0 && data.Periods[p].Start > 1)
				{
					endLastLesson = 1;
				}
				
				if (endLastLesson != null && (endLastLesson != data.Periods[p].Start && data.Periods[p].Start != 7))
				{
					lNr+=data.Periods[p].Start - endLastLesson;
					lesson = CreateEmptyLesson(data.Periods[p].Start - endLastLesson, endLastLesson);
					content.append(lesson);
				}
				
				if (data.Periods[p].Start == 1 && data.Periods[p].Duration == 3)
				{
					lesson = CreateLesson(data.Periods[p], data.Date, 2, 1); content.append(lesson);
					
					breakShort = CreateBreak(1); content.append(breakShort);
					
					lesson = CreateLesson(data.Periods[p], data.Date, 1, 3); content.append(lesson);
				}
				else if (data.Periods[p].Start == 2 && (data.Periods[p].Duration >= 2))
				{
					if (data.Periods[p].Duration == 3)
					{
						lesson = CreateLesson(data.Periods[p], data.Date, 1, 2); content.append(lesson);
					
						breakShort = CreateBreak(1); content.append(breakShort);
						
						lesson = CreateLesson(data.Periods[p], data.Date, 2, 3); content.append(lesson);
					}
					else
					{
						lesson = CreateLesson(data.Periods[p], data.Date, 1, 2); content.append(lesson);
					
						breakShort = CreateBreak(1); content.append(breakShort);
						
						lesson = CreateLesson(data.Periods[p], data.Date, 1, 3); content.append(lesson);
					}
				}
				else
				{
					switch (lNr)
					{
						case 2: lesson = CreateBreak(1);
								content.append(lesson);
								break;
					
						case 5: if(data.Periods[p].Start != 6)
								{
									lesson = CreateBreak(2);
					 				content.append(lesson);
					 			}
					 			break;
				 				
						case 7: lesson = CreateBreak(3);
				 				content.append(lesson);
								break;
					}
					
					lesson = CreateLesson(data.Periods[p], data.Date);
					content.append(lesson);
				}
				
				if(lesson != null) lNr += data.Periods[p].Duration;
			}
		}
		
		//alert(content.html());
		page.append(header);
		page.append(header2);
		page.append(header3);
		page.append(content);
		page.append(footer);
		
		page.page();
		page.appendTo($.mobile.pageContainer);
	}
	
	function CreateBreak(BreakNr)
	{
		Break = $(document.createElement("div"));
		switch (BreakNr)
		{
			case 1: Break.attr("class", "breakNorm" ); break;
			case 2: Break.attr("class", "breakLong" ); break;
			case 3: Break.attr("class", "breakShort"); break;
		}
		return Break;
	}
	function CreateEmptyLesson(duration, startVal)
	{
		lesson = $(document.createElement("div"));
		
		if (duration == 3)
	 	{
	 		lesson.attr("class", "ui-body emptyLesson hr triple");
	 	}
	 	else if (duration == 2)
	 	{
	 		lesson.attr("class", "ui-body emptyLesson hr double");
	 	}
	 	else
	 	{
	 		lesson.attr("class", "ui-body emptyLesson hr single");
	 	}
	 	
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
	 	
		return lesson;
	}
	function CreateLesson(data, date, length, start)
	{
		duration = 0; startVal = 0;
		
		if (typeof(length) != 'undefined') { duration = 	   length; }
		else 							   { duration = data.Duration; }
		
		if (typeof(start) != 'undefined') { startVal =      start; }
		else 							  { startVal = data.Start; }
		
		lesson = $("<div></div>");
	 	lesson.on("click", function() { OpenDetailPopup(data, date); } );
	 	
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
	 	ateachers = new Array();
	 	for(i = 0; i < data.Teachers.length; i++)
	 	{
	 		if (data.Teachers[i].Status == "NORMAL" || data.Type == "CANCELED" || data.Teachers[i].Status == "SUBSTITUTE")
	 		{
	 			ateachers.push(data.Teachers[i]);
	 		}
	 	}
	 	
	 	teachers = "";
	 	
	 	for(t=0;t<ateachers.length;t++)
	 	{
	 		teachers += "<span class=\"teacher-"+ateachers[t].Status.toLowerCase()+"\">"+ateachers[t].Teacher.Abbreviation+"</span>";
	 		
	 		if(t+1 < ateachers.length) teachers += " / ";
	 	}
	 	
	 	if(data.SplitPeriod == -1)
	 	{
	 		lesson.addClass("splitperiod splitperiodLeft");
	 	}
	 	else if(data.SplitPeriod == 1)
	 	{
	 		lesson.addClass("splitperiod splitperiodRight");
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
	 	
	 	if(data.Class != "" && currentDisplayMode == "teacher")
	 	{
	 		rooms = data.Class + " @ ";
	 	}
	 	
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
	
	var loadedPopups = new Array();
	
	function OpenDetailPopup(data,date)
	{
		dialogid = "lesson-"+date+"-"+data.Subject.Name+"-"+data.Subject.Start+"-"+data.SplitPeriod;
		
		if(!(dialogid in loadedPopups))
		{
			dialog = $("<div data-role=\"dialog\" data-transition=\"slidedown\" data-close-btn=\"right\" id=\""+dialogid+"\"></div>");
			
				dHeader = $("<div data-role=\"header\" style=\"text-align:center\"></div>");
					pLesson = $("<p></p>");
					pLesson.html(data.Subject.Name);
					pLesson.addClass("lesson");
					dHeader.append(pLesson);
				dialog.append(dHeader);
				
				dBody = $("<div data-role=\"content\" style=\"text-align:center\"></div>");
					pTeach = $("<p></p>");
						teachers = "";
						for (i = 0; i < data.Teachers.length; i++)
						{
							if (i > 0) { teachers += " / "; }
							teachers += "<span class=\"teacher-"+data.Teachers[i].Status.toLowerCase()+"\">"+data.Teachers[i].Teacher.Abbreviation+"</span>";
						}
					pTeach.html(teachers);
					pTeach.addClass("teacher");
					dBody.append(pTeach);
					
					pText = $("<p style=\"border-top:dotted;border-width: 1px;\"></p>");
					pText.html(data.Subject.Information);
					pText.addClass("text");
					dBody.append(pText);
				dialog.append(dBody);
			
			dialog.dialog({autoResize:true});
			
			dialog.appendTo($.mobile.pageContainer);
		}
		
		$.mobile.changePage("#"+dialogid, {role:"dialog"});
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
	 	footer.attr("data-role","footer").attr("data-position","fixed").css("text-align","center").attr("data-theme","b");
	 	
	 	linkprev = $("<a href=\"#"+prevID+"\" data-role=\"button\" data-icon=\"arrow-l\" data-iconpos=\"left\" class=\"ui-btn-left\" data-mini=\"true\" data-inline=\"true\">"+DayOfWeekToShortName(prevdate.getDay()) + " (" + Zero(prevdate.getDate()) + "." + Zero((prevdate.getMonth()+1))  + ")</a>");
	 	linkprev.on("click",function(data)
			 			{
			 				link = data.target.href.split('#');
			 				link = link[1].substring(9);
			 				switchToPage(link,-1);
			 				return false;
			 			});
			 			
		linknext = $("<a href=\"#"+nextID+"\" data-role=\"button\" data-icon=\"arrow-r\" data-iconpos=\"right\" class=\"ui-btn-right\" data-mini=\"true\" data-inline=\"true\">"+DayOfWeekToShortName(nextdate.getDay()) + " (" + Zero(nextdate.getDate()) + "." + Zero((nextdate.getMonth()+1))  + ")</a>");
	 	linknext.on("click",function(data)
			 			{
			 				link = data.target.href.split('#');
			 				link = link[1].substring(9);
			 				switchToPage(link,1);
			 				return false;
			 			});
	 	
	 	selectClass = $("<select data-native-menu=\"false\" data-theme=\"b\" data-inline=\"true\" data-mini=\"true\" style=\"\"></select>");
	 		selectClass.append("<option data-placeholder=\"true\">Klasse oder Lehrer ausw&auml;hlen</option>");
	 			groupClasses = $("<optgroup label=\"Klassen\"></optgroup>");
	 			groupTeachers = $("<optgroup label=\"Lehrer\"></optgroup>");
	 		selectClass.append(groupClasses);
			selectClass.append(groupTeachers);
	 		
	 	footer.append(linkprev);
	 	footer.append(selectClass);
	 	footer.append(linknext);
	 	
 		selectClass.selectmenu();
 		selectClass.on('change', ChangeCurrentClass);
	 	
	 	year = currentDate.getUTCFullYear();
 		if(currentDate.getMonth()+1 < 9)
 		{
 			year--;
 		}
 		
 		FillClassList(year, groupClasses);
 		
 		if(isTeacher)
 		{
 			FillTeacherList(groupTeachers);
 		}
		 	/*datnavbar=$(document.createElement("div"));	 	
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
	 		footer.append(datnavbar);*/
	 		
	 		/*
	 		datclasslist = footer.append("<div></div>");
	 			datclasslist.css("text-align","center");
	 			selectClass = $("<select data-native-menu=\"false\"></select>");
	 			datclasslist.append(selectClass);
	 				selectClass.append("<option data-placeholder=\"true\">Klasse oder Lehrer ausw&auml;hlen</option>");
	 				groupClasses = $("<optgroup label=\"Klassen\"></optgroup>");
	 				groupTeachers = $("<optgroup label=\"Lehrer\"></optgroup>");
	 				selectClass.append(groupClasses);
	 				selectClass.append(groupTeachers);
	 		
	 		selectClass.selectmenu();
	 		selectClass.on('change', ChangeCurrentClass);
	 		
	 		year = currentDate.getUTCFullYear();
	 		if(currentDate.getMonth()+1 < 9)
	 		{
	 			year--;
	 		}
	 		
	 		FillClassList(year, groupClasses);
	 		
	 		if(isTeacher)
	 		{
	 			FillTeacherList(groupTeachers);
	 		}
	 		FillTeacherList(groupTeachers);*/
	 		
	 	return footer;
	 	/*
	 	
			
		</div>
		*/
	}