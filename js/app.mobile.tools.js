function DateToPrettyDate(date)
{
	var output = DayOfWeekToName(date.getDay()) + ", ";
	
	output += Zero(date.getDate())+"."+Zero(date.getMonth()+1) + "." + date.getFullYear();
	
	return output;
}

function DateToGermanFormat(date)
{
	result = Zero(date.getUTCDate())+"."+Zero(date.getUTCMonth()+1)+"."+date.getUTCFullYear();
	return result;
}

function GermanDateToDate(datestring)
{
	datestring = datestring.split(".");
	
	day   = datestring[0];
	month = datestring[1];
	year  = datestring[2];
	
	utc = year+"-"+month+"-"+day;
	
	return new Date(utc);
}

function SecToTime(seconds)
{
	hr  = seconds % 3600;
	seconds -= hr * 3600;
	min = seconds %   60;
	
	return hr.toString() + ":" + min.toString();
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

function DayOfWeekToShortName(day)
{
	days = ["So","Mo","Di","Mi","Do","Fr","Sa"];
	
	return days[day];
}

function Zero(num)
{
	if(num < 10) num = "0"+num;
	
	return num;
}