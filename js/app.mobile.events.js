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
	
	eventsLoaded = true;
		
 	eventSchedule = $(document.createElement("div"));
 	
 	
 	pDescription = $(document.createElement("p"));
 	
 	pDescription.html(teachers);
 	pDexcription.addClass("teacher");
 	eventSchedule.append(pDescription);
 	
 	pName = $(document.createElement("h3"));
 	pName.html(data.Subject.Name);
 	pName.addClass("lesson");
 	lesson.append(pName);


}
