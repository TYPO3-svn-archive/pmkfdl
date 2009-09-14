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
 *   37: class tx_pmkfdl_makedownloadlink
 *   46:     function makeDownloadLink($content, $conf)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
	class tx_pmkfdl_makedownloadlink {

	/**
	 * Modifies typolink output so that link points to pmkfdl
	 *
	 * @param	string		$content: Current link
	 * @param	array		$$conf: Config options
	 * @return	string		$content: Modified link
	 */
		function makeDownloadLink($content, $conf) {
			$file = str_replace(t3lib_div::getIndpEnv('TYPO3_SITE_URL'), '', $content);
			$filepath = PATH_site.$file;
			if (!file_exists($filepath)) return $content;
			$content = 'index.php?eID=pmkfdl&file='.urlencode($file).'&ck='.md5_file($filepath);
			return $content;
		}
	}

	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_makedownloadlink.php'])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_makedownloadlink.php']);
	}
?>
