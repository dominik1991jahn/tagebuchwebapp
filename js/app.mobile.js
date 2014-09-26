	var schedule = new Array();
	var currentClass = null;
	var currentYear = null;
	var currentDisplayMode = "class";
	var isTeacher = false;
	var permissionChecked = false;
	var classList = new Array();
	var teacherList = new Array();
	var isOffline = false;
	var usingCache = false;
	
	function request(method, server, url, success, async, refresh)
	{
		if(typeof(async) == "undefined") { async = true; }
		if(typeof(refresh) == "undefined") { refresh = false; }
		
		var fromLocalStorage = null;
		var cacheURL = url;
		//alert(server+url);
		if(!navigator.onLine)
		{
			/*
			 * If the device is offline (Airplane Mode or no WiFi and no cellular reception)
			 */
			
			if(LocalStorageAvailability())
			{
				fromLocalStorage = localStorage.getItem(cacheURL);
			}
			
			if(fromLocalStorage)
			{
				response = $.parseJSON(fromLocalStorage);
				success(response);
			}
			else
			{
				alert("Sorry, du bist nicht online und hast keine Daten im Cache!");
			}
			
			isOffline = true;
		}
		else
		{
			isOffline = false;
			
			if(LocalStorageAvailability())
			{
				fromLocalStorage = localStorage.getItem(cacheURL);
			}
			
			headers = null;
			
			/*
			 * ToDo: Load from cache, but offer update button
			 * 
			 * Offline: Red, use cache
			 * Online, but loaded from cache: orange + update link
			 * Online, fresh data: green
			 */
			
			if(fromLocalStorage == null || refresh)
			{
				usingCache = false;
				//alert("NO CACHE! for " + url);
				headers = {"Cache-Control":"no-cache"};
			}
			else
			{
				usingCache = true;
			}
			
			$.ajax({
				type: method.toUpperCase(),
				url: server+url,
				dataType: 'json',
				headers: headers,
				beforeSend: function() { $.mobile.loading('show'); },
				complete: function() { $.mobile.loading('hide'); },
				success: function(response)
				{
					if("code" in response && fromLocalStorage != null)
					{
						response = $.parseJSON(fromLocalStorage);
					}
					else if(LocalStorageAvailability())
					{
						localStorage.setItem(cacheURL, JSON.stringify(response));
					}
					
					success(response);
				},
				error: function(req, status, error) {
					alert("Status: "+status+"\nError: "+error);
				},
				async: async
			});
		}
	}
	
	function FillClassList(year, htmlObject)
	{
		if(classList.length == 0)
		{
			url = "Classes/" + year;
		
			success = function(response)
						{
							//alert(response.code);
							$.each(response.data, function(key, value)
							{
								//alert(value.Name);
								classList.push(value.Name);
							});
						};
						
			request("GET","request/",url,success,false);
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
		if(isOffline)
		{
			$("div.status").removeClass("cache");
			$("div.status").removeClass("online");
			
			$("div.status").addClass("offline");
			$("#OfflineStatusMessage").html("Offline! Daten sind nicht auf dem aktuellsten Stand!");	
		}
		else if(isOffline && !usingCache)
		{
			$("div.status").removeClass("cache");
			$("div.status").removeClass("offline");
			
			$("div.status").addClass("online");
			$("#OfflineStatusMessage").html("Online! Daten wurden aktualisiert.");
		}
		else
		{
			$("div.status").removeClass("online");
			$("div.status").removeClass("offline");
			
			$("div.status").addClass("cache");
			$("#OfflineStatusMessage").html("Online! Daten aktualisieren.");
		}
		
		
	}
	
	function FillTeacherList(htmlObject)
	{
		if(teacherList.length == 0)
		{
			url = "Teacher";
		
			success = function(response)
						{
							$.each(response.data, function(key, value)
							{
								teacherList.push(value.Abbreviation);
							});
						};
						
			request("GET","request/",url,success,false);
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
						//alert(response.code);
						switch(response.code)
						{
							case 200: break;
							case 401: $.mobile.changePage("#login"); return;
								
							default:
								
								$("#errorCode").html(response.code);
								$("#errorMessage").html(response.message);
								
								$.mobile.changePage("#httperror");
								return;
						}
						
						$.each(response.data, function(key, value)
						{
							if(!(currentClass in schedule))
							{
								schedule[currentClass] = new Array();
							}
							
							date = value.Date;
							schedule[currentClass][date]=value;
						});
				};
					
		request("GET","request/",url,success,false);
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
		
		if(direction<0)
		{
			transition = "slide";
			direction=true; // <---
		}
		else if(direction>0)
		{
			transition = "slide";
			direction=false; // --->
		}
		
		if($("#schedule-"+date+"-"+currentClass).length == 0)
		{
			CreateScheduleForDay(schedule[currentClass][date]);
		}
		
		$.mobile.changePage("#schedule-"+date+"-"+currentClass,{
			reverse: direction,
			transition: transition
		});
		
		currentDate = new Date(date);
		document.title = "Digikabu.App :: " + DayOfWeekToName(currentDate.getDay()) + ", " + Zero(currentDate.getDate()) + "."+Zero(currentDate.getMonth()+1);
	}

function LocalStorageAvailability()
{
	try {
        localStorage.setItem("test", "test");
        localStorage.removeItem("test");
        return true;
    } catch(e) {
        return false;
    }
}