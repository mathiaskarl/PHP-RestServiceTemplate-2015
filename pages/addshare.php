<?php
global $paginationHandler;
$httpHandler = new HttpHandler("http://restmock.cloudapp.net/RestService.svc");

if(isset($_POST['submit']) && !empty($_POST['submit'])) {
    try {
        $shareHandler = new ShareHandler($httpHandler);
        if($shareHandler->add_share(isset($_POST['fullname']) ? $_POST['fullname'] : null, isset($_POST['shortname']) ? $_POST['shortname'] : null, isset($_POST['rate']) ? $_POST['rate'] : null)) {
            ErrorHandler::DisplaySuccess("You have successfully added a share.");
        } else {
            ErrorHandler::DisplayWarning($shareHandler->error);
        }
    } catch(Exception $ex) {
        ErrorHandler::DisplayWarning($ex->getMessage());
    }
}
?>
<table valign='top' style='width:100%;'>
<tbody>
<tr>
    <td>
        <form name='submit' method='post' action=''>
            <div class='label' style='font-weight:bold;'>Full share name:</div>
            <input type='text' name='fullname' value='' class='form-control' size='25' placeholder='Enter full share name' style='width:30% !important'>
            <div class='label' style='font-weight:bold;margin-top:10px;'>Short share name:</div>
            <input type='text' name='shortname' value='' class='form-control' size='25' placeholder='Enter short share name' style='width:30% !important'>
            <div class='label' style='font-weight:bold;margin-top:10px;'>Rate:</div>
            <input type='text' name='rate' value='' class='form-control' size='25' placeholder='00.00' style='width:10% !important'>
            <input type='submit' name='submit' value='Add share' class=' btn btn-default' style='width:30%;margin-top:10px;height: 34px;font-size:14px !important;'>
        </form>
    </td>
</tr>
</tbody>
</table>