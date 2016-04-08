<?php
class HttpHandler {
    private $url;
    private $headers;
    private $defaultHeaders = self::JSON;
    private $params;
    
    CONST GET = "GET";
    CONST POST = "POST";
    CONST PUT = "PUT";
    CONST DELETE = "DELETE";
    
    CONST JSON = "application/json";
    CONST XML = "application/xml";
    
    public function __construct($url) {
        $this->url = substr($url, -1) == "/" ? $url : $url."/";
    }
    
    private function Execute($type, $url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url ."".ltrim($url, '/'));
        switch($type) {
            case self::GET:
                curl_setopt($curl, CURLOPT_HTTPHEADER, isset($this->headers) ? $this->headers :array('Content-Type: '. $this->defaultHeaders));
                break;
            case self::POST:
                curl_setopt($curl, CURLOPT_HTTPHEADER, isset($this->headers) ? $this->headers :array('Content-Type: '. $this->defaultHeaders, 'Content-Length: ' . strlen($this->params)));
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
                break;
            case self::PUT:
                curl_setopt($curl, CURLOPT_HTTPHEADER, isset($this->headers) ? $this->headers : array('Content-Type: '. $this->defaultHeaders,'Content-Length: ' . strlen($this->params)));
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::PUT);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
                break;
            case self::DELETE:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::DELETE);
                break;
            default:
                break;
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        switch($status) {
            case 200:
            case 201:
            case 202:
                break;
            default:
                if(empty($status))
                    throw new Exception("Could not contact service.");
                
                list($header, $body) = explode("\r\n\r\n", $result, 2);
                $header = explode("\r\n", $header);
                throw new Exception($header[0]);
        }
        $start = strpos($result, "\r\n\r\n") + 4;
        $body = substr($result, $start, strlen($result) - $start);
        return $body;
    }
    
    private function SetParams($params) {
        if($this->defaultHeaders == self::JSON) {
            if(!empty($params) && is_array($params) && count($params) > 0) {
                $this->params = json_encode($params);   
            }
        } else {
            $this->params = $params;
        }
    }
    
    public function SetDefaultHeaders($json = true) {
        if($json) {
            $this->defaultHeaders = self::JSON;
        } else {
            $this->defaultHeaders = self::XML;
        }
    }
    
    public function SetHeaders($headers) {
        $this->headers = $headers;
    }
    
    public function Get($url) {
        return $this->Execute(SELF::GET, $url);
    }
    
    public function Post($url, $params = array()) {
        $this->SetParams($params);
        return $this->Execute(SELF::POST, $url);
    }
    
    public function Put($url, $params = array()) {
        $this->SetParams($params);
        return $this->Execute(SELF::PUT, $url);
    }
    
    public function Delete($url) {
        return $this->Execute(SELF::DELETE, $url);
    }
}

