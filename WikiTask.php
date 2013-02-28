<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

define( 'WikiTask_VERSION', 'Alpha' );
$dir = dirname( __FILE__ ) . '/';

$wgExtensionMessagesFiles['WikiTask'] = $dir . 'WikiTask.i18n.php';

$wgExtensionCredits['specialpage'][] = array(
	'path'           => __FILE__,
	'name'           => 'WikiTask',
	'descriptionmsg' => 'A task manager integrated with MediaWiki categories',
	'author'         => array( 'Liam (liamzebedee) Edwards-Playne' ),
	'url'            => 'https://github.com/liamzebedee/wikitask',
	'version'        => WikiTask_VERSION,
);

$wgGroupPermissions['sysop']['wikitask/view'] = true;
$wgGroupPermissions['sysop']['wikitask/manage'] = true;
$wgAvailableRights[] = 'wikitask';

$wgAPIModules['put.task'] = 'ApiPutTask';
$wgAPIModules['rem.task'] = 'ApiRemTask';

$wgAutoloadClasses['SpecialTasks'] = $dir . 'SpecialTasks.php';
$wgAutoloadClasses['SpecialManageTasks'] = $dir . 'SpecialManageTasks.php';
$wgAutoloadClasses['ApiPutTask'] = $dir . 'ApiManageTasks.php';
$wgAutoloadClasses['ApiRemTask'] = $dir . 'ApiManageTasks.php';
$wgAutoloadClasses['TasksTable'] = $dir . 'SpecialManageTasks.php';
$wgAutoloadClasses['TaskTypesTable'] = $dir . 'SpecialManageTasks.php';
$wgAutoloadClasses['WikiTaskTask'] = $dir . 'SpecialManageTasks.php';
$wgAutoloadClasses['WikiTaskTaskType'] = $dir . 'SpecialManageTasks.php';

$wgSpecialPages['Tasks'] = 'SpecialTasks';
$wgSpecialPages['ManageTasks'] = 'SpecialManageTasks';
$wgSpecialPageGroups['Tasks'] = 'other';
$wgSpecialPageGroups['ManageTasks'] = 'other';

// Resource loader modules
$wgResourceModules['ext.wikitask'] = array(
	'localBasePath' => $dir,
	'remoteExtPath' => 'WikiTask/',
	'scripts' => array(
		'ext.wikitask.js'
	),
	'styles' => array(
		'ext.wikitask.css'
	)
);
