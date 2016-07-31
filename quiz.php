<?php
$access_token = "EAABZCZBOnpfiMBAITE7qgXFfkPfruG7ab9kM4l0ZCuUUeuWlz72gomIA0W3mer3bzVZBMduRHLxm6Cz0jD8EojQj7zPU97etWo3vDr3UMxpyiyURRElSNOTT8u66tptV8AliNrMWZANHq1OZBZBxgSgEjfwZAH8pgkDB5g8p2duNCgZDZD";
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);
$messaging = $json_object->entry{0}->messaging{0};
$url = 'https://docs.google.com/uc?authuser=0&id=0B3suUrhlktR0QXYyamItc1I3bjQ&export=download';

$id = "1089954324411137";
$post = <<< EOM
{
  "recipient":{
    "id":"{$id}"
  },
  "message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"generic",
        "elements":[
          {
            "title":"わたしのこと、覚えてる？",
            "image_url":"https://lh3.googleusercontent.com/-FhcA_-jzb7Nau1zxOanijNaiDyhV1BWdPJfTEhvya_D4aK9GclZBEXwBR6-Pph5tBn6xA=s190",
            "subtitle":"藻塩焼神事が行われる神社はどこでしょう？",
            "buttons":[
              {
                "type":"postback",
                "title":"鹽竈神社",
                "payload":"shioogama"
              },
              {
                "type":"postback",
                "title":"御釜神社",
                "payload":"okama"
              },
              {
                "type":"postback",
                "title":"祓ヶ崎稲荷神社",
                "payload":"inari"
              }              
            ]
          }
        ]
      }
    }
  }
}
EOM;
api_send_request($access_token, $post,$message);


function api_send_request($access_token, $post,$message) {
    error_log("api_get_message_content_request start".$message);
    $url = "https://graph.facebook.com/v2.6/me/messages?access_token={$access_token}";
    $headers = array(
            "Content-Type: application/json"
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($curl);
}
