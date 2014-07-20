	var schedule = new Array();
	var currentClass = null;
	
	function FillClassList(year, classlist)
	{
		var url = "request.php?/Classes/" + year;
	
		$.getJSON(
			url,
			function(response)
			{
				$.each(response, function(key, value)
				{
					//GetCookie("class")
					option = $(document.createElement("option"));
					option.attr("value","c-"+value.Name);
					option.html(value.Name);
					
					if(value.Name.toLowerCase() == currentClass)
					{
						option.attr("selected",true);
					}
					
					classlist.append(option);
				});
				
				if(classlist.prop("tagName") == "SELECT")
				{
					classlist.selectmenu('refresh');
				}
				else
				{
					classlist.parent().selectmenu('refresh');
				}
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
	
	function ChangeCurrentClass()
	{
		var $this = $(this);
		val = $this.val();
		
		currentClass = val.substring(2).toLowerCase();
		start();
		//alert(val);
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
					alert(response.code);
					switch(response.code)
					{
						case 401:
							
							$.mobile.changePage("#login",{
								reverse: direction,
								transition: transition
							});
		
							break;
							
						default: alert("Error [" + response.code + "]: " + response.message); break;
					}
					
					return;
				}
				
				$.each(response, function(key, value)
				{
					if(!(currentClass in schedule))
					{
						schedule[currentClass] = new Array();
					}
					
					date = value.Date;
					//alert("Received data for " + date);
					schedule[currentClass][date]=value;
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
		ullinks.append("<li><a href=\"#exams\" data-role=\"button\">Schulaufgaben</a></li>");
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
	 			select = $(document.createElement("select"));
	 			datclasslist.append(select);
	 				select.attr("data-native-menu",false);
	 				select.append("<option data-placeholder=\"true\">Klasse ausw&auml;hlen</option>");
	 				groupClasses = $(document.createElement("optgroup"));
	 				groupClasses.attr("label","Klassen");
	 				select.append(groupClasses);
	 				//groupTeachers = select.append("<optgroup label=\"Lehrer\"></optgroup>");
	 		
	 		select.selectmenu();
	 		select.on('change', ChangeCurrentClass);
	 		
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
			
			LoadScheduleDataForDate(currentClass, goToDate, true);
		}
		
		transition = "none";
		var1=true;
		var2=true;
		
		if(direction<0)
		{
			transition = "slide";
			//rechts nach links
			direction=true;
			var1=false;
		}
		else if(direction>0)
		{
			transition = "slide";
			//links nach rechts
			direction=false;
		}
		
		if($("#schedule-"+date+"-"+currentClass).length == 0)
		{
			CreateScheduleForDay(schedule[currentClass][date]);
		}
		
		$.mobile.changePage("#schedule-"+date+"-"+currentClass,{
			reverse: direction,
			transition: transition
		}//,var1,var2
		);
	}
	
	function loginHandler()
	{
		loginname = $("#loginname").val();
		password = $("#password").val();
		expires = (new Date((new Date().getTime()+(30*86400000)))).toString();
		
		document.cookie = "loginname="+loginname+";expires = "+expires;
		document.cookie = "password="+password+";expires = "+expires;
		
		document.location = 'index.html';
		
		return false;
	}
	
	function classSelectHandler()
	{
		selectedClass = $("#classList").val();
		selectedClass = selectedClass.substring(2).toLowerCase();
		
		expires = (new Date((new Date().getTime()+(300*86400000)))).toString();
		
		document.cookie = "class="+selectedClass+";expires = "+expires;
		
		document.location = 'index.html';
		
		return false;
	}

	function start()
	{
		startDate = new Date();
		displayDate = new Date();
		
		while(startDate.getDay() != 1)
		{
			diff = 0;
			if(startDate.getDay() == 0)
			{
				diff = 86400000;
				displayDate = new Date(displayDate.getTime()+diff);
			}
			else
			{
				diff = -86400000;
			}
			
			startDate = new Date(startDate.getTime()+diff);
		}
		startDate = DateToUTC(startDate);
		
		LoadScheduleDataForDate(currentClass,startDate,false);
		/*hashDate = location.hash.substring(10,20);
		alert(hashDate);
		if(hashDate.length==0 || !Date.parse(hashDate))
		{*/
		currentDate = DateToUTC(displayDate);
		/*}
		else
		{
			currentDate = hashDate;	
		}*/
		
		
		
		switchToPage(currentDate);
	}
	
	function GetCookie(cookieName)
	{
		cookies = document.cookie.split(";");
		result = null;
		
		for(c=0;c<cookies.length;c++)
		{
			cookie = cookies[c].split("=");
			
			if(cookie.length>1)
			{
				cname = cookie[0].trim();
				cvalue = cookie[1].trim();
				
				if(cname == cookieName)
				{
					result = cvalue;
				}
			}
		}
		
		return result;
	}
	
	/*
	 * Auto-Start
	 */
	
	$(function(){
		$("#loginform").on("submit", loginHandler);
		$("#classselectform").on("submit", classSelectHandler);
		
		if(GetCookie("loginname") == null)
		{
			$.mobile.changePage("#login");
		}
		else if(GetCookie("class") == null)
		{
			$.mobile.changePage("#SelectClass");
			FillClassList(2013, $("#classList"));
		}
		else
		{
			currentClass = GetCookie("class");
			
			start();
			
			$(document).keydown(function(event) {
				currPage = $.mobile.activePage;
				goToPage = null;
				direction = 0;
				
				if(event.which == 37)
				{
					goToPage = currPage.attr("data-prev").substring(9);
					direction = -1;
				}
				else if(event.which == 39)
				{
					goToPage = currPage.attr("data-next").substring(9);
					direction = +1;
				}
				
				if(direction != 0)
				{
					switchToPage(goToPage,direction);
				}
			});
		}
		
		$(document).on("swipeleft", function() {
			currPage = $.mobile.activePage;
			nextPage = currPage.attr("data-next").substring(9);
			//if(nextPage.length>0)
			{
				switchToPage(nextPage,1);
			}
		});
		
		$(document).on("swiperight", function() {
			currPage = $.mobile.activePage;
			prevPage = currPage.attr("data-prev").substring(9);
			//if(prevPage.length>0)
			{
				switchToPage(prevPage,-1);
			}
		});

	});