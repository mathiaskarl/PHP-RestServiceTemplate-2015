<?php
class ErrorHandler
{
    public static $Error;

    public static function ReturnError($errorCode = null)
    {
        ErrorHandler::$Error = ErrorHandler::SetErrorMessage($errorCode);
        return ErrorHandler::$Error;
    }

    private static function SetErrorMessage($errorCode = null)
    {
        $errorMessage;
        switch ($errorCode)
        {
            case "INVALID_FORM":
                $errorMessage = "You must use our webform.";
                break;
            
            case "EMPTY_FORM":
                $errorMessage = "You must fill out all fields";
                break;
            
            case "LOGIN_INVALID_DATA":
                $errorMessage = "You must enter correct data.";
                break;
            
            case "LOGIN_ALREADY_EXISTS":
                $errorMessage = "You are already logged in.";
                break;
            
            case "LOGIN_INVALID_INFO":
                $errorMessage = "Your email/username and password is not correct.";
                break;
            
            case "CREATE_USER_MISMATCH_PASSWORD":
                $errorMessage = "The passwords you entered does not match.";
                break;
            
            case "CREATE_INVALID_USERNAME":
                $errorMessage = "The username may only contain letters and numbers";
                break;
            
            case "CREATE_INVALID_EMAIL":
                $errorMessage = "The entered email is invalid.";
                break;
            
            case "ID_MUST_BE_NUMBER":
                $errorMessage = "Entered ID must be a number.";
                break;
            
            case "CREATE_USERNAME_TAKEN":
                $errorMessage = "Username already exists.";
                break;
            
            case "EMPTY_FORM":
                $errorMessage = "You must fill out the required fields.";
                break;
            
            case "PAGEFILE_DOESNT_EXIST":
                $errorMessage = "The file associated with this page doesn't exist.";
                break;
            
            case "PAGE_DOESNT_EXIST":
                $errorMessage = "This page doesn't exist.";
                break;

            default:
                $errorCode = "UNKOWN_ERROR";
                $errorMessage =  "Unknown error.";
                break;
        }
        return ErrorHandler::CreateError($errorCode, $errorMessage);
    }

    public static function CreateError($errorCode, $errorMessage)
    {
        $error = new Error($errorCode, $errorMessage);
        return $error;
    }
    
    public static function DisplayError($errorMessage = null, $isHidden = false) {
        echo "<div id='alert_container' class='alert alert-danger alert-dismissible "; 
        if($isHidden == true) {
            echo "hidden";
        }
        echo "' role='alert'>
             <a href='#' class='close alert_close' style='float:right;' aria-label='close' title='close'>&times;</a>
             <div class='alert_message' style='float:left;'>". $errorMessage ."</div>
             <div style='clear:both;'></div>
             </div>";
    }
    
    public static function DisplaySuccess($message = null, $isHidden = false) {
        echo "<div id='success_container' class='alert alert-success alert-dismissible "; 
        if($isHidden == true) {
            echo "hidden";
        }
        echo "' role='alert'>
             <a href='#' class='close alert_close' style='float:right;' aria-label='close' title='close'>&times;</a>
             <div class='alert_message' style='float:left;'>". $message ."</div>
             <div style='clear:both;'></div>
             </div>";
    }
    
    public static function DisplayWarning($message = null, $isHidden = false) {
        echo "<div id='warning_container' class='alert alert-warning alert-dismissible "; 
        if($isHidden == true) {
            echo "hidden";
        }
        echo "' role='alert'>
             <div class='warning_message' style='float:left;'>". $message ."</div>
             <div style='clear:both;'></div>
             </div>";
    }
}
