<?php
	class HttpRequest
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $url;
		private /*(string)*/ $verb;
		private /*(array:string:string)*/ $data;
		private /*(array:string:string)*/ $headers;
		private /*(string)*/ $useragent;
		
		  //
		 // CONSTRUCTOR
		//
		
		/**
		 * Overloads:
		 * 
		 * Request(String $url)
		 * Request(String $verb, String $url)
		 * Request(String $url, Array<String, String> data)
		 * Request(String $verb, String $url, Array<String, String> $data)
		 * Request(String $url, Array<String, String> data, Array<String, String> header)
		 * Request(String $verb, String $url, Array<String, String> data, Array<String, String> $header)
		 */
		 
		public function HttpRequest()
		{
			$this->verb = "GET";
			$this->useragent = "Digikabu Web-App 0.1a";
			
			switch(func_num_args())
			{
				case 1:
					
					$url = func_get_arg(0);
					
					$this->GETRequest($url);
					
					break;
				
				case 2:
					
					$verb = func_get_arg(0);
					$url = func_get_arg(1);
					
					$this->RequestWithVerbAndURL($verb,$url);
					
					break;
				
				case 3:
					
					break;
					
				case 4:
					
					$verb = func_get_arg(0);
					$url = func_get_arg(1);
					$data = func_get_arg(2);
					$headers = func_get_arg(3);
					
					$this->verb = $verb;
					$this->url = $url;
					$this->data = $data;
					$this->headers = $headers;
					
					break;
					
				default: throw new InvalidArgumentException('Too many arguments passed');
			}
		}
		
		private function GETRequest($url)
		{
			$this->url = $url;
			$this->verb = "GET";
			$this->data = array();
			$this->headers = array();
		}
		
		private function RequestWithVerbAndURL($verb, $url)
		{
			$this->url = $url;
			$this->verb = $verb;
			$this->data = array();
			$this->headers = array();
		}
		
		  //
		 // METHODS
		//
		
		public function SendRequest()
		{
			
			$streamContext = stream_context_create();
			
			stream_context_set_option($streamContext, 'http', "method", $this->verb);
			stream_context_set_option($streamContext, 'http', "user_agent", $this->useragent);
			stream_context_set_option($streamContext, 'http', "follow_location", true);
			stream_context_set_option($streamContext, 'http', "max_redirects", 10);
			stream_context_set_option($streamContext, 'http', "timeout", 15.0);
			stream_context_set_option($streamContext, 'http', "ignore_errors", false);
			
			$url = $this->url;
			
			if(count($this->data))
			{
				$data = http_build_query($this->data);
				
				if($this->verb <> 'GET')
				{
					stream_context_set_option($streamContext, 'http', "content", $data);
				}
				else
				{
					$url .= '?'.$data;
				}
			}
			
			if(count($this->headers))
			{
				$header = "";
				
				foreach($this->headers as $name => $value)
				{
					$header .= $name.": ".$name."\r\n";
				}
				
				stream_context_set_option($streamContext, 'http', "header", $header);
			}
			
			$stream = fopen($url, 'r', false, $streamContext);
			
			if(!$stream)
			{
				throw new HttpRequestFailedException();
			}
			
			$result = stream_get_contents($stream);
			
			return $result;
		}

		public function addHttpHeader($name, $value)
		{
			$this->headers[$name] = $value;
		}
		
		  //
		 // CONSTANTS
		//
		
		const HTTP_VERB_GET = "GET";
		const HTTP_VERB_POST = "POST";
		const HTTP_VERB_PUT = "PUT";
		const HTTP_VERB_DELETE = "DELETE";
	}
?>