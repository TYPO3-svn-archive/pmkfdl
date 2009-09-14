<?php
if (!defined ("TYPO3_MODE")) die ("Access denied.");
if (TYPO3_branch>4.1) {
	// TYPO3 v4.2 or higher
	$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_content.php']['stdWrap'][] = 'EXT:pmkfdl/class.tx_pmkfdl_hook.php:&tx_pmkfdl_hook';
}
else {
	require_once(t3lib_extMgm::extPath('pmkfdl').'class.tx_pmkfdl_makedownloadlink.php');
}
$TYPO3_CONF_VARS['FE']['eID_include']['pmkfdl'] = 'EXT:pmkfdl/class.tx_pmkfdl_download.php';
?>