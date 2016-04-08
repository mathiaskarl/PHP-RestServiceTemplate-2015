<?php
global $paginationHandler;
$httpHandler = new HttpHandler("http://restmock.cloudapp.net/RestService.svc");

if(isset($_POST['update']) && !empty($_POST['update'])) {
    header("Location: http://localhost:8080/RestProject/index.php?p=updateshare&id=". $_POST['id']);
}

if(isset($_POST['submit']) && !empty($_POST['submit'])) {
    try {
        $shareHandler = new ShareHandler($httpHandler);
        if($shareHandler->delete_share(isset($_POST['id']) ? $_POST['id'] : null)) {
            ErrorHandler::DisplaySuccess("You have successfully deleted the share.");
        } else {
            ErrorHandler::DisplayWarning($shareHandler->error);
        }
    } catch(Exception $ex) {
        ErrorHandler::DisplayWarning($ex->getMessage());
    }
}

try {
    $var = json_decode($httpHandler->GET("GetSharesDb/"));
    $paginationHandler->Initialize(8, $var);

    echo "<table class='share_table'>
        <tr>
            <td><b>ID:</b></td>
            <td><b>Full name:</b></td>
            <td><b>Short name:</b></td>
            <td><b>Currency:</b></td>
            <td></td>
            <td></td>
        </tr>";
    foreach($paginationHandler->sliced_array as $value){
        echo "<tr>
                <td>".$value->Id."</td>
                <td>".$value->FullShareName."</td>
                <td>".$value->ShortShareName."</td>
                <td>".$value->CurrentRate."</td>
                <td>
                    <form name='update' method='post' action=''>
                    <input type='hidden' name='id' value='".$value->Id."'>
                    <input type='submit' name='update' value='Update share' class=' btn btn-default' style='width:auto;margin-top:0px;padding-top:0px !important;height: 20px;font-size:12px !important;'>
                    </form>
                </td>
                <td>
                    <form name='submit' method='post' action=''>
                        <input type='hidden' name='id' value='".$value->Id."'>
                        <input type='submit' name='submit' value='Delete share' class=' btn btn-default' style='width:auto;margin-top:0px;padding-top:0px !important;height: 20px;font-size:12px !important;'>
                    </form>
                </td>
            </tr>";
    }
    echo "</table>";
} catch(Exception $ex) {
    ErrorHandler::DisplayWarning($ex->getMessage());
}
?>

<nav>
    <ul class="pagination">
        <?php $paginationHandler->BuildPagination(); ?>
    </ul>
</nav>