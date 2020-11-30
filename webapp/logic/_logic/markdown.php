<?php

function text2markdown($str) {
	$objDB = DBD_Query::singleQuery();

	// シングル変換
	$markDownArray = array(
		'single' => array(
			array(
				'match' => "/^# .*?$/m",
				'erase' => array('/^# /'),
				'start' => '<h3 class="c-title__l">',
				'end' => '</h3>',
			),
			array(
				'match' => "/^## .*?$/m",
				'erase' => array('/^## /'),
				'start' => '<h4 class="c-title__m">',
				'end' => '</h4>',
			),
			array(
				'match' => "/\*\*.*?\*\*/is",
				'erase' => array('/\*\*/'),
				'start' => '<span class="c-text__bold">',
				'end' => '</span>',
			),
			array(
				'match' => "/```.*?```/is",
				'erase' => array(
					'/```\\r\\n/',
					'/\\r\\n```/',
					'/```/',
				),
				'start' => '<div class="c-text__code">',
				'end' => '</div>',
			),
		),
		'after' => array(
			array(
				'match' => "/\(YouTube:.*?\)/is",
				'erase' => array('/\(/', '/\)/', '/YouTube:/'),
				'start' => '<iframe width="100%" height="315" src="https://www.youtube.com/embed/',
				'end' => '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
			),
			array(
				'match' => "/\(Twitch:.*?\)/is",
				'erase' => array('/\(/', '/\)/', '/Twitch:/'),
				'start' => '<iframe src="https://player.twitch.tv/?autoplay=false&video=v',
				'end' => '" frameborder="0" allowfullscreen="true" scrolling="no" height="315" width="100%"></iframe>',
			),
			array(
				'match' => "/\(TwitchClip:.*?\)/is",
				'erase' => array('/\(/', '/\)/', '/TwitchClip:/'),
				'start' => '<iframe src="https://clips.twitch.tv/embed?clip=',
				'end' => '&autoplay=false" frameborder="0" allowfullscreen="true" height="315" width="100%"></iframe>',
			),
		),
	);
	foreach ($markDownArray['single'] as $key => $val) {
		preg_match_all($val['match'], $str, $replaceArray);
		foreach ($replaceArray[0] as $replace) {
			$sourceReplace = $replace;
			foreach ($val['erase'] as $erase) $replace = preg_replace($erase, '', trim($replace));
			$str = str_replace($sourceReplace, $val['start'].$replace.$val['end'], $str);
		}
	}

	// テーブル
	$expLine = explode("\r\n", $str);
	$expLine[] = '';
	$tableArray = array();
	$tableFlg = false;
	$tableNo = 1;
	foreach ($expLine as $key => $val) {
		if (preg_match("/^\|(.*)\|$/", $val)) {
			$tableFlg = true;
			$tableArray[] = $val;
		} elseif ($tableFlg == true) {
			$tableFlg = false;
			$sourceTableArray = $tableArray;

			$tableArray[0] = str_replace("|", "</th><th>", $tableArray[0]);
			$expAlign = explode("|", $tableArray[1]);
			unset($expAlign[0], $expAlign[count($expAlign)]);
			$tableCss = '';
			foreach ($expAlign as $alignKey => $align) {
				if (preg_match("/\:(.*)\:$/", $align)) {
					$tableCss.= '#article-table'.$tableNo.' td:nth-of-type('.$alignKey.') { text-align:center; } ';
				} elseif (preg_match("/(.*)\:$/", $align)) {
					$tableCss.= '#article-table'.$tableNo.' td:nth-of-type('.$alignKey.') { text-align:right; } ';
				} else {
					$tableCss.= '#article-table'.$tableNo.' td:nth-of-type('.$alignKey.') { text-align:left; } ';
				}
			}
			unset($tableArray[1]);

			$replaceHtml = implode("\r\n", $tableArray);
			$replaceHtml = str_replace("\r\n", "</tr>\r\n<tr>", $replaceHtml);
			$replaceHtml = str_replace("|", "</td><td>", $replaceHtml);
			$replaceHtml = '<table id="article-table'.$tableNo.'" class="c-table"><tr>'.$replaceHtml.'</tr></table>';
			$replaceHtml = str_replace("<tr></td><td>", "<tr><td>", $replaceHtml);
			$replaceHtml = str_replace("</td><td></tr>", "</td></tr>", $replaceHtml);
			$replaceHtml = str_replace("<tr></th><th>", "<tr><th>", $replaceHtml);
			$replaceHtml = str_replace("</th><th></tr>", "</th></tr>", $replaceHtml);
			$replaceHtml = str_replace("\r\n", "", $replaceHtml);
			$replaceHtml = $replaceHtml.'<style> '.$tableCss.'</style>';

			$str = str_replace(implode("\r\n", $sourceTableArray), $replaceHtml, $str);

			$tableNo++;
			$tableArray = array();
		}
	}

	// 画像
	preg_match_all("/\!\[.*?\]\(IMAGEID:[0-9]+\)/is", $str, $replaceArray);
	foreach ($replaceArray[0] as $key => $val) {
		preg_match("/\[.*?\]/", $val, $description);
		preg_match("/\(IMAGEID:[0-9]+\)/", $val, $imageId);
		$infoImage = $objDB->findByIdData('image', str_replace('(IMAGEID:', '', str_replace(')', '', $imageId[0])));
		$str = str_replace($val, '<div class="u-text-c"><img src="'.str_replace(HOME_URL, '/', UPLOAD_FILE_URL).$infoImage['file'].'" alt="'.substr($description[0], 1, count($description[0]) - 2).'"></div>', $str);
	}

	// テキストリンク(取得)
	$textLinkArray = array();
	preg_match_all("/\[.*?\]\(https?:\/\/.*?\)/is", $str, $textLinkArray);
	foreach ($textLinkArray[0] as $key => $val) {
		preg_match("/\[.*?\]/", $val, $linkText);
		preg_match("/\(https?:\/\/.*?\)/", $val, $linkUrl);
		$textLinkArray[$key]['text'] = str_replace("[", "", str_replace("]", "", $linkText[0]));
		$textLinkArray[$key]['url'] = str_replace("(", "", str_replace(")", "", $linkUrl[0]));
		$str = str_replace($val, '{textLink-'.$key.'}', $str);
	}

	// 改行・URL変換
	$str = nl2br($str);
	$str = mb_ereg_replace('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)', '<a href="\1" target="_blank" class="c-text__link">\1</a>', $str);
	
	// 後処理
	foreach ($markDownArray['after'] as $key => $val) {
		preg_match_all($val['match'], $str, $replaceArray);
		foreach ($replaceArray[0] as $replace) {
			$sourceReplace = $replace;
			foreach ($val['erase'] as $erase) $replace = preg_replace($erase, '', trim($replace));
			$str = str_replace($sourceReplace, $val['start'].$replace.$val['end'], $str);
		}
	}

	// テキストリンク(設置)
	foreach ($textLinkArray as $key => $val) {
		$str = str_replace('{textLink-'.$key.'}', '<a href="'.$val['url'].'" target="_blank" class="c-text__link">'.$val['text'].'</a>', $str);
	}

	return $str;
}



function text2markdownDev($str, $dev) {
	$objDB = DBD_Query::singleQuery();

	if ($dev == 1) return text2markdown($str);

	$markDownArray = array(
		'single' => array(
			array(
				'match' => "/^# .*?$/m",
				'erase' => array('/^# /'),
				'start' => '<h3 class="c-title__l">',
				'end' => '</h3>',
			),
			array(
				'match' => "/^## .*?$/m",
				'erase' => array('/^## /'),
				'start' => '<h4 class="c-title__m">',
				'end' => '</h4>',
			),
			array(
				'match' => "/\*\*.*?\*\*/is",
				'erase' => array('/\*\*/'),
				'start' => '<span class="c-text__bold">',
				'end' => '</span>',
			),
			array(
				'match' => "/```.*?```/is",
				'erase' => array(
					'/```\\r\\n/',
					'/\\r\\n```/',
					'/```/',
				),
				'start' => '<div class="c-text__code">',
				'end' => '</div>',
			),
		),
		'after' => array(
			array(
				'match' => "/\(YouTube:.*?\)/is",
				'erase' => array('/\(/', '/\)/', '/YouTube:/'),
				'start' => '<iframe width="100%" height="315" src="https://www.youtube.com/embed/',
				'end' => '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
			),
			array(
				'match' => "/\(Twitch:.*?\)/is",
				'erase' => array('/\(/', '/\)/', '/Twitch:/'),
				'start' => '<iframe src="https://player.twitch.tv/?autoplay=false&video=v',
				'end' => '" frameborder="0" allowfullscreen="true" scrolling="no" height="315" width="100%"></iframe>',
			),
			array(
				'match' => "/\(TwitchClip:.*?\)/is",
				'erase' => array('/\(/', '/\)/', '/TwitchClip:/'),
				'start' => '<iframe src="https://clips.twitch.tv/embed?clip=',
				'end' => '&autoplay=false" frameborder="0" allowfullscreen="true" height="315" width="100%"></iframe>',
			),
		),
	);
	foreach ($markDownArray['single'] as $key => $val) {
		preg_match_all($val['match'], $str, $replaceArray);
		foreach ($replaceArray[0] as $replace) {
			$sourceReplace = $replace;
			foreach ($val['erase'] as $erase) $replace = preg_replace($erase, '', trim($replace));
			$str = str_replace($sourceReplace, $val['start'].$replace.$val['end'], $str);
		}
	}

	$expLine = explode("\r\n", $str);
	$expLine[] = '';
	$tableArray = array();
	$tableFlg = false;
	$tableNo = 1;
	foreach ($expLine as $key => $val) {
		if (preg_match("/^\|(.*)\|$/", $val)) {
			$tableFlg = true;
			$tableArray[] = $val;
		} elseif ($tableFlg == true) {
			$tableFlg = false;
			$sourceTableArray = $tableArray;

			$tableArray[0] = str_replace("|", "</th><th>", $tableArray[0]);
			$expAlign = explode("|", $tableArray[1]);
			unset($expAlign[0], $expAlign[count($expAlign)]);
			$tableCss = '';
			foreach ($expAlign as $alignKey => $align) {
				if (preg_match("/\:(.*)\:$/", $align)) {
					$tableCss.= '#article-table'.$tableNo.' td:nth-of-type('.$alignKey.') { text-align:center; } ';
				} elseif (preg_match("/(.*)\:$/", $align)) {
					$tableCss.= '#article-table'.$tableNo.' td:nth-of-type('.$alignKey.') { text-align:right; } ';
				} else {
					$tableCss.= '#article-table'.$tableNo.' td:nth-of-type('.$alignKey.') { text-align:left; } ';
				}
			}
			unset($tableArray[1]);

			$replaceHtml = implode("\r\n", $tableArray);
			$replaceHtml = str_replace("\r\n", "</tr>\r\n<tr>", $replaceHtml);
			$replaceHtml = str_replace("|", "</td><td>", $replaceHtml);
			$replaceHtml = '<table id="article-table'.$tableNo.'" class="c-table"><tr>'.$replaceHtml.'</tr></table>';
			$replaceHtml = str_replace("<tr></td><td>", "<tr><td>", $replaceHtml);
			$replaceHtml = str_replace("</td><td></tr>", "</td></tr>", $replaceHtml);
			$replaceHtml = str_replace("<tr></th><th>", "<tr><th>", $replaceHtml);
			$replaceHtml = str_replace("</th><th></tr>", "</th></tr>", $replaceHtml);
			$replaceHtml = str_replace("\r\n", "", $replaceHtml);
			$replaceHtml = $replaceHtml.'<style> '.$tableCss.'</style>';

			$str = str_replace(implode("\r\n", $sourceTableArray), $replaceHtml, $str);

			$tableNo++;
			$tableArray = array();
		}
	}

	preg_match_all("/\!\[.*?\]\(IMAGEID:[0-9]+\)/is", $str, $replaceArray);
	foreach ($replaceArray[0] as $key => $val) {
		preg_match("/\[.*?\]/", $val, $description);
		preg_match("/\(IMAGEID:[0-9]+\)/", $val, $imageId);
		$infoImage = $objDB->findByIdData('image', str_replace('(IMAGEID:', '', str_replace(')', '', $imageId[0])));
		$str = str_replace($val, '<div class="u-text-c"><img src="'.str_replace(HOME_URL, '/', UPLOAD_FILE_URL).$infoImage['file'].'" alt="'.substr($description[0], 1, count($description[0]) - 2).'"></div>', $str);
	}

	// テキストリンク(取得)
	$textLinkArray = array();
	preg_match_all("/\[.*?\]\(https?:\/\/.*?\)/is", $str, $textLinkArray);
	foreach ($textLinkArray[0] as $key => $val) {
		preg_match("/\[.*?\]/", $val, $linkText);
		preg_match("/\(https?:\/\/.*?\)/", $val, $linkUrl);
		$textLinkArray[$key]['text'] = str_replace("[", "", str_replace("]", "", $linkText[0]));
		$textLinkArray[$key]['url'] = str_replace("(", "", str_replace(")", "", $linkUrl[0]));
		$str = str_replace($val, '{textLink-'.$key.'}', $str);
	}

	$str = nl2br($str);
	$str = mb_ereg_replace('(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)', '<a href="\1" target="_blank" class="c-text__link">\1</a>', $str);

	foreach ($markDownArray['after'] as $key => $val) {
		preg_match_all($val['match'], $str, $replaceArray);
		foreach ($replaceArray[0] as $replace) {
			$sourceReplace = $replace;
			foreach ($val['erase'] as $erase) $replace = preg_replace($erase, '', trim($replace));
			$str = str_replace($sourceReplace, $val['start'].$replace.$val['end'], $str);
		}
	}

	// テキストリンク(設置)
	foreach ($textLinkArray as $key => $val) {
		$str = str_replace('{textLink-'.$key.'}', '<a href="'.$val['url'].'" target="_blank" class="c-text__link">'.$val['text'].'</a>', $str);
	}

	return $str;
}
