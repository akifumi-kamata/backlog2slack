<?php

set_time_limit(300);

print "backlog2slack<br />";

//-----------------------------
ini_set( 'display_errors', 1 );

define("TMP_DIR","/tmp/backlog2slack");

define("BASE_DIR",dirname(__FILE__));

define('SLACK_API_URL',"https://slack.com/api/chat.postMessage?token=%%TOKEN%%&channel=%%CHANNEL%%&text=%%TEXT%%&username=%%USERNAME%%&icon_url=%%ICON_URL%%&pretty=1");

define("SEND_MESSAGE",3);

//------------------------------

$path = get_include_path();

$path = $path.":".dirname(__FILE__)."/Services_Backlog/Backlog";
set_include_path($path);

//------------------------------

require_once(BASE_DIR."/config.php");
require_once(BASE_DIR."/Services/Backlog.php");
require_once(BASE_DIR."/lib/xml2.class.php5");

//------------------------------

if(!is_array($configs)){
	print "configs not found.";
	exit;
}

foreach($configs as $project=>$config){

	$backlog = new Services_Backlog($config['url'],$config['user'],$config['pass']);

	# Apiの呼び出し
	$result_xml = $backlog->getTimeline();
	//print_r($result_xml);

	$xmlObj = new xml2;

	$dataAry = $xmlObj->xml2array($result_xml);

	$dataAry = dataFormat($dataAry);

	//var_dump($dataAry);

	print "<hr />";
	print "実行中：{$project}";

	slackApiSendMessage($project,$config,$dataAry);

}

print "<hr />";
print "Finish.";

exit;

//--------------------------------------------------------------------
/**
*
*/
function dataFormat($dataAry){

	if(!is_array($dataAry)){
		return false;
	}

	$dataAry = $dataAry['params']['param']['value']['array']['data']['value'];


	$aryTimeLineTmp = array();
	foreach($dataAry as $data){

		$data = $data['struct']['member'];

		$message = $data[0]['value']['string'];

		$title = $data[1]['value']['struct']['member'][0]['value']['string'];

		if(isset($data[1]['value']['struct']['member'][4]['name']) && $data[1]['value']['struct']['member'][4]['name'] == 'key'){
			$pKey = $data[1]['value']['struct']['member'][4]['value']['string'];
		}
		elseif(isset($data[1]['value']['struct']['member'][5]['name']) && $data[1]['value']['struct']['member'][5]['name'] == 'key'){
			$pKey = $data[1]['value']['struct']['member'][5]['value']['string'];
		}
		else{
			continue;
		}

		$user = $data[4]['value']['struct']['member'][1]['value']['string'];

		$update = $data[2]['value']['string'];

		if($message && $pKey && $user){
			$tmp = array();
			$tmp['key']     = $pKey;
			$tmp['title']   = $title;
			$tmp['message'] = $message;
			$tmp['user']    = $user;
			$tmp['update']  = $update;

			$aryTimeLineTmp[] = $tmp;
		}

	}

	$aryTimeLine = array();

	foreach($aryTimeLineTmp as $v){

		$key = $v['key'];

		if(!isset($aryTimeLine[$key])){
			$aryTimeLine[$key] = $v;
		}
	}

	return $aryTimeLine;

}

/**
*
*/
function getHistry($project){

	$historyFile = TMP_DIR."/".$project.".history";

	print "load:{$historyFile}<br />";

	if(!file_exists($historyFile)){
		return array();
	}

	$fileData = file_get_contents($historyFile);

	$aryData = unserialize($fileData);

	return $aryData;

}

/**
*
*/
function saveHistry($project,$aryData){

	$historyFile = TMP_DIR."/".$project.".history";

	print "save:{$historyFile}<br />";

	$fileData = serialize($aryData);

	file_put_contents($historyFile,$fileData);

	return true;

}

/**
* slackAPI連携
* メッセージの送信
*/
function slackApiSendMessage($projectKey,$config,$dataAry){

	$notSendFlg = false;

	$slackConfigAry = $config['slack'];

	$histryAry = getHistry($projectKey);

	if(!is_array($histryAry)){
		$histryAry = array();
		$notSendFlg = true;
	}

	// 送信対象をチェック
	$sendTargetAry = array();
	foreach($dataAry as $aryVal){

		$key    = $aryVal['key'];
		$update = $aryVal['update'];

		if(!isset($histryAry[$key])){
		// データなし
			$sendTargetAry[] = $aryVal;

		} else{

			// 更新されているかチェック
			if($update > $histryAry[$key]){
				$sendTargetAry[] = $aryVal;
			}
		}

		$histryAry[$key] = $update;

	}

	//var_dump($sendTargetAry);
	//var_dump($histryAry);

	// データを保存
	$histryAry = saveHistry($projectKey,$histryAry);
	print "データを保存しました。<br />";


	if($notSendFlg){
		print "データを更新しました。APIへの送信は中止しました。<br />";
		return true;
	}

	if(empty($sendTargetAry)){
		print "送信対象のデータがありません。<br />";
		return true;
	}

	$backlogUrlBase = "https://".$config['url']."/view/";

	$count = 0;
	foreach($sendTargetAry as $data){

		$count ++;

		$key     = $data['key'];
		$title   = $data['title'];
		$message = $data['message'];
		$user    = $data['user'];

		$message = mb_strimwidth($message,0,200,"...",'UTF-8');

		$url = $backlogUrlBase.$key;

		$message = "{$title}\n{$message} by {$user}\n{$url}";

		$message = urlencode($message);

		$url = SLACK_API_URL;
		$url = str_replace('%%TEXT%%'    ,$message                   ,$url);
		$url = str_replace('%%TOKEN%%'   ,$slackConfigAry['token']   ,$url);
		$url = str_replace('%%CHANNEL%%' ,$slackConfigAry['channel'] ,$url);
		$url = str_replace('%%USERNAME%%',$slackConfigAry['username'],$url);
		$url = str_replace('%%ICON_URL%%',$slackConfigAry['icon_url'],$url);

		//var_dump($url);

		// slackにメッセージ送信
		$result = file_get_contents($url);


		print "<br />";
		print "send message:{$key}<br />";
		print_r($result);

		if($count >= SEND_MESSAGE){
			print "メッセージ送信自主制限:".SEND_MESSAGE."<br />";
			break;
		}

	}

	return true;
}

