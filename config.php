<?php

$configs = array();

$key = "projectname";//
//backlogの設定
$configs[$key]['url']  = 'example.backlog.jp';
$configs[$key]['user'] = 'backloguser';
$configs[$key]['pass'] = 'password';

//slackの設定
$configs[$key]['slack']['token']    = 'tokenString';
$configs[$key]['slack']['channel']  = urlencode('#channel-name');
$configs[$key]['slack']['username'] = 'Backlog';
//$configs[$key]['slack']['icon_url'] = urlencode('http://example.com/icon.png');//option


// 複数のプロジェクトを対象にする場合はkeyを別名として複数登録します。
/*
$key = "project2name";//
//backlogの設定
$configs[$key]['url']  = 'example.backlog.jp';
$configs[$key]['user'] = 'backloguser';
$configs[$key]['pass'] = 'password';

//slackの設定
$configs[$key]['slack']['token']    = 'tokenString';
$configs[$key]['slack']['channel']  = urlencode('#channel-name');
$configs[$key]['slack']['username'] = 'Backlog';
//$configs[$key]['slack']['icon_url'] = urlencode('http://example.com/icon.png');//option
*/

//---------------------------------------
unset($key);
