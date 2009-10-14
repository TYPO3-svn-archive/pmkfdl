<?php
	/***************************************************************
	*  Copyright notice
	*
	*  (c) 2009 Peter Klein <peter@umloud.dk>
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
 *   48: class tx_pmkfdl_download
 *   55:     public function makeDownloadLink()
 *  114:     public function getMimeType()
 *  142:     function decrypt($encrypted,$key)
 *  158:     function checkAccess($userGroups,$accessGroups)
 *  174:     public function error()
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(PATH_t3lib.'class.t3lib_div.php');

 /**
  * Main class. eID based. Sends the file using the 'header' function.
  *
  */
	class tx_pmkfdl_download {

	/**
	 * Force download of file
	 *
	 * @return	void
	 */
		public function makeDownloadLink() {
			// Currently not needed.
			// tslib_eidtools::connectDB(); //Connect to database

			if ($sdata = t3lib_div::_GET('sfile')) {
				// Encrypted data
				parse_str($this->decrypt($sdata,$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']),$getval);
				$feUserObj = tslib_eidtools::initFeUser(); // Initialize FE user object
				$userGroups =  t3lib_div::intExplode(',',$feUserObj->user['usergroup']);
				$accessGroups = t3lib_div::intExplode(',',$getval['access']);
				$access = $this->checkAccess($userGroups,$accessGroups);
			}
			else {
				$getval = t3lib_div::_GET();
				$access = true;
			}
			$this->file = rawurldecode($getval['file']);
			$md5 = $getval['ck'];
			$forcedl = intval( $getval['forcedl']);

			// Exit if:
			//  No filename or checksum argument is present
			//  File doesn't exist
			//   md5 checksum of file doesn't match the checksum argument
			if ($this->file == '' || $md5 == '' || !file_exists($this->file) || @md5_file($this->file) != $md5)
				$this->error();

			$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pmkfdl']);
			$blockedExt = preg_split('/\s*,\s*/',$extConf['blockedExt']);
			$this->filesegments = pathinfo(strtolower($this->file));
			// Exit if file extension is in list of illegal file extensions
			if (in_array($this->filesegments['extension'], $blockedExt))
				$this->error();

			// Make sure there's nothing else in the buffer
			ob_end_clean();

			// Get mimetype
			$mimetype = $forcedl ? 'application/force-download' : $this->getMimeType();

			// Start sending headers
			header('Pragma: public'); // required
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Cache-Control: private', false); // required for certain browsers
			header('Content-Transfer-Encoding: binary');
			header('Content-Type: ' . $mimetype);
			header('Content-Length: ' . filesize($this->file));
			header('Content-Disposition: attachment; filename="' . $this->filesegments['basename'] . '";' );
			// Send data
			readfile($this->file);
			exit;
		}

	/**
	 * Returns mimetype of current file
	 *
	 * @return	string		$mimetype; Mimetype that match selected filetype
	 */
		public function getMimeType() {
			$mimetype = '';
			// 1st choice: finfo_file
			if (function_exists('finfo_file')) {
				$finfo = finfo_open(FILEINFO_MIME);
				$mimetype = finfo_file($finfo, PATH_site.$this->file);
				finfo_close($finfo);
			}
			// 2nd choice: mime_content_type
			if ($mimetype == '' && function_exists('mime_content_type')) {
				$mimetype = mime_content_type(PATH_site.$this->file);
			}
			// 3rd choice: Use external list of mimetypes
			if ($mimetype == '') {
				require_once(t3lib_extMgm::extPath('pmkfdl').'mimetypes.php');
				$defaultmimetype = 'application/octet-stream';
				$mimetype = isset($mimetypes[$this->filesegments['extension']]) ? $mimetypes[$this->filesegments['extension']] : $defaultmimetype;
			}
			return $mimetype;
		}

	/**
	 * Decrypt file using mcrypt
	 *
	 * @param	string		$encrypted: encrypted text
	 * @param	string		$key: decryption key
	 * @return	string		$decrypted; decrypted text
	 */
		function decrypt($encrypted,$key) {
			$cipher = mcrypt_module_open(MCRYPT_BLOWFISH,'','ecb','');
			$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($cipher), MCRYPT_RAND);
			mcrypt_generic_init($cipher, $key, $iv);
			$decrypted = mdecrypt_generic($cipher,base64_decode(rawurldecode($encrypted)));
			mcrypt_generic_deinit($cipher);
			mcrypt_module_close($cipher);
			return rtrim($decrypted);
		}
	/**
	 * Checks if user has access to download file, based on TYPO3 access groups
	 *
	 * @param	array		$userGroups; fe_groups user belongs to
	 * @param	array		$accessGroups; fe_groups required for access
	 * @return	boolean		$access; True if user has the correct access credentials
	 */
		function checkAccess($userGroups,$accessGroups) {
			$access = false;
			foreach ($userGroups as $group) {
				if (in_array($group,$accessGroups)) {
					$access = true;
					break;
				}
			}
			return $access;
		}

	/**
	 * Returns 404 header to browser
	 *
	 * @return	void
	 */
		public function error() {
			header($GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling_statheader']);
			exit;
		}

	}
	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_download.php']) {
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_download.php']);
	}

	// Make instance:
	$output = t3lib_div::makeInstance('tx_pmkfdl_download');
	$output->makeDownloadLink();
?>
