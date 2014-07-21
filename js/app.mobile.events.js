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
	
	page.append("<h1>Termine</h1>");
	
	eventsLoaded = true;
}
