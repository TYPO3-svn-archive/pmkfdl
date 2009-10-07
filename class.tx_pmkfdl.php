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
 *   45: class tx_pmkfdl
 *   54:     public function makeDownloadLink($content, $conf)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

	/**
	 * USE:
	 * The class is intended to be used without creating an instance of it.
	 * So: Don't instantiate - call functions with "tx_pmkfdl::" prefixed the function name.
	 * So use tx_pmkfdl::[method-name] to refer to the functions, eg. 'tx_pmkfdl::makeDownloadLink()'
	 *
	 */
	class tx_pmkfdl {

	/**
	 * Modifies typolink output so that link points to pmkfdl
	 *
	 * @param	string		$content: Current link
	 * @param	array		$$conf: Config options
	 * @return	string		$content: Modified link
	 */
		public function makeDownloadLink($content, $conf) {
			$file = str_replace(t3lib_div::getIndpEnv('TYPO3_SITE_URL'), '', $content);
			$filepath = PATH_site.$file;
			$filesegments = pathinfo(strtolower($filepath));
			$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pmkfdl']);
			$blockedExt = preg_split('/\s*,\s*/',$extConf['blockedExt']);
			if (file_exists($filepath) && !in_array($filesegments['extension'], $blockedExt)) {
				// Encrypt filename if "crypt_blowfish" extension is installed.	
				if (t3lib_extMgm::isLoaded('crypt_blowfish')) {
					require_once(t3lib_extMgm::extPath('crypt_blowfish').'lib/class.tx_cryptblowfish.php');
					$blowfish = new Blowfish($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']);
					$file = $blowfish->encrypt($file);
				}
				$content = 'index.php?eID=pmkfdl&file='.urlencode($file).'&ck='.md5_file($filepath);
				if (preg_match('/\|?forcedl\|?/i', $conf['makeDownloadLink'])) {
					// Force download
					$content.='&forcedl=1';
				}
				$GLOBALS['TSFE']->register['filesize'] = filesize($filepath);
				$GLOBALS['TSFE']->register['filetype'] = $filesegments['extension'];
			}
			return $content;
		}
	}

	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl.php'])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl.php']);
	}
?>
