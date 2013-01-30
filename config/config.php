<?php

$config = array();

$config['importdb'] = 
	array(
		'type'=>'mysql',
		'user'=>'test1',
		'pass'=>'test',
		'host'=>'localhost',
		'port'=>'3306',
		'dbname'=>'blogfor2'
		);

$config['db_prefix'] = 'mhenox_';
$config['dbsite'] = '';
$config['db_fileroot'] = '';
$config['db_galleryroot'] = '';
$config['db_avatars'] = '';

Config::Set('router.page.spize', 'PluginSpize_ActionAdmin');
return $config;
?>
