<?php

//Definitions. Do not put trailing / for dir.
define('CACHEDIR', 'cache');

/**
* Class to cache API calls in the relevant format for a specified time.
* Author: 	Aaron Parker
* URL:		http://www.parkeyparker.co.uk/
**/
class Caching {
	
	var $APIURL = "";
	var $APIFunc = "";
	var $returnType = "";
	var $cacheTime = 0;
	var $cacheFile = "";
	
	function __construct($APIFunc, $parameters, $returnType, $cacheTime) {
		if (strlen($APIFunc) < 1 or $cacheTime <= 0) {
			//Required data is missing!
			echo "You have omitted some important data from the caching function, please rectify and try again";
			return false;
		} else {
			//All required data is present
			$this->APIFunc = $APIFunc;
			$this->cacheTime = $cacheTime;
			
			//Remove any periods that precede the return type
			$returnType = preg_replace("/^\./", "", $returnType);
			
			//Check that the return type is relevant to the function requested
			if ($APIFunc == "livestats_widget" or $APIFunc == "trends_widget") {
				if ($returnType != "jpg" and $returnType != "png") {
					//Need to be images,"\n" default to png
					$returnType = "png";
				}
			} else {
				if ($returnType != "json" and $returnType != "jsonp" and $returnType != "serialized" and $returnType != "xml") {
					//Invalid return type, set default
					$returnType = "serialized";
				}
			}
			$this->returnType = $returnType;
			
			$strParameters = "";
			foreach ($parameters as $param => $value) {
				$strParameters .= $param . '=' . $value . '&';
			}
			$strParameters = preg_replace("/&$/", "", $strParameters);
			
			//Set the cache file
			$cacheFile = CACHEDIR . $APIFunc . '.' . $returnType;
			$this->cacheFile = $cacheFile;
			
			//Create the URL
			$URL = "//api.gosquared.com/" . $APIFunc . "." . $returnType . "?" . $strParameters;
			$this->APIURL = $URL;
		}
	}
	
	function checkCacheValid() {
		//Get the times
		$fileModTime = filemtime($this->cacheFile);
		if ($fileModTime) {
			$cacheRenewalTime = $fileModTime + $this->cacheTime;
			if ((time() - $cacheRenewalTime) > 0) {
				//cache is still valid
				return true;
			}
		}
		return false;
	}
	
	function updateCache() {
		$data = null;
		switch ($this->returnType) {
			case 'jpg':
			case 'png':
				// Save the image in the cache location
				$curl = curl_init(); 
				$timeout = 0; 
				curl_setopt ($curl, CURLOPT_URL, $this->APIURL); 
				curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, $timeout); 
				// Getting binary data 
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1); 
				//Get image via cURL
				$image = curl_exec($curl); 
				curl_close($curl);
				if ($image) {
					//Save image to cache file
					$cache = fopen($this->cacheFile, 'w');
					fwrite($cache, $image);
				}
				break;
			
			case 'json':
			case 'jsonp':
				
				break;
			
			case 'xml':
				
				break;
			
			case 'serialized':
				$data = file_get_contents($this->APIURL);
				if ($data) {
					$cache = fopen($this->cacheFile, 'w');
					//Perhaps check if the first part is "Fatal Error"??
					fwrite($cache, $data);
				}
				break;
			
			default:
				# code...
				break;
		}
		if ($cache) {
			//File open, close it!
			fclose($cache);
		}
	}
}


?>