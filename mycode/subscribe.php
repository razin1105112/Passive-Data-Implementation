<?php
    $json = file_get_contents('php://input');
    $obj = json_decode($json);
   // print_r($obj);
   // print_r("this is a test");
    $BloodGroup = $obj->{'BloodGroup'};
    $Id=$obj->{'Id'};
    $Location=$obj->{'Location'};
/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);
//SID 	BloodGroup 	Id 	Location 
if ($BloodGroup && $Id && $Location) {

    // check if user is already existed with the same email
    if ($db->isSubscribeExisted($BloodGroup,$Id,$Location)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "Subscription already existed with " . $email;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storeSubscriber($BloodGroup,$Id,$Location);
        if ($user) {
            // user stored successfully
            $response["error"] = 'No error';           
            $response["SID"] = $user["SID"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (BloodGroup,Id or Location) is missing!";
    echo json_encode($response);
}
?>

