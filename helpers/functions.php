<?php

	function html($tx){
        return str_replace(['<','>'],['&#60;','&#62;'],$tx);
    }

    function removeBotUserName($tx)
    {
    	return explode('@', $tx)[0];
    }

	function myUser($dbColumns=[],$myUser=[]) {
        global $db;
        $user = $db->selectWhere('users',[
            [
                'fromid'=>$myUser[0],
                'cn'=>'='
            ],
        ]);
        if ($user->num_rows) {
            if (mysqli_fetch_assoc($user)['del'] == '1') {
                $db->updateWhere('users',[
                    'del'=>0,
                ],[
                    'fromid'=>$myUser[0],
                    'cn'=>'='
                ]);
            }
            return false;
        }
        $dbInsert = [];
        foreach ($dbColumns as $key => $value) {
            $dbInsert[$dbColumns[$key]] = $myUser[$key];
        }
        return $db->insertInto('users',$dbInsert);
    }

    function getUrlData($url)
    {
        $postdata = http_build_query(
            array(
                'key' => 'b35cd306637291724feda70d76db0f1ad00a9208a5438d98369c64a00125feaf',
                'url' => $url
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $result = file_get_contents('http://alldownloader.komiljonovdev.uz/get', false, $context);
        $result = json_decode($result, true);
        return $result;
    }

    function isAdmin($fromid)
    {
        global $db;
        $user = $db->selectWhere('admins',[
            [
                'fromid'=>$fromid,
                'cn'=>'='
            ],
        ]);
        return (bool) mysqli_num_rows($user);
    }
?>