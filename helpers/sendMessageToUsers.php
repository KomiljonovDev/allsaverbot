<?php

	if (isset($_REQUEST['sendMessage'])) {
		$sendAdConfig = mysqli_fetch_assoc(
			$db->selectWhere('sendAd',[
				[
					'id'=>1,
					'cn'=>'>='
				]
			])
		);
		$end = $sendAdConfig['sended_count'] + 60;
		if ($sendAdConfig['send_confirm'] == 1) {
			$users = null;
			if ($sendAdConfig['toRus'] == 1 && $sendAdConfig['toUs'] == 1 && $sendAdConfig['toUz'] == 1 && $sendAdConfig['toNotSelectedLang'] == 1 && $sendAdConfig['toGroup'] == 1) {
				$users = $db->selectWhere('users',[
					[
						'id'=>$sendAdConfig['sended_count'],
						'cn'=>'>'
					],
					[
						'id'=>$end,
						'cn'=>'<='
					]
				]);
			}else{
				$extra = " AND (";
				if ($sendAdConfig['toRus'] == 1) $extra .= "(lang='ru') ";
				if ($sendAdConfig['toUs'] == 1) $extra .= "(lang='en') OR ";
				if ($sendAdConfig['toUz'] == 1) $extra .= "(lang='uz') OR ";
				if ($sendAdConfig['toNotSelectedLang'] == 1) $extra .= "(lang='') OR ";
				if ($sendAdConfig['toGroup'] == 1) $extra .= "(chat_type='group')";

				$extra .= ")";
				
				$users = $db->selectWhere('users',[
					[
						'id'=>$sendAdConfig['sended_count'],
						'cn'=>'>'
					],
					[
						'id'=>$end,
						'cn'=>'<='
					]
				], $extra);
			}
			if (!$users) exit();
			if (!$users->num_rows) {
				$db->updateWhere('sendAd',
					[
						'chat_id'=>'',
						'message_id'=>'',
						'sended_count'=>'0',
						'sended_user_count'=>'0',
						'send_confirm'=>'0',
						'reply_markup'=>json_encode(false),
					],
					[
						'id'=>1,
						'cn'=>'>='
					]
				);

				$admins = $db->selectWhere('admins',[
					[
						'id'=>0,
						'cn'=>'>'
					]
				]);
				foreach ($admins as $admin) {
					$bot->sendMessage("<a href='tg://user?id=" . $sendAdConfig['chat_id'] . "'>Admin </a> tomonidan " . $sendAdConfig['sending_at'] . " da yuborilgan reklama yakunlandi.\n\nUserlarga muvoffaqiyatli yuborilganlar soni: " . $sendAdConfig['sended_user_count'], $admin['fromid']);
				}
				exit();
			}
			$sendedUser = (int) $sendAdConfig['sended_user_count'] ?? 0;
			foreach ($users as $user) {
				$copy = [
					'from_chat_id'=>$sendAdConfig['chat_id'],
					'chat_id'=>$user['fromid'],
					'message_id'=>$sendAdConfig['message_id'],
				];
				json_decode($sendAdConfig['reply_markup']) ? $copy['reply_markup'] = $sendAdConfig['reply_markup'] : false;
				$request = $bot->request('copyMessage',$copy);
				if ($request->ok) $sendedUser++;
			}
			$db->updateWhere('sendAd',
				[
					'sended_count'=>($end - 1),
					'sended_user_count'=>$sendedUser
				],
				[
					'id'=>1,
					'cn'=>'>='
				]
			);
		}
		exit();
	}
?>