<?php
	class HttpRequest
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $url;
		private /*(string)*/ $verb;
		private /*(RequestData)*/ $data;
		private /*(HeaderData)*/ $headers;
		private /*(string)*/ $responsebody;
		private /*(array<String>)*/ $responseheaders;
		
		  //
		 // CONSTRUCTOR
		//
		
		/**
		 * Overloads:
		 * 
		 * Request(String $url)
		 * Request(String $verb, String $url)
		 * Request(String $url, RequestData data)
		 * Request(String $verb, String $url, RequestData $data)
		 * Request(String $verb, String $url, HeaderData $data)
		 * Request(String $url, RequestData data, HeaderData header)
		 * Request(String $verb, String $url, RequestData data, HeaderData header)
		 */
		 
		public function HttpRequest()
		{
			$this->verb = "GET";
			$this->headers = new HeaderData;
			
			switch(func_num_args())
			{
				/*
				 * Request(String $url)
				 */
				
				case 1:
					
					$url = func_get_arg(0);
					
					if(is_string($url))
					{
						$this->url = $url;
					}
					else
					{
						throw new InvalidArgumentException("Overload (".gettype($url).") not defined");
					}
					
					break;
				
				/*
				 * Request(String $verb, String $url)
		 		 * Request(String $url, RequestData data)
				 */
				
				case 2:
					
					$verb_url = func_get_arg(0);
					$url_data = func_get_arg(1);
					
					if(HttpRequest::IsHTTPVerb($verb_url) && is_string($url_data))
					{
						$this->verb = $verb_url;
						$this->url = $url_data;
					}
					else if(is_string($verb_url) && $url_data instanceof RequestData)
					{
						$this->url = $verb_url;
						$this->data = $url_data;
					}
					else
					{
						throw new InvalidArgumentException("Overload (".gettype($verb_url).", ".gettype($url_data).") not defined");
					}
					
					break;
				
				/*
				 * Request(String $verb, String $url, RequestData $data)
				 * Request(String $verb, String $url, HeaderData $data)
		 	     * Request(String $url, RequestData data, HeaderData header)
				 */
				 
				case 3:
					
					$verb_url = func_get_arg(0);
					$url_data = func_get_arg(1);
					$data_header = func_get_arg(2);
					
					
					if(HttpRequest::IsHTTPVerb($verb_url) && is_string($url_data) && ($data_header instanceof RequestData || $data_header instanceof HeaderData))
					{
						$this->verb = $verb_url;
						$this->url = $url_data;
						
						if($data_header instanceof RequestData)
						{
							$this->data = $data_header;
						}
						else if($data_header instanceof HeaderData)
						{
							$this->header = $data_header;
						}
					}
					else if(is_string($verb_url) && $url_data instanceof RequestData && $data_header instanceof HeaderData)
					{
						$this->url = $verb_url;
						$this->data = $url_data;
						$this->header = $data_header;
					}
					else
					{
						throw new InvalidArgumentException("Overload (".gettype($verb_url).", ".gettype($url_data).", ".gettype($data_header).") not defined");
					}
					
					break;
					
				/*
				 * Request(String $verb, String $url, RequestData data, HeaderData header)
				 */
				
				case 4:
					
					$verb = func_get_arg(0);
					$url = func_get_arg(1);
					$data = func_get_arg(2);
					$headers = func_get_arg(3);
					
					if(!HttpRequest::IsHTTPVerb($verb) || !is_string($url) || !($data instanceof RequestData) || !($headers instanceof HeaderData))
					{
						throw new InvalidArgumentException("Overload (".gettype($verb).", ".gettype($url).", ".gettype($data).", ".gettype($headers).") not defined");
					}
					
					$this->verb = $verb;
					$this->url = $url;
					$this->data = $data;
					$this->headers = $headers;
					
					break;
					
				default: throw new InvalidArgumentException('Too many arguments passed');
			}
		}
		
		  //
		 // METHODS
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "Verb":
					return $this->getVerb();
					
				case "URL":
					return $this->getURL();
					
				case "Data":
					return $this->getData();
					
				case "Header":
					return $this->getHeader();
					
				case "ResponseBody":
					return $this->GetResponseBody();
					
				case "ResponseHeaders":
					return $this->GetResponseHeaders();
					
				case "HTTPStatusCode":
					return $this->GetHTTPStatusCode();
					
				default:
					throw new InvalidArgumentException("Field '".$field."' not defined");
			}
		}
		
		public function __set($field, $value)
		{
			switch($field)
			{
				case "Verb":
					return $this->setVerb($value);
					
				case "URL":
					return $this->setURL($value);
					
				case "Data":
					throw new InvalidArgumentException("Field 'Data' cannot directly be overwritten");
					
				case "Header":
					throw new InvalidArgumentException("Field 'Header' cannot directly be overwritten");
					
				default:
					throw new InvalidArgumentException("Field '".$field."' not defined");
			}
		}
		
		public function ReceiveCertificate()
		{
			/*$url = Configuration::GetConfigurationParameter("CertURL");
			$context = stream_context_create(
				array("ssl" => array(
					"capture_peer_cert"=>true,
					"allow_self_signed" => true)));
			$errstr = null;
			$socket = stream_socket_client($url, $errno,$errstr, 30, STREAM_CLIENT_CONNECT, $context);
			$params = stream_context_get_params($socket);
			var_dump($params["options"]["ssl"]["peer_certificate"]);
			
			$params["options"]["ssl"]["peer_certificate"]);
			
			echo "----";*/
		}
		
		public function SendRequest()
		{
			$url = $this->url;
			
			if(!is_null($this->data))
			{
				$data = http_build_query($this->data->Parameters);
				
				if(strlen($data))
				{
					if($this->verb <> 'GET')
					{
						stream_context_set_option($streamContext, 'ssl', "content", $data);
					}
					else
					{
						$url .= '?'.$data;
					}
				}
			}
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_VERBOSE, true);
			curl_setopt($curl, CURLOPT_HEADER, true);
			if(!is_null($this->headers))
			{
				$headers = array();
				
				foreach($this->headers->Headers as $name => $value)
				{
					$headers[] = $name.": ".$value;
				}
				
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			}
			
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_ENCODING,"");
			curl_setopt($curl, CURLOPT_CAINFO, getcwd()."/Core/Configuration/cert.pem");
			
			$response = curl_exec($curl);
			$error = curl_error($curl);
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			
			curl_close($curl);
			
			$header = substr($response,0,$header_size);
			$body = substr($response, $header_size+3);
			
			$this->responsebody = $body;
			
			$this->responseheaders = self::cURLResponseHeadersToArray($header);
			
			return true;
			/*$this->ReceiveCertificate();*/
			
			/*$streamContext = stream_context_create();
			
			stream_context_set_option($streamContext, 'ssl', "method", $this->verb);
			#stream_context_set_option($streamContext, 'https', "user_agent", $this->Headers["UserAgent"]);
			stream_context_set_option($streamContext, 'ssl', "follow_location", true);
			stream_context_set_option($streamContext, 'ssl', "max_redirects", 10);
			stream_context_set_option($streamContext, 'ssl', "timeout", 15.0);
			stream_context_set_option($streamContext, 'ssl', "ignore_errors", false);
			stream_context_set_option($streamContext, 'ssl', "verify_peer", true);
			stream_context_set_option($streamContext, 'ssl', "cafile", "Core/Configuration/cert.pem");
			stream_context_set_option($streamContext, 'ssl', "CN_MATCH", "selfhost.bz");
			stream_context_set_option($streamContext, 'ssl', "allow_self_signed", true);
			stream_context_set_option($streamContext, 'ssl', "ciphers", "HIGH:!SSLv2:!SSLv3");
			#stream_context_set_option($streamContext, 'ssl', "capture_peer_cert", true);
			
			$url = $this->url;
			
			if(!is_null($this->data))
			{
				$data = http_build_query($this->data->Parameters);
				
				if(strlen($data))
				{
					if($this->verb <> 'GET')
					{
						stream_context_set_option($streamContext, 'ssl', "content", $data);
					}
					else
					{
						$url .= '?'.$data;
					}
				}
			}
			
			if(!is_null($this->headers))
			{
				$header = "";
				
				$headers = $this->headers->Headers;
				
				foreach($headers as $name => $value)
				{
					$header .= $name.": ".$value."\r\n";
				}
				
				stream_context_set_option($streamContext, 'ssl', "header", $header);
			}
			var_dump($streamContext);
			$stream = @fopen($url, 'r', false, $streamContext);
			var_dump($url);
			var_dump($stream);
			if($stream)
			{
				$result = stream_get_contents($stream);
				$this->responsebody = $result;
			}
			
			if(isset($http_response_header))
			{
				$this->responseheaders = self::HTTPResponseHeadersToArray($http_response_header);
			}
			
			return true;*/
		}

		public function SetAuthorization($authorization)
		{
			if(is_null($this->headers))
				$this->headers = new HeaderData;
			
			return $this->Header->AddHeader("Authorization",$authorization);
		}

		public function SetAccept($accept)
		{
			if(is_null($this->headers))
				$this->headers = new HeaderData;
			
			return $this->Header->AddHeader("Accept",$accept);
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Verb
		
		private function getVerb()
		{
			return $this->verb;
		}
		
		private function setVerb($verb)
		{
			$this->verb = $verb;
		}
		
		# URL
		
		private function getURL()
		{
			return $this->url;
		}
		
		private function setURL($url)
		{
			$this->url = $url;
		}
		
		# Data
		
		private function getData()
		{
			return $this->data;
		}
		
		# Header
		
		private function getHeader()
		{
			return $this->headers;
		}

		private function GetResponseBody()
		{
			return $this->responsebody;
		}
		
		private function GetResponseHeaders()
		{
			return $this->responseheaders;
		}
		
		private function GetHTTPStatusCode()
		{
			if(is_null($this->responseheaders) || !array_key_exists("HTTPStatusCode",$this->responseheaders))
				return null;
				
			return self::HTTPStatusCode($this->responseheaders["HTTPStatusCode"]);
		}
		
		  //
		 // FUNCTIONS
		//
		
		public function IsHTTPVerb($verb)
		{
			if(in_array($verb, array(HttpRequest::HTTP_VERB_GET, HttpRequest::HTTP_VERB_POST, HttpRequest::HTTP_VERB_PUT, HttpRequest::HTTP_VERB_DELETE)))
				return true;
			
			return false;
		}
		
		private static function HTTPResponseHeadersToArray($headers)
		{
			$result = array("HTTPStatusCode" => $headers[0]);

			$items = count($headers);
			for($h = 1; $h < $items; $h++)
			{
			  $header = explode(":",$headers[$h]);

			  $field = trim($header[0]);
			  $value = trim($header[1]);

			  $result[$field] = $value;
			}

			return $result;
		}
		
		private static function cURLResponseHeadersToArray($headers)
		{
			$result = array();
			
			$lines = explode("\n",$headers);
			
			$result["HTTPStatusCode"] = trim($lines[0]);
			
			for($h = 1; $h < count($lines); $h++)
			{
			  $header = explode(":",$lines[$h]);

			  $field = trim($header[0]);
			  $value = trim($header[1]);

			  $result[$field] = $value;
			}

			return $result;
		}

		private static function HTTPStatusCode($string)
		{
			return (int) substr($string, 9,3);
		}
		
		  //
		 // CONSTANTS
		//
		
		const HTTP_VERB_GET = "GET";
		const HTTP_VERB_POST = "POST";
		const HTTP_VERB_PUT = "PUT";
		const HTTP_VERB_DELETE = "DELETE";
	}

	class RequestData
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<String,String>)*/ $data;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function RequestData()
		{
			$this->data = array();
		}
		
		  //
		 // METHODS
		//
		
		public function AddParameter($name, $value)
		{
			$this->data[$name] = $value;
		}
		
		public function __get($field)
		{
			switch($field)
			{
				case "Parameters":
					return $this->data;
					
				default:
					throw new InvalidArgumentException("Field '".$field."' not defined");
			}
		}
		
		public function __set($field, $value)
		{
			switch($field)
			{
				case "Parameters":
					throw new InvalidArgumentException("Field 'Parameters' is read-only");
				
				default:
					throw new InvalidArgumentException("Field '".$field."' not defined");
			}
		}
	}
	
	class HeaderData
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<String,String>)*/ $data;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function HeaderData()
		{
			$this->data = array();
		}
		
		  //
		 // METHODS
		//
		
		public function AddHeader($name, $value)
		{
			$this->data[$name] = $value;
		}
		
		public function __get($field)
		{
			switch($field)
			{
				case "Headers":
					return $this->data;
					
				default:
					throw new InvalidArgumentException("Field '".$field."' not defined");
			}
		}
		
		public function __set($field, $value)
		{
			switch($field)
			{
				case "Headers":
					throw new InvalidArgumentException("Field 'Headers' is read-only");
				
				default:
					throw new InvalidArgumentException("Field '".$field."' not defined");
			}
		}
	}
?>