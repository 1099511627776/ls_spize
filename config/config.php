<?php

$config = array();

$config['importdb'] = 
	array(
		'type'=>'mysql',
		'user'=>'',
		'pass'=>'',
		'host'=>'localhost',
		'port'=>'3306',
		'dbname'=>''
		);

$config['db_prefix'] = '';
$config['dbsite'] = '';
$config['db_fileroot'] = '';
$config['db_galleryroot'] = '';
$config['db_avatars'] = '';

Config::Set('router.page.spize', 'PluginSpize_ActionAdmin');
return $config;
?>
