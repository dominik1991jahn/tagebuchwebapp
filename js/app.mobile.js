	var schedule = new Array();
	var currentClass = null;
	var currentYear = null;
	var currentDisplayMode = "class";
	var isTeacher = false;
	var permissionChecked = false;
	var classList = new Array();
	var teacherList = new Array();
	var isOffline = false;
	
	function request(method, server, url, success, async, refresh)
	{
		if(typeof(async) == "undefined") { async = true; }
		if(typeof(refresh) == "undefined") { refresh = false; }
		
		var fromLocalStorage = null;
		var cacheURL = url;
		
		if(!navigator.onLine)
		{
			/*
			 * If the device is offline (Airplane Mode or no WiFi and no cellular reception)
			 */
			
			DisplayOfflineMessage();
			
			fromLocalStorage = localStorage.getItem(cacheURL);
			response = $.parseJSON(fromLocalStorage);
			
			success(response);
		}
		else
		{
			$.ajax({
				type: method.toUpperCase(),
				url: server+url,
				dataType: 'json',
				beforeSend: function() { $.mobile.loading('show'); },
				complete: function() { $.mobile.loading('hide'); },
				success: function(response)
				{
					fromLocalStorage = localStorage.getItem(cacheURL);
					
					/*
					 * If nothing has changed we receive a 403-code
					 */
					if("code" in response && fromLocalStorage)
					{
						/*switch(response.code)
						{
							case 304:	alert("Nothing changed in '"+cacheURL+"'!"); break;
							default: alert(response.code + ": "+response.message); break;
						}*/
						
						response = $.parseJSON(fromLocalStorage);
					}
					else
					{
						//alert("We need new data for '"+url+"'");
						localStorage.setItem(cacheURL, JSON.stringify(response));
					}
					
					success(response);
				},
				async: async
			});
		}
		
		
		
		
		/*var fromLocalStorage = localStorage.getItem(cacheURL);
		
		if(!fromLocalStorage || refresh)
		{
			$.ajax({
				type: method.toUpperCase(),
				url: server+url,
				dataType: 'json',
				beforeSend: function() {
					$.mobile.loading('show');
					//alert("GET "+url);
				},
				complete: function() { $.mobile.loading('hide'); },
				success: function(response)
				{
					localStorage.setItem(cacheURL, JSON.stringify(response));
					//alert("Added to Cache as "+cacheURL);
					success(response);
				},
				async: async
			});
		}
		else
		{
			//alert("Cached (from '"+cacheURL+"')");
			//alert(cacheURL + ": " + fromLocalStorage);
			success($.parseJSON(fromLocalStorage));
		}*/
	}
	
	function FillClassList(year, htmlObject)
	{
		if(classList.length == 0)
		{
			url = "Classes/" + year;
		
			success = function(response)
						{
							$.each(response, function(key, value)
							{
								classList.push(value.Name);
							});
						};
						
			request("GET","request.php?/",url,success,false);
		}
		
		for(c=0;c<classList.length;c++)
		{
			option = $(document.createElement("option"));
			option.attr("value","c-"+classList[c]);
			option.html(classList[c]);
								
			if(classList[c].toLowerCase() == currentClass)
			{
				option.attr("selected",true);
			}
			
			htmlObject.append(option);
		}
		
		if(htmlObject.prop("tagName") == "SELECT")
		{
			htmlObject.selectmenu('refresh');
		}
		else
		{
			htmlObject.parent().selectmenu("refresh");
		}
	}
	
	function DisplayOfflineMessage()
	{
		if(!isOffline)
		{
			alert("You are offline!");
			isOffline = true;
		}
	}
	
	function FillTeacherList(htmlObject)
	{
		if(teacherList.length == 0)
		{
			url = "Teacher";
		
			success = function(response)
						{
							$.each(response, function(key, value)
							{
								teacherList.push(value.Abbreviation);
							});
						};
						
			request("GET","request.php?/",url,success,false);
		}
		
		for(c=0;c<teacherList.length;c++)
		{
			option = $("<option value=\"t-"+teacherList[c]+"\">"+teacherList[c]+"</option>");
								
			if(teacherList[c].toLowerCase() == currentClass)
			{
				option.attr("selected",true);
			}
			
			htmlObject.append(option);
		}
		
		if(htmlObject.prop("tagName") == "SELECT")
		{
			htmlObject.selectmenu('refresh');
		}
		else
		{
			htmlObject.parent().selectmenu("refresh");
		}
	}
	
	function ChangeCurrentClass()
	{
		var $this = $(this);
		val = $this.val();
		
		currentDisplayMode = "class";
		
		if(val.substring(0,1) == "t") currentDisplayMode = "teacher";
		else if(val.substring(0,1) == "c") currentDisplayMode = "class";
		
		currentClass = val.substring(2).toLowerCase();
		start();
		//alert(val);
	}
	
	function LoadScheduleDataForDate(classcode, startdate, async)
	{
		if(currentDisplayMode == "class")
		{
			var url = "Schedule/Class/" + classcode + "-" + startdate;
		}
		else
		{
			var url = "Schedule/Teacher/" + classcode + "-" + startdate;
		}
		
		success = function(response) {
						if("code" in response)
						{
							switch(response.code)
							{
								case 401:
									
									$.mobile.changePage("#login");
				
									break;
									
								default:
									
									$("#errorCode").html(response.code);
									$("#errorMessage").html(response.message);
									
									$.mobile.changePage("#httperror");
									
									break;
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
							schedule[currentClass][date]=value;
						});
				};
					
		request("GET","request.php?/",url,success,false);
	}
	
	function switchToPage(date, direction, async)
	{
		if(!(date in schedule[currentClass]))
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