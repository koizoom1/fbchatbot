<?php
$access_token = "EAABZCZBOnpfiMBAITE7qgXFfkPfruG7ab9kM4l0ZCuUUeuWlz72gomIA0W3mer3bzVZBMduRHLxm6Cz0jD8EojQj7zPU97etWo3vDr3UMxpyiyURRElSNOTT8u66tptV8AliNrMWZANHq1OZBZBxgSgEjfwZAH8pgkDB5g8p2duNCgZDZD";
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);
$messaging = $json_object->entry{0}->messaging{0};
$url = 'https://docs.google.com/uc?authuser=0&id=0B3suUrhlktR0QXYyamItc1I3bjQ&export=download';

$id = $messaging->sender->id;
if(isset($messaging->postback)) {
	$payload =  $messaging->postback->payload;
	
	if(ctype_digit($payload)){
	$post = build_game($id,(int) $payload);
    api_send_request($access_token, $post,$message);
	} else {
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
}

if(isset($messaging->message)) {
    //$id = $messaging->sender->id;
    $message =  $messaging->message->text;
	
	$content = file_get_contents($url); // HTMLを取得
	//$csvary[] = str_getcsv($content);
	$csvary = array();
	
	$lines = explode('\n',$content);
	$count = 0;
	foreach ($lines as $line) {
		$ret = explode('\n',$line);
		$colcnt = 0;
		foreach ($ret as $col) {
			$csvary[$count][$colcnt] = $col;
			$colcnt++;
		}
		$count++;
	}
	
	
	error_log($message);
	error_log($csvary[0][8]);
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
            "title":"{$csvary[0][1]}",
            "image_url":"https://lh3.googleusercontent.com/-FhcA_-jzb7Nau1zxOanijNaiDyhV1BWdPJfTEhvya_D4aK9GclZBEXwBR6-Pph5tBn6xA=s190",
            "subtitle":"{$csvary[0][2]}",
            "buttons":[
              {
                "type":"postback",
                "title":"{$csvary[0][4]}",
                "payload":"{$csvary[0][5]}"
              },
              {
                "type":"postback",
                "title":"{$csvary[0][6]}",
                "payload":"{$csvary[0][7]}"
              },
              {
                "type":"postback",
                "title":"{$csvary[0][8]}",
                "payload":"aaa"
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

function build_game($id,$num){
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
            "title":"$csvary[$num][1]",
            "image_url":"https://lh3.googleusercontent.com/-FhcA_-jzb7Nau1zxOanijNaiDyhV1BWdPJfTEhvya_D4aK9GclZBEXwBR6-Pph5tBn6xA=s190",
            "subtitle":"$csvary[$num][2]",
            "buttons":[
              {
                "type":"postback",
                "title":"$csvary[$num][4]",
                "payload":"$csvary[$num][5]"
              },
              {
                "type":"postback",
                "title":"$csvary[$num][6]",
                "payload":"$csvary[$num][7]"
              },
              {
                "type":"postback",
                "title":"$csvary[$num][8]",
                "payload":"$csvary[$num][9]"
              }              
            ]
          }
        ]
      }
    }
  }
}
EOM;

}
