<?php

class tx_pmkfdl_ttnews_filemarkers {
	
	function extraItemMarkerProcessor($parentMarkerArray, $row, $lConf, $tt_news) {
		$this->conf = &$tt_news->conf;
		// filelinks
		if ($row['news_files']) {
			$files_stdWrap = t3lib_div::trimExplode('|', $this->conf['newsFiles_stdWrap.']['wrap']);
			$fileArr = explode(',', $row['news_files']);
			$files = '';
			$rss2Enclousres = '';
			$path = trim($this->conf['newsFiles.']['path']);
			while (list(, $val) = each($fileArr)) {
				// fills the marker ###FILE_LINK### with the links to the atached files
				$theFile = $path.$val;
				$tt_news->cObj->data['pmkfdl_filename'] = $val;
				$tt_news->cObj->data['pmkfdl_filepath'] = $theFile;
				$filelinks.= $tt_news->cObj->cObjGetSingle($this->conf['newsFiles_pmkfdl'],$this->conf['newsFiles_pmkfdl.']);
					// <enclosure> support for RSS 2.0
				if($this->theCode == 'XML') {
					if (@is_file($theFile))	{
						$fileURL      = $this->config['siteUrl'].$theFile;
						$fileSize     = filesize($theFile);
						$fileMimeType = t3lib_htmlmail::getMimeType($fileURL);

						$rss2Enclousres .= '<enclosure url="'.$fileURL.'" ';
						$rss2Enclousres .= 'length ="'.$fileSize.'" ';
						$rss2Enclousres .= 'type="'.$fileMimeType.'" />'."\n\t\t\t";
					}
				}
			}
			$parentMarkerArray['###FILE_LINK###'] = $filelinks.$files_stdWrap[1];
			$parentMarkerArray['###NEWS_RSS2_ENCLOSURES###'] = trim($rss2Enclousres);
		}

		return $parentMarkerArray;
	}
}
?>