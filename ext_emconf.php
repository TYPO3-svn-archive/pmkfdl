<?php

########################################################################
# Extension Manager/Repository config file for ext: "pmkfdl"
#
# Auto generated 20-09-2009 22:58
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'PMK Forced Download',
	'description' => 'Force a download on links to files. With this extension it\'s possible to force download of files like images, PDFs, MP3 ect., overriding the browser settings. (Normally when you click on a TYPO3 link to a file like an image, the image will open directly in the browser.)',
	'category' => 'fe',
	'author' => 'Peter Klein',
	'author_email' => 'pmk@io.dk',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.1.3',
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'crypt_blowfish' => '1.1.0'
		),
	),
	'_md5_values_when_last_written' => 'a:11:{s:9:"ChangeLog";s:4:"7c28";s:10:"README.txt";s:4:"ee2d";s:19:"class.tx_pmkfdl.php";s:4:"8dc4";s:28:"class.tx_pmkfdl_download.php";s:4:"d7b8";s:24:"class.tx_pmkfdl_hook.php";s:4:"4d75";s:21:"ext_conf_template.txt";s:4:"f2ef";s:12:"ext_icon.gif";s:4:"84d7";s:17:"ext_localconf.php";s:4:"5575";s:15:"ext_php_api.dat";s:4:"5e66";s:13:"mimetypes.php";s:4:"9686";s:14:"doc/manual.sxw";s:4:"da12";}',
	'suggests' => array(
	),
);

?>