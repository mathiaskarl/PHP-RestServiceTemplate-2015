<?php
global $paginationHandler;
$httpHandler = new HttpHandler("http://restmock.cloudapp.net/RestService.svc");

if(isset($_POST['submit']) && !empty($_POST['submit'])) {
    try {
        if($_GET['search'] == "name") {
            $shareHandler = new ShareHandler($httpHandler);
            if($shareHandler->search_name(isset($_POST['share_name']) ? $_POST['share_name'] : null)) {
                echo "<table class='share_table'>
                        <tr>
                            <td><b>ID:</b></td>
                            <td><b>Full name:</b></td>
                            <td><b>Short name:</b></td>
                            <td><b>Currency:</b></td>
                        </tr>
                        <tr>
                            <td>".$shareHandler->share->Id."</td>
                            <td>".$shareHandler->share->FullShareName."</td>
                            <td>".$shareHandler->share->ShortShareName."</td>
                            <td>".$shareHandler->share->CurrentRate."</td>
                        </tr>
                      </table>";
            } else {
                echo $shareHandler->error;
            }
        } else {
            $shareHandler = new ShareHandler($httpHandler);
            if($shareHandler->search_id(isset($_POST['share_id']) ? $_POST['share_id'] : null)) {
                echo "<table class='share_table'>
                        <tr>
                            <td><b>ID:</b></td>
                            <td><b>Full name:</b></td>
                            <td><b>Short name:</b></td>
                            <td><b>Currency:</b></td>
                        </tr>
                        <tr>
                            <td>".$shareHandler->share->Id."</td>
                            <td>".$shareHandler->share->FullShareName."</td>
                            <td>".$shareHandler->share->ShortShareName."</td>
                            <td>".$shareHandler->share->CurrentRate."</td>
                        </tr>
                      </table>";
            } else {
                ErrorHandler::DisplayWarning($shareHandler->error);
            }
        }
    } catch(Exception $ex) {
        ErrorHandler::DisplayWarning($ex->getMessage());
    }
} else {
    ErrorHandler::DisplayWarning("Invalid search input");
}
?>