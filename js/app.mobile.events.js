/**
 * @author zilcha
 */

var eventsLoaded = false;

function loadEvents()
{

	
	if(eventsLoaded) return true;
	
	page = $("<div data-role=\"page\" id=\"events\"></div>");
	
	page.page();
	page.appendTo($.mobile.pageContainer);
	
	header = document.createElement('div');
	header = $(header);
	
	header.attr('data-role','header').append('<h1>Termine</h1>');
	header.attr('data-theme','b');
	
	header2 = document.createElement('div');
	header2 = $(header2);
	
	header2.attr('data-role','header');
	
	navbar = $(document.createElement('div'));
	navbar.attr("data-role","navbar");
	header2.append(navbar);
	
	ullinks = $(document.createElement('ul'));
	navbar.append(ullinks);
	
	ullinks.append("<li><a href=\"#bla\" data-role=\"button\">Stundenplan</a></li>");
	ullinks.append("<li><a href=\"#events\" data-role=\"button\">Termine</a></li>").on("click",loadEvents);
	ullinks.append("<li><a href=\"#bla\" data-role=\"button\">Fehltage</a></li>");
	navbar.navbar();

	page.append(header);
	page.append(header2);
	
	FillEvents(currentClass, 2013);
	
	eventsLoaded = true;
		
 	

}

function FillEvents(currentClass, year)
{
	if(classList.length == 0)
	{
		url = "request.php?/Events/" + currentClass + "/" + year;
	
		success = function(response)
					{
						$.each(response, function(key, value)
						{
							eventSchedule = $(document.createElement("div"));
						 	pDescription = $(document.createElement("p"));
						 	
						 	pDescription.html(value.description);
						 	//pDexcription.addClass("teacher");
						 	eventSchedule.append(pDescription);
						 	
						 	pDate = $(document.createElement("p"));
						 	pDate.html(value.date);
						 	//pDate.addClass("lesson");
						 	eventSchedule.append(pName);
						});
					};
					
		request("GET",url,success,false);
	}
}
