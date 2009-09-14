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
 *   43: class tx_pmkfdl_hook implements tslib_content_stdWrapHook
 *   53:     function stdWrapPreProcess($content, array $configuration, tslib_cObj &$parentObject)
 *   65:     function stdWrapOverride($content, array $configuration, tslib_cObj &$parentObject)
 *   77:     function stdWrapProcess($content, array $configuration, tslib_cObj &$parentObject)
 *   92:     function stdWrapPostProcess($content, array $configuration, tslib_cObj &$parentObject)
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
require_once(PATH_typo3.'/sysext/cms/tslib/interfaces/interface.tslib_content_stdwraphook.php');
require_once(t3lib_extMgm::extPath('pmkfdl').'class.tx_pmkfdl_makedownloadlink.php');

class tx_pmkfdl_hook implements tslib_content_stdWrapHook {

	/**
	 * Hook for modifying $content before core's stdWrap does anything
	 *
	 * @param	string		input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
	 * @param	array		TypoScript stdWrap properties
	 * @param	tslib_cObj		parent content object
	 * @return	string		further processed $content
	 */
	function stdWrapPreProcess($content, array $configuration, tslib_cObj &$parentObject) {
		return $content;
	}

	/**
	 * Hook for modifying $content after core's stdWrap has processed setContentToCurrent, setCurrent, lang, data, field, current, cObject, numRows, filelist and/or preUserFunc
	 *
	 * @param	string		input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
	 * @param	array		TypoScript stdWrap properties
	 * @param	tslib_cObj		parent content object
	 * @return	string		further processed $content
	 */
	function stdWrapOverride($content, array $configuration, tslib_cObj &$parentObject) {
		return $content;
	}

	/**
	 * Hook for modifying $content after core's stdWrap has processed override, preIfEmptyListNum, ifEmpty, ifBlank, listNum, trim and/or more (nested) stdWraps
	 *
	 * @param	string		input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
	 * @param	array		TypoScript "stdWrap properties".
	 * @param	tslib_cObj		parent content object
	 * @return	string		further processed $content
	 */
	function stdWrapProcess($content, array $configuration, tslib_cObj &$parentObject) {
		if ($configuration['forceDownload']) {
			$content = tx_pmkfdl_makedownloadlink::makeDownloadLink($content,$configuration);
		}
		return $content;
	}

	/**
	 * Hook for modifying $content after core's stdWrap has processed anything but debug
	 *
	 * @param	string		input value undergoing processing in this function. Possibly substituted by other values fetched from another source.
	 * @param	array		TypoScript stdWrap properties
	 * @param	tslib_cObj		parent content object
	 * @return	string		further processed $content
	 */
	function stdWrapPostProcess($content, array $configuration, tslib_cObj &$parentObject) {
		return $content;
	}
}
	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_hook.php'])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pmkfdl/class.tx_pmkfdl_hook.php']);
	}
?>