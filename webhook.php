<?php
$access_token = "EAABZCZBOnpfiMBAITE7qgXFfkPfruG7ab9kM4l0ZCuUUeuWlz72gomIA0W3mer3bzVZBMduRHLxm6Cz0jD8EojQj7zPU97etWo3vDr3UMxpyiyURRElSNOTT8u66tptV8AliNrMWZANHq1OZBZBxgSgEjfwZAH8pgkDB5g8p2duNCgZDZD";
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);
$messaging = $json_object->entry{0}->messaging{0};

//if(isset($messaging->message)) {
//    $id = $messaging->sender->id;
//    $message = $messaging->message->text;
	//if( $message == 'じゃんけん' ){
	/*
        $post = <<< EOM
		{
			"recipient":{
				"id":"{$id}"
			},
			"message":{
				"attachment":{
					"type": "template",
					"payload": {
						"template_type": "button",
						"text": $message,
						"buttons": [
							{
								"type": "postback",
								"title": "グー",
								"payload": "rock"
							},
							{
								"type": "postback",
								"title": "チョキ",
								"payload": "scissors"
							},
							{
								"type": "postback",
								"title": "パー",
								"payload": "paper"
							}
						]
					}   
				}
			}
		}
		EOM;
		*/
    //} else {
	/*
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
		*/
	//}

//    api_send_request($access_token, $post);
//}

if(isset($messaging->message)) {
    $id = $messaging->sender->id;
    $message =  $messaging->message->text;
	error_log($message);
	if( $message == 'じゃんけん' ){
	/*
    $post = <<< EOM
    {
        "recipient":{
            "id":"{$id}"
        },
        "message":{
			"attachment":{
				"type": "template",
				"payload": {
					"template_type": "button",
					"text": $message,
					"buttons": [
						{
							"type": "postback",
							"title": "グー",
							"payload": "rock"
						},
						{
							"type": "postback",
							"title": "チョキ",
							"payload": "scissors"
						},
						{
							"type": "postback",
							"title": "パー",
							"payload": "paper"
						}
					]
				}
			}
        }
    }
EOM;
*/
$post = <<< EOM
{
    "recipient":{
        "id":"{$from_user_id}"
    },
    "message":{
	/*
        "attachment":{
            "type":"template",
            "payload":{
                "template_type":"button",
                "text":"What do you want to do next?",
                "buttons":[
                    {
                        "type":"web_url",
                        "url":"https://messengerplatform.fb.com",
                        "title":"Show Website"
                    },
                    {
                        "type":"postback",
                        "title":"Start Chatting",
                        "payload":"USER_DEFINED_PAYLOAD"
                    }
                ]
            }
        }
		*/
	/*
	"attachment": {
	  "type": "template",
	  "payload": {
		"template_type": "generic",
		"elements": [{
		  "title": "First card",
		  "subtitle": "Element #1 of an hscroll",
		  "image_url": "http://messengerdemo.parseapp.com/img/rift.png",
		  "buttons": [{
			"type": "web_url",
			"url": "https://www.messenger.com/",
			"title": "Web url"
		  }, {
			"type": "postback",
			"title": "Postback",
			"payload": "Payload for first element in a generic bubble",
		  }],
		},{
		  "title": "Second card",
		  "subtitle": "Element #2 of an hscroll",
		  "image_url": "http://messengerdemo.parseapp.com/img/gearvr.png",
		  "buttons": [{
			"type": "postback",
			"title": "Postback",
			"payload": "Payload for second element in a generic bubble",
		  }],
		}]
	  }
	}
	*/
        "attachment":{
            "type":"template",
            "payload":{
				template_type: "generic",
				elements:{
                        title: "title",
                        image_url: "https://dl.dropboxusercontent.com/u/18796572/gibasachan/sd_gibasachan.jpg",
                        subtitle: "検索結果"
                        buttons: [
                            {
                                type: "web_url",
                                url: "https://dl.dropboxusercontent.com/u/18796572/gibasachan/sd_gibasachan.jpg",
                                title: "View Web Page",
                            }
                        ]
				}
            }
        }
    }
}
EOM;
	api_get_user_profile_request($access_token, $from_user_id);
    //api_send_request($access_token, $post,$message);
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