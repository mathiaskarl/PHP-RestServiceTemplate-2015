<?php 
    require_once "include/includes.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once "templates/head_includes.php"; ?>
    </head>
    <body>
        <div id="top_bar">
            <h1 id="logo">
                <a href='?p=front'>RestProject</a>
            </h1>
        </div>
        <div id="menu_bar">
            <div id="menu">
                <ul id="menu_list">
                    <?php $pageHandler->BuildMenu(); ?>
                </ul> 
            </div>
        </div>
        
        <div id="middle_bar">
            <div id="breadcrumbs">
                <?php $pageHandler->BuildBreadCrumbs(); ?>
                <div style="clear:both;"></div>
            </div>
            
            <?php $pageHandler->SetPage(); ?>
        </div>
    </body>
</html>
