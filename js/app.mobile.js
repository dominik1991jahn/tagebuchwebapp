	var schedule = new Array();
	var currentClass = null;
	var classList = new Array();
	
	function request(method, url, success, async)
	{
		if(typeof(async) == "undefined") { async = true; }
		
		$.ajax({
			type: method.toUpperCase(),
			url: url,
			dataType: 'json',
			beforeSend: function() {
				$.mobile.loading('show');
				//alert("GET "+url);
			},
			complete: function() { $.mobile.loading('hide'); },
			success: success,
			async: async
		});
	}
	
	function FillClassList(year, htmlObject)
	{
		if(classList.length == 0)
		{
			url = "request.php?/Classes/" + year;
		
			success = function(response)
						{
							$.each(response, function(key, value)
							{
								classList.push(value.Name);
							});
						};
						
			request("GET",url,success,false);
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
	
	function FillTeacherList(teacherlist)
	{
		var url = "request.php?/Teacher";
		
		success = function(response)
			{
				$.each(response, function(key, value)
				{
					teacherlist.append("<option value=\""+value.Abbreviation+"\">["+value.Abbreviation+"] "+value.Name+"</option>");
				});
				
				teacherlist.selectmenu('refresh');
			};
			
		request("GET",url,success,true);
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
		
		success = function(response) {
						if("code" in response)
						{
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
							schedule[currentClass][date]=value;
						});
				};
					
		request("GET",url,success,false);
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