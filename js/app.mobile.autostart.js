	/*
	 * Auto-Start
	 */
	
	$(function(){
		checkPermission();
		
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
			if(isTeacher)
			{
				FillTeacherList($("#teacherList"));
			}
		}
		else
		{
			currentClass = GetCookie("class");
			currentDisplayMode = GetCookie("displaymode","class");
			
			currentDate = new Date();
			currentYear = currentDate.getUTCFullYear();
	 		if(currentDate.getMonth()+1 < 9)
	 		{
	 			currentYear--;
	 		}
			
			start();
			
			$(document).keydown(function(event) {
				currPage = $.mobile.activePage;
				goToPage = null;
				direction = 0;
				
				if(event.which == 37)
				{
					goToPage = currPage.attr("data-prev").substring(9,19);
					direction = -1;
				}
				else if(event.which == 39)
				{
					goToPage = currPage.attr("data-next").substring(9,19);
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
			nextPage = currPage.attr("data-next").substring(9,19);
			
			//if(nextPage.length>0)
			{
				switchToPage(nextPage,1);
			}
		});
		
		$(document).on("swiperight", function() {
			currPage = $.mobile.activePage;
			prevPage = currPage.attr("data-prev").substring(9,19);
			//if(prevPage.length>0)
			{
				switchToPage(prevPage,-1);
			}
		});
		
		$(window).on('hashchange', function() {
		    hashChange();
		});

	});
	
	function hashChange()
	{
		hash = location.hash.substring(1);
		
		switch(hash)
		{
			case "settings":
				
				$.mobile.changePage("#Settings");
				FillClassList(2013, $("#SettingsClassList"));
				if(isTeacher)
				{
					FillTeacherList($("#SettingsTeacherList"));
				}
				
				$("#settingsForm").on("submit", settingsHandler);
			
				break;
				
			case "events":
			
				break;
		}
	}
	
	function start()
	{
		startDate = new Date();
		
		while(startDate.getDay() != 1)
		{
			diff = -86400000;
			
			startDate = new Date(startDate.getTime()+diff);
		}
		startDate = DateToUTC(startDate);
		
		LoadScheduleDataForDate(currentClass,startDate,false);
		
		hashDate = location.hash.substring(10,20);
		//alert(hashDate);
		if(hashDate.length==0 || !Date.parse(hashDate))
		{
			currentDate = DateToUTC(new Date());
		}
		else
		{
			currentDate = hashDate;	
		}
		
		switchToPage(currentDate);
	}
	
	function checkPermission()
	{
		if(!permissionChecked)
		{
			url = "Permissions";
		
			success = function(response)
						{
							isTeacher = response;
						};
						
			request("GET","request.php?/",url,success,false);
		}
		
	}