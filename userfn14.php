<?php

// Global user functions
// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}

function getStoreName($userid)
{
$store_name = ew_ExecuteScalar("SELECT S.store_name FROM stores S INNER JOIN user U ON
S.sn=U.store_location WHERE U.sn=".$userid);
return $store_name;
}

function CurrentStoreID()
{
$store_name = ew_ExecuteScalar("SELECT S.sn FROM stores S INNER JOIN user U ON
S.sn=U.store_location WHERE U.sn=".CurrentUserID());
return $store_name;
}

function getProductCategory($product_id)
{
$category = ew_ExecuteScalar("SELECT PC.category FROM product_categories PC INNER JOIN products P ON
PC.sn=P.product_category WHERE P.sn=".$product_id);
return $category;
}

function getProductQtySold($cat_id)
{
$category = ew_ExecuteScalar("SELECT SUM(T.quantity) AS qty FROM transactions T INNER JOIN product_categories PC ON
PC.sn=P.product_category WHERE P.sn=".$product_id);
return $category;
}

function getUserDeviceToken($userid)
{

// Get a field value
// NOTE: Modify your SQL here, replace the table name, field name and the condition

$token = ew_ExecuteScalar("SELECT device_token FROM user WHERE sn=".$userid);
return $token;
}

function getALLUsers()
{
}

function debugFile($txt)
{
	$fp = fopen("debug.txt", 'w');
	fwrite($fp, $txt);
	fclose($fp);
}

function sendNotificationToUser($title, $message, $device_token)
{
$url = 'https://fcm.googleapis.com/fcm/send';
$fields = array (
		'to' => $device_token,
		'notification' => array (
				"body" => $title,
				"title" => "New Merchandizer Message",
				"click_action" => "MessageActivity"
		),
		'data' => array(
	'message' => $message
   )
);
$fields = json_encode ( $fields );
$headers = array (
		'Authorization: key=' . "AAAAJcMooPQ:APA91bERjG4Bkl0pm0hcTX2QKsh6NmlhJlcuUk8-gQpnWAAxjm5F0tG2EHE6WPQEFVz1CZGdY3SJw4NfB8IKJKAP29VrydBk1SXU_VkvYIaiDOhPUN2c8gMl9cjk35pq9ZUkc4ze0EAV",
		'Content-Type: application/json'
);
$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_POST, true );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
$result = curl_exec ( $ch );
curl_close ( $ch );
}

function sendNotificationToAllUsers($title, $message)
{
$url = 'https://fcm.googleapis.com/fcm/send';
$fields = array (
		'to' => '/topics/merchandizers',
		'notification' => array (
				"body" => $title,
				"title" => "New Merchandizer Message",
				"click_action" => "MessageActivity"
		),
		'data' => array(
	'message' => $message
   )
);
$fields = json_encode ( $fields );
$headers = array (
		'Authorization: key=' . "AAAAJcMooPQ:APA91bERjG4Bkl0pm0hcTX2QKsh6NmlhJlcuUk8-gQpnWAAxjm5F0tG2EHE6WPQEFVz1CZGdY3SJw4NfB8IKJKAP29VrydBk1SXU_VkvYIaiDOhPUN2c8gMl9cjk35pq9ZUkc4ze0EAV",
		'Content-Type: application/json'
);
$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_POST, true );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
$result = curl_exec ( $ch );
curl_close ( $ch );
}
?>
