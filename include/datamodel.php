<?php
class Page {
    public $title;
    public $url;
    public $children = array();
    public $display = false;
    
    public function __construct($url, $title, $children = array(), $display = false) 
    {
        $this->title = $title;
        $this->url = $url;
        $this->children = $children;
        $this->display = $display;
    }
}

class Error {
  public $errorCode;
  public $errorMessage;
  
  public function __construct($errorCode, $errorMessage) {
      $this->errorCode = $errorCode;
      $this->errorMessage = $errorMessage;
  }
}
