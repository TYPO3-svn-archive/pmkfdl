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
 *   38: class tx_pmkfdl_download
 *   48:     function forceDownload()
 *   87:     function getMimeType()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_pmkfdl_download {

	// Files with these extensions can not be downloaded
	var $illegalExt = array('php','php4','php5','inc','sql');

	/**
	 * Force download of file
	 *
	 * @return	void
	 */
	function forceDownload() {
		//$feUserObj = tslib_eidtools::initFeUser(); // Initialize FE user object
		//tslib_eidtools::connectDB(); //Connect to database

		$this->file = urldecode($_GET['file']);
		$md5 = $_GET['ck'];

		// Exit if:
		//		No filename or checksum argument is present
		//		File doesn't exist
		// 		md5 checksum of file doesn't match the checksum argument
		if ($this->file=='' || $md5=='' || !file_exists($this->file) || @md5_file($this->file)!=$md5) exit;

		$this->filesegments = pathinfo(strtolower($this->file));
		// Exit if file extension is in list of illegal file extensions
		if (in_array($this->filesegments['extension'], $this->illegalExt)) exit;

		// Make sure there's nothing else in the buffer
		ob_end_clean();

		// Start sending headers
		header('Pragma: public'); // required
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private',false); // required for certain browsers
		header('Content-Transfer-Encoding: binary');
		header('Content-Type: ' . $this->getMimeType());
		header('Content-Length: ' . filesize($this->file));
		header('Content-Disposition: attachment; filename="' . $this->filesegments['basename'] . '";' );
		// Send data
		readfile($this->file);
		exit;
	}

	/**
	 * Returns mimetype of current file
	 *
	 * @return	string		$mimetype
	 */
	function getMimeType() {
		$mimetype = '';
		if (function_exists('finfo_file')){
   			$finfo = finfo_open(FILEINFO_MIME);
   			$mimetype = finfo_file($finfo, $this->file);
   			finfo_close($finfo);
		}
		if ($mimetype == '' && function_exists('mime_content_type')){
			$mimetype = mime_content_type($this->file);
		}
		if ($mimetype == '') {
			require_once(t3lib_extMgm::extPath('pmkfdl').'mimetypes.php');
			//$defaultmimetype="application/force-download";
			$defaultmimetype = "application/octet-stream";
			$mimetype = isset($mimetypes[$this->filesegments['extension']]) ? $mimetypes[$this->filesegments['extension']] : $defaultmimetype;
		}
		return $mimetype;
	}

}
	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_download.php'])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_download.php']);
	}

	// Make instance:
	$output = t3lib_div::makeInstance('tx_pmkfdl_download');
	$output->forceDownload();
?>