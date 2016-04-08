<?php
global $paginationHandler;
$httpHandler = new HttpHandler("http://localhost:22882/RestService.svc");

try {
    //$book = array("Author" => "Ingen", "Title" => "Ingen");
    //$httpHandler->POST("AddBook/", $book);
    
    $var = json_decode($httpHandler->GET("GetBooks/"));
    $paginationHandler->Initialize(3, $var);

    foreach($paginationHandler->sliced_array as $value){
        echo $value->Title ." <br/>";
    }
} catch(Exception $ex) {
    ErrorHandler::DisplayWarning($ex->getMessage());
}


/*
// XML
try {
    $httpHandler->SetDefaultHeaders(false);
    /*
    $xmlToAdd = "<?xml version='1.0' encoding='UTF-8'?><Book xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema'><Title>Vanilje</Title><Author>Rasmus</Author></Book>";
    $httpHandler->POST("AddBookAsXml/", $xmlToAdd);
    
    
    $xmlRaw = $httpHandler->GET("GetBooksAsXml/");
    
    $xml = simplexml_load_string($xmlRaw);
    $paginationHandler->Initialize(3, reset($xml));

    foreach($paginationHandler->sliced_array as $value){
        echo $value->Title ." <br/>";
    }
} catch(Exception $ex) {
    ErrorHandler::DisplayWarning($ex->getMessage());
}
*/
?>

<nav>
    <ul class="pagination">
        <?php $paginationHandler->BuildPagination(); ?>
    </ul>
</nav>