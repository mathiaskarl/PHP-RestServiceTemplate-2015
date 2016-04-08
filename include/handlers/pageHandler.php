<?php
class PageHandler {
    private $pre_page;
    
    public $currentPage;
    public $pages;
    public $pageHierarchy;
    
    private function PreparePages() {
        $this->pre_page[0]           = new Page("front","Forside", null, true);
        $this->pre_page[1][0]           = new Page("shares","All shares", null, true);
        $this->pre_page[1][1]           = new Page("updateshare","Update share", null, true);
        $this->pre_page[2][0]        = new Page("specificshare","Specific share", null, true);
        $this->pre_page[2][1]        = new Page("searchshare","Search share", null, false);
        $this->pre_page[3]    = new Page("addshare","Add share", null, true);
        $this->pre_page[4]           = new Page("error","Error", null, false);
    }

    public function __construct() {
        $this->PreparePages();
        $this->pages = $this->GeneratePageArray($this->pre_page);
        $this->pageHierarchy = $this->GetPageHierarchy($this->pages, isset($_GET['p']) && !empty($_GET['p']) ? ($this->PageExists($this->pages, $_GET['p']) ? $_GET['p'] : "error") : "front");
    }

    private function GeneratePageArray($pageArray) {
        $pages = null;
        foreach($pageArray as $key => $value) {
            if(is_array($value)) {
                $childArray = $this->GeneratePageArray($value);
                $page = new Page(reset($childArray)->url, reset($childArray)->title, null, reset($childArray)->display);
                array_shift($childArray);
                if(count($childArray) > 0) {
                    $page->children = $childArray;
                }
                $pages[$page->url] = $page;
            } else {
                $pages[$value->url] = $value;
            }
        }
        return $pages;
    }

    private function GetPageHierarchy($pageArray, $page = null) {
        if($page == null) {
            return reset($pageArray);
        }
        if(is_array($pageArray) && isset($pageArray[$page])) {
            $pageArray[$page]->children = null;
            foreach($pageArray as $key => $value) {
                if($key != $page) {
                    unset($pageArray[$key]);
                }
            }
            return $pageArray;
        }

        if(is_array($pageArray)) {
            foreach($pageArray as $key => $value) {
                $child = $this->GetPageHierarchy($value->children, $page);
                if($child != null) {
                    $pageArray[$key]->children = $child;
                    foreach($pageArray as $outerkey => $outervalue) {
                        if($key != $outerkey) {
                            unset($pageArray[$outerkey]);
                        }
                    }
                return $pageArray;
                }
            }
        }
        return null;
    }
    
    private function PageExists($pageArray, $page = null) {
        if(empty($page) || $page == null) {
            return false;
        }
        
        if(!is_array($pageArray) || count($pageArray) < 1) {
            return false;
        }
        
        if(isset($pageArray[$page])) {
            return true;
        }
        
        foreach($pageArray as $key => $value) {
            if($value->children != null && is_array($value->children)) {
                if($this->PageExists($value->children, $page)) {
                    return true;
                } else {
                    continue;
                }
            }
        }
        return false;
    }

    private function IsInPageHierarchy($page) {
        if($this->pageHierarchy == null) {
            return false;
        }

        if($page == reset($this->pageHierarchy)->url) {
            return true;
        }

        return false;
    }
    
    private function GetBreadCrumbs($page = null) {
        $page = ($page == null ? reset($this->pageHierarchy) : reset($page));
        $breadCrumbs = $page->url . ":" . $page->title . ";";
        if($page->children != null && is_array($page->children)) {
            return $breadCrumbs . "" .$this->GetBreadCrumbs($page->children);
        }
        return $breadCrumbs;
    }
    
    public function BuildBreadCrumbs() {
        if($this->pageHierarchy == null) {
            return null;
        }
        
        $breadCrumbs = $this->GetBreadCrumbs();
        if($breadCrumbs != null) {
            $pages = explode(";", $breadCrumbs);
            echo "<ol class='breadcrumb' style='float:right;'>";
            for($i = 0; $i < count($pages)-1; $i++) {
                $explode = explode(":", $pages[$i]);
                if($i == count($pages)-2) {
                    echo "<li class='active'>".$explode[1]."</li>";
                } else {
                    echo "<li><a href='?p=".$explode[0]."'>".$explode[1]."</a></li>";
                }
            }
            echo "</ol>";
        }
    }

    public function BuildMenu() {
        foreach ($this->pages as $key => $value)
        {
            if($value->display) {
                echo "<li><a ";
                if($this->IsInPageHierarchy($value->url)) {
                    echo "class='current' ";
                }
                echo "href='?p=". $value->url ." '/>" . $value->title . "</a></li>";
            }
        }
    }
    
    public function SetPage() {
        if (isset($_GET['p']) && !empty($_GET['p'])) {
            if ($this->PageExists($this->pages, $_GET['p'])) {
                if(file_exists("pages/" . $_GET['p'] . ".php")) {
                    include("pages/" . $_GET["p"] . ".php");
                } else {
                    ErrorHandler::DisplayWarning(ErrorHandler::ReturnError("PAGEFILE_DOESNT_EXIST")->errorMessage);
                }
            } else {
                ErrorHandler::DisplayWarning(ErrorHandler::ReturnError("PAGE_DOESNT_EXIST")->errorMessage);
            }
        } else {
            include("pages/front.php");
        }
    }
}
?>
