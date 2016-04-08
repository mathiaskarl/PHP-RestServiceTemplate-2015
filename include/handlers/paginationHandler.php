<?php

class PaginationHandler {
    private $recordsPerPage;
    private $currentPage;
    private $totalRecords;
    private $totalPages;
    
    private $array;
    public $sliced_array;
    
    public function __construct() {
    }
    
    public function Initialize($recordsPerPage, $array = array()) {
        if(count($array) < 1) {
            return false;
        }
        $this->array = $array;
        $this->recordsPerPage = $recordsPerPage;
        $this->totalRecords = count($array);
        $this->totalPages = ceil($this->totalRecords/$this->recordsPerPage);
        
        $this->SetCurrentPage();
        $this->SetSlicedArray();
        return true;
    }
    
    private function SetCurrentPage() {
        if(isset($_GET['s']) && !empty($_GET['s']) && is_numeric($_GET['s'])) {
            if($_GET['s'] > $this->totalPages || $_GET['s'] < 0) {
                $this->currentPage = 1;
            } else {
                $this->currentPage = $_GET['s'];
            }
        } else {
            $this->currentPage = 1;
        }
    }
    
    private function SetSlicedArray() {
        if($this->totalRecords <= $this->recordsPerPage) {
            $this->sliced_array = $this->array;
        } else {
            $this->sliced_array = array_slice($this->array,(($this->currentPage-1) < 1 ? 0 : ($this->currentPage-1) * $this->recordsPerPage), $this->recordsPerPage);
        }
    }
    
    private function ReplaceUrl($page) {
        if (preg_match('/&s=/', $_SERVER['REQUEST_URI'])) {
            return preg_replace("#&s=.*#", "&s=".$page, $_SERVER['REQUEST_URI']);
        } else {
            return $_SERVER['REQUEST_URI']."&s=".$page;
        }
    }
    
    public function BuildPagination() {
        if($this->currentPage > 1) {
            echo "<li><a href='".$this->ReplaceUrl($this->currentPage-1)."' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
        } else {
            echo "<li class='disabled'><a href='#' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
        }
        
        $pagesBefore = "";
        for($i = $this->currentPage-1; $i > $this->currentPage-3; $i--) {
            if($i > 0 && $this->currentPage > 1) {
                $pagesBefore = "<li><a href='".$this->ReplaceUrl($i)."'>".$i."</a></li>" . $pagesBefore;
            }
        }
        echo $pagesBefore;
        
        echo "<li class='active'><a href='#'>";
        $currentPage = ($this->currentPage < 1 ? 1 : $this->currentPage);
        echo $currentPage;
        echo "<span class='sr-only'>(current)</span></a></li>";
        
        for($i = $this->currentPage+1; $i < $this->currentPage+3; $i++) {
            if($i <= $this->totalPages && $this->currentPage < $this->totalPages && $i != 1) {
                echo "<li><a href='".$this->ReplaceUrl($i)."'>".$i."</a></li>";
            }
        }
        
        if($this->currentPage < $this->totalPages) {
            echo "<li><a href='".$this->ReplaceUrl($this->currentPage < 1 ? 2 : $this->currentPage+1)."' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
        } else {
            echo "<li class='disabled'><a href='#' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
        }
        
    }
}

