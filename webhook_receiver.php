<?php
    function sendMessage(){
        
/*
$arr = array(
    "user_id" => 2,
    "imei" => 862292050483049,
    "address" => "Plot 211, Lavandus Block Bahria Town, Lahore, Punjab, Pakistan",
    "plate_number" => "AQW-001",
    "object_owner" => "Muhammad Muzammil Hussain",
    "updated_at" => "2022-07-06 20:30:50",
    "message" => "Ignition OFF"
);
$_POST['json'] = json_encode($arr);
*/

        $json = filter_var_array(json_decode($_POST['json'], true), [
            'user_id'    => FILTER_VALIDATE_INT,
            'imei' => FILTER_SANITIZE_STRING,
            'address' => FILTER_SANITIZE_STRING,
            'plate_number' => FILTER_SANITIZE_STRING,
            'object_owner' => FILTER_SANITIZE_STRING,
            'message' => FILTER_SANITIZE_STRING,
            'updated_at' => FILTER_SANITIZE_STRING
        ]);

        $json['updated_at'] = Date('H:i d-m-Y', strtotime($json['updated_at']));
        $token = $json['imei'];

        $content = array(
            'en' => 'Dear ' . $json['object_owner'] . ', Your vehicle, ' . $json['plate_number'] . ', status has changed to ' . $json['message'] . ' at ' . $json['updated_at'] . ' near ' . $json['address'] . '. Thanks.'
        );
        
        
        $fields = array(
            'app_id' => "3a3051eb-f6e7-42de-9026-871862ec3599",
            'token' => 'MGU4MDdjNTEtMGRiNS00MTg5LWEwOWMtNzExZTRlYTRhOGYy',
            "filters" => array(array(
                "field" => "tag",
                "key" => "imei",
                "relation" => "=",
                "value" => $token
            )),
            'channel_for_external_user_ids' => 'push',
            'data' => array("foo" => "bar"),
            'contents' => $content,
            'small_icon' => "ic_launcher",
        );
        
        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic MGU4MDdjNTEtMGRiNS00MTg5LWEwOWMtNzExZTRlYTRhOGYy'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
    
    $response = sendMessage();
    $return["allresponses"] = $response;
    $return = json_encode( $return);
    
    print("\n\nJSON received:\n");
    print($return);
    print("\n");
?>