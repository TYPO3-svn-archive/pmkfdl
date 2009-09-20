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
 *   45: class tx_pmkfdl_download
 *   52:     public function makeDownloadLink()
 *   98:     public function getMimeType()
 *
 * TOTAL FUNCTIONS: 2
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
			//$feUserObj = tslib_eidtools::initFeUser(); // Initialize FE user object
			//tslib_eidtools::connectDB(); //Connect to database

			$this->file = urldecode(t3lib_div::_GET('file'));
			$md5 = t3lib_div::_GET('ck');
			$forcedl = intval(t3lib_div::_GET('forcedl'));

			// Exit if:
			//  No filename or checksum argument is present
			//  File doesn't exist
			//   md5 checksum of file doesn't match the checksum argument
			if ($this->file == '' || $md5 == '' || !file_exists($this->file) || @md5_file($this->file) != $md5) exit;

			$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pmkfdl']);
			$blockedExt = preg_split('/\s*,\s*/',$extConf['blockedExt']);
			$this->filesegments = pathinfo(strtolower($this->file));
			// Exit if file extension is in list of illegal file extensions
			if (in_array($this->filesegments['extension'], $blockedExt)) exit;

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
				$mimetype = finfo_file($finfo, $this->file);
				finfo_close($finfo);
			}
			// 2nd choice: mime_content_type
			if ($mimetype == '' && function_exists('mime_content_type')) {
				$mimetype = mime_content_type($this->file);
			}
			// 3rd choice: Use external list of mimetypes
			if ($mimetype == '') {
				require_once(t3lib_extMgm::extPath('pmkfdl').'mimetypes.php');
				$defaultmimetype = 'application/octet-stream';
				$mimetype = isset($mimetypes[$this->filesegments['extension']]) ? $mimetypes[$this->filesegments['extension']] : $defaultmimetype;
			}
			return $mimetype;
		}

	}
	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_download.php']) {
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_download.php']);
	}

	// Make instance:
	$output = t3lib_div::makeInstance('tx_pmkfdl_download');
	$output->makeDownloadLink();
?>
