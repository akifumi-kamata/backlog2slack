<?php

$configs = array();

$key = "projectname";//
//backlog�̐ݒ�
$configs[$key]['url']  = 'example.backlog.jp';
$configs[$key]['user'] = 'backloguser';
$configs[$key]['pass'] = 'password';

//slack�̐ݒ�
$configs[$key]['slack']['token']    = 'tokenString';
$configs[$key]['slack']['channel']  = urlencode('#channel-name');
$configs[$key]['slack']['username'] = 'Backlog';
//$configs[$key]['slack']['icon_url'] = urlencode('http://example.com/icon.png');//option


// �����̃v���W�F�N�g��Ώۂɂ���ꍇ��key��ʖ��Ƃ��ĕ����o�^���܂��B
/*
$key = "project2name";//
//backlog�̐ݒ�
$configs[$key]['url']  = 'example.backlog.jp';
$configs[$key]['user'] = 'backloguser';
$configs[$key]['pass'] = 'password';

//slack�̐ݒ�
$configs[$key]['slack']['token']    = 'tokenString';
$configs[$key]['slack']['channel']  = urlencode('#channel-name');
$configs[$key]['slack']['username'] = 'Backlog';
//$configs[$key]['slack']['icon_url'] = urlencode('http://example.com/icon.png');//option
*/

//---------------------------------------
unset($key);
