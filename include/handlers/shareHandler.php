<?php
class ShareHandler 
{
    private $httpHandler;
    private $_searchId;
    private $_searchName;
    private $_access;
    
    private $_fullname;
    private $_shortname;
    private $_rate;
    private $_id;
    
    public $share;
    public $error;
    
    public function __construct($httpHandler) 
    {
        $this->httpHandler = $httpHandler;;
	$this->_errors	= array();
	$this->_access	= false;
    }
    
    public function delete_share($id = null) 
    {
        $this->_id = $id;
        $this->check_delete_share();
	return $this->_access;
    }
    
    public function update_share($id = null, $fullname = null, $shortname = null, $rate = null) 
    {
        $this->_id = $id;
        $this->_fullname = $fullname;
        $this->_shortname = $shortname;
        $this->_rate = str_replace(",", ".", $rate);
        $this->check_update_share();
	return $this->_access;
    }
    
    public function add_share($fullname = null, $shortname = null, $rate = null) 
    {
        $this->_fullname = $fullname;
        $this->_shortname = $shortname;
        $this->_rate = str_replace(",", ".", $rate);
        $this->check_add_share();
	return $this->_access;
    }
    
    public function search_id($id = null) 
    {
        $this->_searchId = $id;
        $this->check_search_id();
	return $this->_access;
    }
    
    public function search_name($name = null) 
    {
        $this->_searchName = strtolower($name);
        $this->check_search_name();
	return $this->_access;
    }
    
     private function check_delete_share()
    {
	try 
	{
	    if(empty($this->_id)) {
		throw new Exception ("Invalid share id.");
	    }
	    
	    if(!is_numeric($this->_id)) {
		throw new Exception ("Invalid share id.");
	    }
	    
	    if(!$this->verify_delete_share()) {
		throw new Exception ("REST_ERROR");
	    }
	    
	    $this->_access = true;
	}
	catch (Exception $ex) 
	{
            if($ex->getMessage() != "REST_ERROR") {
                $this->error = $ex->getMessage();
            }
	}
    }
    
    private function check_update_share()
    {
	try 
	{
            if(empty($this->_id)) {
                throw new Exception ("Invalid share id.");
            }
            
	    if(empty($this->_fullname) || empty($this->_shortname) || empty($this->_rate)) {
		throw new Exception ("You must fill all the fields.");
	    }
            
            if(!is_numeric($this->_rate)) {
		throw new Exception ("Entered rate must be a number");
	    }
	    
	    if(!$this->verify_update_share()) {
		throw new Exception ("REST_ERROR");
	    }
	    
	    $this->_access = true;
	}
	catch (Exception $ex) 
	{
            if($ex->getMessage() != "REST_ERROR") {
                $this->error = $ex->getMessage();
            }
	}
    }
    
    private function check_add_share()
    {
	try 
	{
	    if(empty($this->_fullname) || empty($this->_shortname) || empty($this->_rate)) {
		throw new Exception ("You must fill all the fields.");
	    }
            
            if(!is_numeric($this->_rate)) {
		throw new Exception ("Entered rate must be a number");
	    }
	    
	    if(!$this->verify_add_share()) {
		throw new Exception ("REST_ERROR");
	    }
	    
	    $this->_access = true;
	}
	catch (Exception $ex) 
	{
            if($ex->getMessage() != "REST_ERROR") {
                $this->error = $ex->getMessage();
            }
	}
    }
    
    private function check_search_name()
    {
	try 
	{
	    if(empty($this->_searchName)) {
		throw new Exception ("You must enter the name.");
	    }
	    
	    if(!$this->verify_request_name()) {
		throw new Exception ("REST_ERROR");
	    }
	    
	    $this->_access = true;
	}
	catch (Exception $ex) 
	{
            if($ex->getMessage() != "REST_ERROR") {
                $this->error = $ex->getMessage();
            }
	}
    }
    
    private function check_search_id()
    {
	try 
	{
	    if(empty($this->_searchId)) {
		throw new Exception ("You must enter the id.");
	    }
	    
	    if(!is_numeric($this->_searchId)) {
		throw new Exception ("Entered id must be a number");
	    }
	    
	    if(!$this->verify_request()) {
		throw new Exception ("REST_ERROR");
	    }
	    
	    $this->_access = true;
	}
	catch (Exception $ex) 
	{
            if($ex->getMessage() != "REST_ERROR") {
                $this->error = $ex->getMessage();
            }
	}
    }
    
    private function verify_delete_share()
    {
        try {
            $var = json_decode($this->httpHandler->DELETE("DeleteShareDb/". $this->_id));
            return true;
        } catch(Exception $ex) {
            $this->error = $ex->getMessage();
            return false;
        }
    }
    
    private function verify_update_share()
    {
        try {
            $share = array("FullShareName" => $this->_fullname, "ShortShareName" => $this->_shortname, "CurrentRate" => $this->_rate);
            $var = json_decode($this->httpHandler->PUT("UpdateShareDb/".$this->_id, $share));
            return true;
        } catch(Exception $ex) {
            $this->error = $ex->getMessage();
            return false;
        }
    }
    
    private function verify_add_share()
    {
        try {
            $share = array("FullShareName" => $this->_fullname, "ShortShareName" => $this->_shortname, "CurrentRate" => $this->_rate);
            $var = json_decode($this->httpHandler->POST("AddShareDb/", $share));
            $this->share = $var;
            return true;
        } catch(Exception $ex) {
            $this->error = $ex->getMessage();
            return false;
        }
    }
    
    private function verify_request()
    {
        try {
            $var = json_decode($this->httpHandler->GET("GetShareDb/".$this->_searchId));
            $this->share = $var;
            return true;
        } catch(Exception $ex) {
            $this->error = $ex->getMessage();
            return false;
        }
    }
    
    private function verify_request_name()
    {
        try {
            $var = json_decode($this->httpHandler->GET("GetSharesDb/"));
            foreach($var as $value) {
                if(strtolower($value->FullShareName) == $this->_searchName || strtolower($value->ShortShareName) == $this->_searchName) {
                    $this->share = $value;
                    return true;
                }
            }
            $this->error = "No shares match your search.";
            return false;
        } catch(Exception $ex) {
            $this->error = $ex->getMessage();
            return false;
        }
    }
}

?>
