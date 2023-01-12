<?php
	/**
        * Komiljonov Obidjon
        * @author https://github.com/KomiljonovDev/, 
        * @author https://t.me/GoldCoderUz, 
        * @author https://t.me/uzokdeveloper, 
        * @author https://t.me/komiljonovdev,
        * 
    */
    ini_set('display_errors', 1);
    
	require 'Telegram/TelegramBot.php';
	require 'db_config/db_config.php';
	require 'helpers/functions.php';

	use TelegramBot as Bot;

	$dataSet = ['botToken'=>'Bot Token'];

	$bot = new Bot($dataSet);

	use \db_config;

	require_once 'helpers/variebles.php';
	$db = new db_config;
	
	include 'helpers/sendMessageToUsers.php';

	if ($update) {
		if ($update->message) {
			if ($type == 'private') {
				if (removeBotUserName($text) == "/start") {
					$myUser = myUser(['fromid','name','user','chat_type','lang','del','created_at'],[$fromid,$full_name,$user,'private','',0]);
					if ($myUser) {
						$bot->sendChatAction('typing', $chat_id)->sendMessage('Assalomu alaykum, xush kelibsiz!');
						exit();
					}
					$bot->sendChatAction('typing', $chat_id)->sendMessage('Assalomu alaykum, qayata xush kelibsiz!');
				}
				preg_match('/^(?:https?:\/\/)?(?:[^@\/\n]+@)?(?:www\.)?([^:\/\n]+)/', $text, $matches);
				if ($matches[1]) {
					// $bot->deleteMessage($miid);
					foreach ($update->message->entities as $value) {
						if ($value->type == 'url') {
							$url = substr($text, $value->offset, $value->offset + $value->length);
							break;
						}
					}
					$getData = getUrlData($url);
					if ($getData) {
						$db->deleteWhere('ref_user_videos',[
							[
								'user_id'=>$fromid,
								'cn'=>'='
							]
						]);
						$keyBoards = array();
						foreach ($getData['medias'] as $key => $media) {
							$keyBoards[] = ['text'=> $media['extension'] . " - " . $media['quality'] . " - " . $media['formattedSize'], 'callback_data' => 'get_item_' . $key];
							$db->insertInto('ref_user_videos',[
								'user_id'=>$fromid,
								'url'=>$media['url'],
								'extension'=>$media['extension']
							]);
						}
						$keyBoards = array_chunk($keyBoards, 2);
						$bot->sendChatAction('upload_photo', $fromid)->setInlineKeyboard($keyBoards)->sendPhoto($getData['thumbnail'], $getData['title'] . "\n\n" . $getData['duration']);
					}
				}
			}
		}else if ($update->callback_query) {
			if (mb_stripos($data, 'get_item_')!==false) {
				$item = (int) explode('get_item_', $data)[1];
				$items = $db->selectWhere('ref_user_videos',[
					[
						'user_id'=>$cbid,
						'cn'=>'='
					]
				]);
				$url = null;
				foreach ($items as $key => $value) {
					if ($key == $item) {
						$url = $value['url'];
						$extension = $value['extension'];
						break;
					}
				}

				if ($url) {
					if ($extension == 'mp4') {
						$bot->sendChatAction('upload_video',$cbid)->uploadFile('sendVideo',[
								'chat_id'=>$cbid,
								'video'=>$url,
								'caption'=>"Video yuklab olindi!"
							]
						);
					}else if($extension == 'mp3'){
						$bot->sendChatAction('upload_audio',$cbid)->uploadFile('sendAudio',[
								'chat_id'=>$cbid,
								'audio'=>$url,
								'caption'=>"Audio yuklab olindi!"
							]
						);
					}else{
						$bot->sendChatAction('upload_document',$cbid)->uploadFile('sendDocument',[
								'chat_id'=>$cbid,
								'document'=>$url,
								'caption'=>"Fayl yuklab olindi!"
							]
						);
					}
					exit();
				}
				$bot->request('answerCallbackQuery',[
					'callback_query_id'=>$qid,
					'text'=>'Iltimos havolani qayta tashlang!',
					'show_alert'=>true
				]);
			}
		}else if ($update->chat_join_request) {
            $join_request = $update->chat_join_request;
            $fromid = $join_request->from->id;
            $full_name = html($join_request->from->first_name . " " . $join_request->from->last_name);
            $username = $join_request->from->username;
            $approve = $bot->request('approveChatJoinRequest',[
                'chat_id'=>$join_request->chat->id,
                'user_id'=>$fromid
            ]);
            if ($approve->ok) {
                $myUser = myUser(['fromid','name','user','chat_type','lang','del','created_at'],[$fromid,$full_name,$user,'private','',0]);
                $getChat = $bot->request('getChat',[
                    'chat_id'=>$join_request->chat->id,
                ]);
                $title = $getChat->result->title;
                $link = $getChat->result->invite_link;
                $send = $bot->request('sendMessage',[
                    'chat_id'=>$fromid,
                    'text'=>"Assalomu alaykum <a href='" . $link . "'>" . $title . "</a> kanalimizga obuna bo'lganingiz uchun tashakkur. ✅\n\nSiz ushbu bot orqali ijtimoi tarmoqlardan video va postlarni yuklab olishingiz mumkin. Buning botga /start bosing ✅",
                    'parse_mode'=>'html'
                ]);
            }
        }
	}

	include 'helpers/admin/admin.php';

?>