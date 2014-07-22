/**
 * @author zilcha
 */

var eventsLoaded = false;

function loadEvents()
{

	
	if(eventsLoaded) return true;
	
	page = $("<div data-role=\"page\" id=\"events\"></div>");
	
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
	
	ullinks.append("<li><a href=\"#"+pageid+"\" data-role=\"button\">Stundenplan</a></li>");
	ullinks.append("<li><a href=\"#events\" data-role=\"button\">Termine</a></li>").on("click",function(){loadEvents()});
	ullinks.append("<li><a href=\"#bla\" data-role=\"button\">Fehltage</a></li>");
	navbar.navbar();

	content = document.createElement('div');
	content = $(content);
	content.attr('data-role','content');
	
	modeSwitcher = $("<div data-role=\"controlgroup\" data-type=\"horizontal\"></div>");
	futureSwitch = $("<button data-role=\"button\">Aktuelle Termine</button>").on("click", function() { FillEvents(currentClass, currentYear, "future"); });
	pastSwitch = $("<button data-role=\"button\">Vergangene Termine</button>").on("click", function() { FillEvents(currentClass, currentYear, "past"); });
	modeSwitcher.append(futureSwitch);
	modeSwitcher.append(pastSwitch);
	
	content.append(modeSwitcher);
	
	events = $("<div id=\"eventList\"></div>");
	
	content.append(events);
	
	page.append(header);
	page.append(header2);
	page.append(content);
	
	FillEvents(currentClass, currentYear, "future");
	page.page();
	
	eventsLoaded = true;
}

function FillEvents(currentClass, year, mode)
{
	url = "request.php?/Events/" + currentClass + "/" + year + "/" + mode;
	
	$("#eventList").empty();
	
	success = function(response)
				{
					$.each(response, function(key, value)
					{
						eventSchedule = $(document.createElement('div'));
						eventSchedule.attr("class", "ui-body ui-body-gray single hr");
						
						
					 	pDescription = $(document.createElement("h3"));
					 	
					 	pDescription.html(value.Description);
					 	pDescription.addClass("lesson");
					 	eventSchedule.append(pDescription);
					 	
					 	pDateFrom = $(document.createElement("p"));
					 	pDateTo   = $(document.createElement("p"));
					 	if(value.To == null)
					 	{
					 		pDateFrom.html(DateToPrettyDate(new Date(value.From)));
					 		pDateFrom.addClass("teacher");
					 	}
					 	else
					 	{
					 		pDateFrom.html("von " + DateToPrettyDate(new Date(value.From)));
					 		pDateFrom.addClass("teacher");
					 		
					 		pDateTo.html("bis " + DateToPrettyDate(new Date(value.To)));
					 		pDateTo.addClass("room");
					 	}
					 	eventSchedule.append(pDateFrom);
					 	eventSchedule.append(pDateTo);

					 	$("#eventList").append(eventSchedule);	
					});
				};
				
	request("GET",url,success,false);
}
