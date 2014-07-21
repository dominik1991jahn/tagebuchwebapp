function DateToPrettyDate(date)
{
	var output = DayOfWeekToName(date.getDay()) + ", ";
	
	output += Zero(date.getDate())+"."+Zero(date.getMonth()+1);
	
	return output;
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