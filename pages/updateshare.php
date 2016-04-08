<?php
global $paginationHandler;
$httpHandler = new HttpHandler("http://restmock.cloudapp.net/RestService.svc");
$validShare = false;

if(isset($_GET['id']) && !empty($_GET['id'])) {
    try {
        $shareHandler = new ShareHandler($httpHandler);
        $shareHandler->search_id($_GET['id']);
        
        $validShare = true;
        if(isset($_POST['submit']) && !empty($_POST['submit'])) {
            try {

                if($shareHandler->update_share(isset($_POST['share_id']) ? $_POST['share_id'] : null, isset($_POST['fullname']) ? $_POST['fullname'] : null, isset($_POST['shortname']) ? $_POST['shortname'] : null, isset($_POST['rate']) ? $_POST['rate'] : null)) {
                    ErrorHandler::DisplaySuccess("You have successfully updated the share.");
                    $shareHandler->search_id($_GET['id']);
                } else {
                    ErrorHandler::DisplayWarning($shareHandler->error);
                }
            } catch(Exception $ex) {
                ErrorHandler::DisplayWarning($ex->getMessage());
            }
        }
        
        if($validShare) {
            echo "<table valign='top' style='width:100%;'>
                <tbody>
                <tr>
                    <td>
                        <form name='submit' method='post' action=''>
                            <input type='hidden' name='share_id' value='".$shareHandler->share->Id."'>
                            <div class='label' style='font-weight:bold;'>Full share name:</div>
                            <input type='text' name='fullname' value='".$shareHandler->share->FullShareName."' class='form-control' size='25' placeholder='Enter full share name' style='width:30% !important'>
                            <div class='label' style='font-weight:bold;margin-top:10px;'>Short share name:</div>
                            <input type='text' name='shortname' value='".$shareHandler->share->ShortShareName."' class='form-control' size='25' placeholder='Enter short share name' style='width:30% !important'>
                            <div class='label' style='font-weight:bold;margin-top:10px;'>Rate:</div>
                            <input type='text' name='rate' value='".$shareHandler->share->CurrentRate."' class='form-control' size='25' placeholder='00.00' style='width:10% !important'>
                            <input type='submit' name='submit' value='Update share' class=' btn btn-default' style='width:30%;margin-top:10px;height: 34px;font-size:14px !important;'>
                        </form>
                    </td>
                </tr>
                </tbody>
                </table>";
        }
    }catch (Exception $ex)  {
        ErrorHandler::DisplayWarning($ex->getMessage());
    }
    
} else {
    ErrorHandler::DisplayWarning("Invalid share id.");
}