<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "to":"'.$user->FCMToken.'",
    "data" :{
      "id_transaksi": "'.$transaksi->id.'",
      "status_transaksi": "'.$transaksi->status_transaksi.'"
    },
    "notification":{
        "title":"'.$notip->title.'",
        "body":"'.$notip->message.'"
    }
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: key=AAAAIQ0AbBo:APA91bG3235JdCH0Gj_F79x1URsW-8vd5JE8jQmziArYotFG717VYKT_8czEC6mwtvP1tfuu1cW-rcppEJgIQXkQhDgBYVJeOkFhvNRf1B7Q3r0i4wRIjpKHjr-nb10aQKORZX_c6vZP',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
