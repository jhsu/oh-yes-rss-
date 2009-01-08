<?php
/**
* Sends an API request to Facebook
*
* @param array $parameters Array of parameters to send
* @param string $method The API function to call
* @return array Returns array of data returned
*/
function do_facebook_request($parameters, $method)
{    
 if (empty($parameters) || empty($method))
 {
   return false;
 }
   
 // Build Facebook args
 // http://developers.f8.facebook.com/documentation.php?v=1.0&doc=auth
 $data['api_key'] = 'API KEY';
 $data['method'] = $method;
 $data['v'] = '1.0';
 
 // Loop through and set as array
 foreach ($parameters as $key => $value)
 {
   $data[$key] = $value;
 }  
   
 // Sort          
 ksort($data);
             
 $args = '';        
             
 foreach ($data as $key => $value)
 {
   $args .= $key.'='.$value;
 }
             
 $data['sig'] = md5($args.'secret');
             
 // Get a Facebook session
 $response = do_post_request('http://api.facebook.com/restserver.php', $data);
   
 // Handle XML [23]
 $xml = simplexml_load_string($response);
   
 return $xml;
}
 
/**
* Sends a POST request with necessary parameters
* Code based on http://netevil.org/node.php?nid=937
* We use HTTP here. See http://uk2.php.net/manual/en/wrappers.http.php
*
* @param string $url The URL to perform the POST on. Include http://
* @param array $data The data to POST in array format
* @param array $optional_headers Any HTTP headers. See http://www.php.net/manual/sv/function.header.php or http://www.faqs.org/rfcs/rfc2616
* @param string $method The method for the request. Defaults to POST
* @return string The response
*/
function do_post_request($url, $data, $optional_headers = NULL, $method = 'POST')
{
 // Just defining some parameters for the request here
 // See http://uk2.php.net/manual/en/wrappers.http.php#AEN268663 for additional context options
 $params = array('http' => array('method' => $method, 'content' => http_build_query($data)));
 
 if ($optional_headers !== NULL) // Add in any additional headers
 {
   $params['http']['header'] = $optional_headers;
 }
   
 // Makes it easier to add additional parameters such
 // as any optional HTTP headers as set above
 $context = stream_context_create($params);
   
 $fp = @fopen($url, 'rb', false, $context);
   
 if (!$fp)
 {
   return false;
 }
   
 $response = @stream_get_contents($fp);
   
 if ($response === false)  
 {
   fclose($fp);
   return false;
 }
   
 fclose($fp);
   
 return $response;
}
?>
