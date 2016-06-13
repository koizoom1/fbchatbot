<?php
$access_token = "EAABZCZBOnpfiMBAITE7qgXFfkPfruG7ab9kM4l0ZCuUUeuWlz72gomIA0W3mer3bzVZBMduRHLxm6Cz0jD8EojQj7zPU97etWo3vDr3UMxpyiyURRElSNOTT8u66tptV8AliNrMWZANHq1OZBZBxgSgEjfwZAH8pgkDB5g8p2duNCgZDZD";
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);
$messaging = $json_object->entry{0}->messaging{0};

$id = $messaging->sender->id;
if(isset($messaging->postback)) {
	$payload =  $messaging->postback->payload;
    $post = <<< EOM
    {
        "recipient":{
            "id":"{$id}"
        },
        "message":{
            "text":"{$payload}を出しました！"
        }
    }
EOM;
    api_send_request($access_token, $post,$message);
}

if(isset($messaging->message)) {
    //$id = $messaging->sender->id;
    $message =  $messaging->message->text;
	error_log($message);
	if( $message == 'じゃんけん' ){
    $post = <<< EOM
    {
        "recipient":{
            "id":"{$id}"
        },
"message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"button",
        "text":"じゃんけんぽん！",
        "buttons":[
          {
            "type":"postback",
            "title":"グー",
            "payload":"rock"
          },
          {
            "type":"postback",
            "title":"チョキ",
            "payload":"scissors"
          },
		            {
            "type":"postback",
            "title":"パー",
            "payload":"paper"
          }
        ]
      }
    }
  }
    }
EOM;
	//api_get_user_profile_request($access_token, $from_user_id);
	api_send_request($access_token, $post,$message);
	} else if( $message == 'ゲーム' ){
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
                        "title":"Classic White T-Shirt",
                        "image_url":"https://lh5.googleusercontent.com/WMnm1C09vzUreZ1_GkMX62835sqXnNQUjA81Jb3tCS851PeEI5uM9l5___RE9R6u1kvTDQ",
                        "subtitle":"Soft white cotton t-shirt is back in style",
                        "buttons":[
                            {
                                "type":"web_url",
                                "url":"https://messengerplatform.fb.com",
                                "title":"View Item"
                            },
                            {
                                "type":"web_url",
                                "url":"https://developers.facebook.com/docs/messenger-platform",
                                "title":"Buy Item"
                            },
                            {
                                "type":"postback",
                                "title":"Bookmark Item",
                                "payload":"USER_DEFINED_PAYLOAD_FOR_ITEM100"
                            }                            
                        ]
                    },
                    {
                        "title":"Classic Grey T-Shirt",
                        "image_url":"https://lh3.googleusercontent.com/-FhcA_-jzb7Nau1zxOanijNaiDyhV1BWdPJfTEhvya_D4aK9GclZBEXwBR6-Pph5tBn6xA",
                        "subtitle":"Soft gray cotton t-shirt is back in style",
                        "buttons":[
                            {
                                "type":"web_url",
                                "url":"https://messengerplatform.fb.com",
                                "title":"View Item"
                            },
                            {
                                "type":"web_url",
                                "url":"https://developers.facebook.com/docs/messenger-platform",
                                "title":"Buy Item"
                            },
                            {
                                "type":"postback",
                                "title":"Bookmark Item",
                                "payload":"USER_DEFINED_PAYLOAD_FOR_ITEM101"
                            }                            
                        ]
                    }
                ]
            }
        }
    }
}
EOM;
	} else {
    $post = <<< EOM
    {
        "recipient":{
            "id":"{$id}"
        },
        "message":{
            "text":"{$message}"
        }
    }
EOM;
    api_send_request($access_token, $post,$message);
}

}

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

function api_get_user_profile_request($access_token, $from_user_id) {
error_log("api_get_user_profile_request start".$from_user_id);
    $url = "https://graph.facebook.com/v2.6/{$from_user_id}?fields=first_name,last_name,profile_pic&access_token={$access_token}";
    $headers = array(
        "Content-Type: application/json"
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($curl);
}