<?php
	/***************************************************************
	*  Copyright notice
	*
	*  (c) 2009 Peter Klein <pmk@io.dk>
	*  All rights reserved
	*
	*  This script is part of the TYPO3 project. The TYPO3 project is
	*  free software; you can redistribute it and/or modify
	*  it under the terms of the GNU General Public License as published by
	*  the Free Software Foundation; either version 2 of the License, or
	*  (at your option) any later version.
	*
	*  The GNU General Public License can be found at
	*  http://www.gnu.org/copyleft/gpl.html.
	*
	*  This script is distributed in the hope that it will be useful,
	*  but WITHOUT ANY WARRANTY; without even the implied warranty of
	*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	*  GNU General Public License for more details.
	*
	*  This copyright notice MUST APPEAR in all copies of the script!
	***************************************************************/

	/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   43: class tx_pmkfdl_postprocess_hook
 *   54:     function postProcessHook(&$params, &$pObj)
 *   77:     function logDownload( $logFile,$logData)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * Class which hook into pmkfdl and do additional processing
 *
 */
class tx_pmkfdl_postprocess_hook {

	/**
	 * Example postProcessHook function.
	 *
	 * @param	array		$$params: ...
	 * @param	array		$pObj: ...
	 * @return	void
	 */
	function postProcessHook(&$params, &$pObj) {
		$logData = array(
			'datetime' => date('Y-m-d H:i:s'),
			'filename' => $pObj->file,
			'accessgroups' => implode(',',$pObj->accessGroups),
			'userid' => -1,
			'username' => 'N/A',
			'usergroups' => implode(',',$pObj->userGroups)
		);
		if ($user = $pObj->feUserObj->user) {
			$logData['userid'] = $user['uid'];
			$logData['username'] = $user['username'];
		}
		$this->logDownload('pmkfdl_log.txt',$logData);
	}

	/**
	 * Writes entry in logfile
	 *
	 * @param	string		$logFile:
	 * @param	array		$logData:
	 * @return	void
	 */
	function logDownload( $logFile,$logData) {
		$logFile = PATH_site . "/fileadmin/" . $logFile;
		$data = '"'.implode('";"',$logData).'"'.chr(13);
		if (!file_exists($logFile) || !filesize($logFile)) {
			$data = '"'.implode('";"',array_keys($logData)).'"'.chr(13).$data;
		}
		if (file_put_contents($logFile, $data, FILE_APPEND | LOCK_EX ) === false) {
			error_log('pmkfdl - download log file not writable ({'.$logFile.'})', 0);
		}
	}

}
	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/res/class.tx_pmkfdl_postprocess_hook.php'])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/res/class.tx_pmkfdl_postprocess_hook.php']);
	}
?>
