function GetCookie(cookieName)
{
	cookies = document.cookie.split(";");
	result = null;
	
	for(c=0;c<cookies.length;c++)
	{
		cookie = cookies[c].split("=");
		
		if(cookie.length>1)
		{
			cname = cookie[0].trim();
			cvalue = cookie[1].trim();
			
			if(cname == cookieName)
			{
				result = cvalue;
			}
		}
	}
	
	return result;
}