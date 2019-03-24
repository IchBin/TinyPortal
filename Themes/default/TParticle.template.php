<?php
/**
 * @package TinyPortal
 * @version 2.0.0
 * @author tino - http://www.tinyportal.net
 * @founder Bloc
 * @license MPL 2.0
 *
 * The contents of this file are subject to the Mozilla Public License Version 2.0
 * (the "License"); you may not use this package except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Copyright (C) 2018 - The TinyPortal Team
 *
 */

function template_submitarticle() 
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $boarddir, $boardurl, $language, $smcFunc;

	$tpmonths=array(' ','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	$mg = $context['TPortal']['editarticle'];

	if(!isset($context['TPortal']['category_name'])) {
		$context['TPortal']['category_name'] = $txt['tp-uncategorised'];
    }

	echo '
	<form accept-charset="', $context['character_set'], '" name="TPadmin3" action="' . $scripturl . '?action=tpadmin" enctype="multipart/form-data" method="post" onsubmit="submitonce(this);">
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
		<input name="tpadmin_form" type="hidden" value="editarticle' . $mg['id'] . '">
		<div class="cat_bar"><h3 class="catbg"><img style="margin-right: 4px;" border="0" src="' .$settings['tp_images_url']. '/TP' , $mg['off']=='1' ? 'red' : 'green' , '.png" alt=""  />' , $mg['id']=='' ? $txt['tp-addarticle']. '' .$txt['tp-incategory'] . (html_entity_decode($context['TPortal']['category_name'])) : $txt['tp-editarticle']. ' ' .html_entity_decode($mg['subject']) , '' , $mg['id']==0 ? '' : '&nbsp;-&nbsp;<a href="'.$scripturl.'?page='.$mg['id'].'">['.$txt['tp-preview'].']</a>';
	echo '</h3></div>
		<div id="edit-add-single-article" class="admintable admin-area">
		<div class="windowbg noup">
			<div class="formtable padding-div">
			<div>
						<div class="font-strong">' , $txt['tp-title'] , ':</div>
						<input style="width: 92%;" name="tp_article_subject" type="text" value="'. html_entity_decode($mg['subject'], ENT_QUOTES, $context['character_set']) .'">
					</div>
					<div>
						<div class="font-strong">'.$txt['tp-shortname_article'].'&nbsp;</div>
						<input size=20 name="tp_article_shortname" type="text" value="'.$mg['shortname'].'">
					</div>
					<br>
					<div>';
				$tp_use_wysiwyg = $context['TPortal']['show_wysiwyg'];
				if($mg['articletype'] == 'php')
					echo '
							<textarea name="tp_article_body" id="tp_article_body" wrap="auto">' ,  $mg['body'] , '</textarea><br>';
				elseif($tp_use_wysiwyg > 0 && ($mg['articletype'] == '' || $mg['articletype'] == 'html'))
					TPwysiwyg('tp_article_body', $mg['body'], true, 'qup_tp_article_body', $tp_use_wysiwyg);
				elseif($tp_use_wysiwyg == 0 && ($mg['articletype'] == '' || $mg['articletype'] == 'html'))
					echo '
							<textarea name="tp_article_body" id="tp_article_body" wrap="auto">' , $mg['body'], '</textarea><br>';
				elseif($mg['articletype'] == 'bbc')
					TP_bbcbox($context['TPortal']['editor_id']);
				else
					echo $txt['tp-importarticle'] , ' &nbsp;<input size="60" name="tp_article_fileimport" type="text" value="' , $mg['fileimport'] , '">' ;
					echo '
					</div>
				<div class="padding-div"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
			<hr><br>
				<dl class="settings">
					<dt>
						<label for="field_name">', $txt['tp-status'], '</label>
					</dt>
					<dd>';
					if (!empty($context['TPortal']['editing_article']))
					{
						// show checkboxes since we have these features aren't available until the article is saved.
						echo '
							<img style="cursor: pointer;" class="toggleFront" id="artFront' .$mg['id']. '" title="'.$txt['tp-setfrontpage'].'" border="0" src="' .$settings['tp_images_url']. '/TPfront' , $mg['frontpage']=='1' ? '' : '2' , '.png" alt="'.$txt['tp-setfrontpage'].'"  />
							<img style="cursor: pointer;" class="toggleSticky" id="artSticky' .$mg['id']. '" title="'.$txt['tp-setsticky'].'" border="0" src="' .$settings['tp_images_url']. '/TPsticky' , $mg['sticky']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-setsticky'].'"  />
							<img style="cursor: pointer;" class="toggleLock" id="artLock' .$mg['id']. '" title="'.$txt['tp-setlock'].'" border="0" src="' .$settings['tp_images_url']. '/TPlock' , $mg['locked']=='1' ? '1' : '2' , '.png" alt="'.$txt['tp-setlock'].'"  />';
					}
					else
					{
						// Must be a new article, so lets show the check boxes instead.
						echo '
							<input type="checkbox" id="artFront'. $mg['id']. '" name="tp_article_frontpage" value="1" /> '. $txt['tp-setfrontpage']. '<br>
							<input type="checkbox" id="artSticky'. $mg['id']. '" name="tp_article_sticky" value="1" /> '. $txt['tp-setsticky']. '<br>
							<input type="checkbox" id="artLock'. $mg['id']. '" name="tp_article_locked" value="1" /> '. $txt['tp-setlock']. '';
					}
						echo '<br><br>
					</dd>
					<dt>
						<label for="field_name">', $txt['tp-approved'], '</label>
					</dt>
					<dd>
							
							<input name="tp_article_approved" type="radio" value="1" ', $mg['approved']=='1' ? 'checked' : '' ,'>  '.$txt['tp-yes'].'
							<input name="tp_article_approved" type="radio" value="0" ', $mg['approved']=='0' ? 'checked' : '' ,'>  '.$txt['tp-no'].'<br><br>
					</dd>
					<dt>
						<label for="field_name">', $txt['tp-author'], '</label>
					</dt>
					<dd>
							<b><a href="' . $scripturl . '?action=profile;u='.$mg['author_id'].'" target="_blank">'.$mg['real_name'].'</a></b>
							&nbsp;' . $txt['tp-assignnewauthor'] . ' <input size="8" maxsize="12" name="tp_article_authorid" value="' . $mg['author_id'] . '" /><br><br>
					</dd>
					<dt>
						<label for="field_name">', $txt['tp-created'], '</label>
					</dt>
					<dd>';
				// day
				echo '
							<input name="tp_article_timestamp" type="hidden" value="'.$mg['date'].'">
							<select size="1" name="tp_article_day">';
				$day = date("j",$mg['date']);
				$month = date("n",$mg['date']);
				$year = date("Y",$mg['date']);
				$hour = date("G",$mg['date']);
				$minute = date("i",$mg['date']);
				for($a=1; $a<32;$a++)
					echo '
								<option value="'.$a.'" ' , $day==$a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>';
				// month
				echo '
							<select size="1" name="tp_article_month">';
				for($a=1; $a<13; $a++)
					echo '
								<option value="'.$a.'" ' , $month==$a ? ' selected' : '' , '>'.$tpmonths[$a].'</option>  ';
				echo '
							</select>';
				// year
				echo '
							<select size="1" name="tp_article_year">';
				$now=date("Y",time())+1;
				for($a=2004; $a<$now; $a++)
					echo '
								<option value="'.$a.'" ' , $year==$a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>';
				// hours
				echo ' -
							<select size="1" name="tp_article_hour">';
				for($a=0; $a<24;$a++)
					echo '
								<option value="'.$a.'" ' , $hour==$a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>';
				// minutes
				echo ' <b>:</b>
							<select size="1" name="tp_article_minute">';
				for($a=0; $a<60;$a++)
					echo '
								<option value="'.$a.'" ' , $minute==$a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select><br><br>
					</dd>
					<dt>
						<label for="field_name">', $txt['tp-published'], '</label>
					</dt>
					<dd><div class="description" style="line-height: 1.6em; padding: 5px;">
							<b>',$txt['tp-pub_start'],': </b><br>';
				// day
				echo '
							<input name="tp_article_pub_start" type="hidden" value="'.$mg['pub_start'].'">
							<select size="1" name="tp_article_pubstartday">
								<option value="0">' . $txt['tp-notset'] . '</option>';
				$day = !empty($mg['pub_start']) ? date("j",$mg['pub_start']) : 0;
				$month = !empty($mg['pub_start']) ? date("n",$mg['pub_start']) : 0;
				$year = !empty($mg['pub_start']) ? date("Y",$mg['pub_start']) : 0;
				$hour = !empty($mg['pub_start']) ? date("G",$mg['pub_start']) : 0;
				$minute = !empty($mg['pub_start']) ? date("i",$mg['pub_start']) : 0;
				for($a=1; $a<32;$a++)
					echo '
								<option value="'.$a.'" ' , $day==$a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>';
				// month
				echo '
							<select size="1" name="tp_article_pubstartmonth"><option value="0">' . $txt['tp-notset'] . '</option>';
				for($a=1; $a<13; $a++)
					echo '
								<option value="'.$a.'" ' , $month==$a ? ' selected' : '' , '>'.$tpmonths[$a].'</option>  ';
				echo '
							</select>';
				// year
				echo '
							<select size="1" name="tp_article_pubstartyear"><option value="0">' . $txt['tp-notset'] . '</option>';
				$now = date("Y",time())+1;
				for($a = 2004; $a < $now + 2; $a++)
					echo '
								<option value="'.$a.'" ' , $year == $a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>';
				// hours
				echo ' -
							<select size="1" name="tp_article_pubstarthour">';
				for($a=0; $a<24;$a++)
					echo '
								<option value="'.$a.'" ' , $hour == $a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>';
				// minutes
				echo ' <b>:</b>
							<select size="1" name="tp_article_pubstartminute">';
				for($a = 0; $a < 60; $a++)
					echo '
								<option value="'.$a.'" ' , $minute == $a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select><br>';
				// day
				echo '
							<input name="tp_article_pub_end" type="hidden" value="'.$mg['pub_end'].'">
							<b>',$txt['tp-pub_end'],':</b><br><select size="1" name="tp_article_pubendday"><option value="0">' . $txt['tp-notset'] . '</option>';
				$day = !empty($mg['pub_end']) ? date("j",$mg['pub_end']) : 0;
				$month = !empty($mg['pub_end']) ? date("n",$mg['pub_end']) : 0;
				$year = !empty($mg['pub_end']) ? date("Y",$mg['pub_end']) : 0;
				$hour = !empty($mg['pub_end']) ? date("G",$mg['pub_end']) : 0;
				$minute = !empty($mg['pub_end']) ? date("i",$mg['pub_end']) : 0;
				for($a=1; $a<32;$a++)
					echo '
								<option value="'.$a.'" ' , $day == $a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>';
				// month
				echo '
							<select size="1" name="tp_article_pubendmonth"><option value="0">' . $txt['tp-notset'] . '</option>';
				for($a = 1; $a < 13; $a++)
					echo '
								<option value="'.$a.'" ' , $month == $a ? ' selected' : '' , '>'.$tpmonths[$a].'</option>  ';
				echo '
							</select>';
				// year
				echo '
							<select size="1" name="tp_article_pubendyear"><option value="0">' . $txt['tp-notset'] . '</option>';
				$now = date("Y",time())+1;
				for($a = 2004; $a < $now + 2; $a++)
					echo '
								<option value="'.$a.'" ' , $year == $a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>';
				// hours
				echo ' -
							<select size="1" name="tp_article_pubendhour">';
				for($a = 0; $a < 24; $a++)
					echo '
								<option value="'.$a.'" ' , $hour == $a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>';
				// minutes
				echo ' <b>:</b>
							<select size="1" name="tp_article_pubendminute">';
				for($a = 0; $a < 60; $a++)
					echo '
								<option value="'.$a.'" ' , $minute == $a ? ' selected' : '' , '>'.$a.'</option>  ';
				echo '
							</select>
							</div>
					</dd>
					<dt>
						<label for="field_name">', $txt['tp-category'], '</label>
					</dt>
					<dd>
						<div>
							<select size="1" name="tp_article_category">
								<option value="0">'.$txt['tp-none2'].'</option>';
				foreach($context['TPortal']['allcats'] as $cats)
				{
					if($cats['id']<9999 && $cats['id']>0)
						echo '
								<option value="'.$cats['id'].'" ', $cats['id'] == $mg['category'] ? 'selected' : '' ,'>'. str_repeat("-", isset($cats['indent']) ? $cats['indent'] : 0) .' '.$cats['name'].'</option>';
				}
				echo '
							</select>
							<a href="', $scripturl, '?action=tpadmin;sa=categories;cu='.$mg['category'].';sesc=' .$context['session_id']. '">',$txt['tp-editcategory'],'</a>
						</div><br>
					</dd>
					<dt>
						<label for="tp_article_useintro">', $txt['tp-useintro'], '</label>
					</dt>
					<dd>
							<input name="tp_article_useintro" type="radio" value="1" ', $mg['useintro']=='1' ? 'checked' : '' ,'> '.$txt['tp-yes'].'
							<input name="tp_article_useintro" type="radio" value="0" ', $mg['useintro']=='0' ? 'checked' : '' ,'> '.$txt['tp-no'].'<br>
					</dd>
				</dl>
					';
				if($mg['articletype'] == 'php' || $mg['articletype'] == '' || $mg['articletype'] == 'html')
				{
					echo '<div id="tp_article_show_intro"', ($mg['useintro'] == 0) ? 'style="display:none;">' : '>' ,
                        '<div class="font-strong">'.$txt['tp-introtext'].'</div>';
					if($tp_use_wysiwyg > 0 && ($mg['articletype'] == '' || $mg['articletype'] == 'html'))
						TPwysiwyg('tp_article_intro',  $mg['intro'], true, 'qup_tp_article_intro', $tp_use_wysiwyg, false);
					else
						echo '
							<textarea name="tp_article_intro" id="tp_article_intro" rows=5 cols=20 wrap="soft">'.$mg['intro'].'</textarea>';
					echo '
						</div>';
				}
				elseif($mg['articletype'] == 'bbc' || $mg['articletype'] == 'import')
				{
					echo '<div id="tp_article_show_intro"', ($mg['useintro'] == 0) ? 'style="display:none;">' : '>' ,
                    '<div class="font-strong">'.$txt['tp-introtext'].'</div>
					<div>
						<textarea name="tp_article_intro" id="tp_article_intro" rows=5 cols=20 wrap="soft">'. $mg['intro'] .'</textarea>
					</div>
                    </div>';
				}
				echo '<br><hr>
				<dl class="settings">
					<dt>
						<label for="field_name">', $txt['tp-switchmode'], '</label>
					</dt>
					<dd>
							<input align="middle" name="tp_article_type" type="radio" value="html"' , $mg['articletype']=='' || $mg['articletype']=='html' ? ' checked="checked"' : '' ,'> '.$txt['tp-gohtml'] .'<br>
							<input align="middle" name="tp_article_type" type="radio" value="php"' , $mg['articletype']=='php' ? ' checked="checked"' : '' ,'> '.$txt['tp-gophp'] .'<br>
							<input align="middle" name="tp_article_type" type="radio" value="bbc"' , $mg['articletype']=='bbc' ? ' checked="checked"' : '' ,'> '.$txt['tp-gobbc'] .'<br>
							<input align="middle" name="tp_article_type" type="radio" value="import"' , $mg['articletype']=='import' ? ' checked="checked"' : '' ,'> '.$txt['tp-goimport'] .'<br><br>
					</dd>
					<dt>
						<label for="field_name">', $txt['tp-display'], '</label>
					</dt>
					<dd>
							<input name="tp_article_frame" type="radio" value="theme" ' , $mg['frame']=='theme' ? 'checked' : '' , '> '.$txt['tp-useframe'].'<br>
							<input name="tp_article_frame" type="radio" value="title" ' , $mg['frame']=='title' ? 'checked' : '' , '> '.$txt['tp-usetitle'].' <br>
							<input name="tp_article_frame" type="radio" value="none" ' , $mg['frame']=='none' ? 'checked' : '' , '> '.$txt['tp-noframe'].'<br><br>
					</dd>
					<dt>
						<label for="field_name">', $txt['tp-status'], ': <img style="margin:0 1ex;" border="0" src="' .$settings['tp_images_url']. '/TP' , $mg['off']=='1' ? 'red' : 'green' , '.png" alt=""  /></label>
					</dt>
					<dd>
							  <input name="tp_article_off" type="radio" value="1" ' , $mg['off']=='1' ? 'checked' : '' , '> '.$txt['tp-articleoff'].'<br>
							  <input name="tp_article_off" type="radio" value="0" ' , $mg['off']=='0' ? 'checked' : '' , '> '.$txt['tp-articleon'].'<br><br>
					</dd>
					<dt>
						<label for="field_name">', $txt['tp-illustration'], '</label><br>
					</dt>
					<dd>
						<div class="article_icon" style="background: top right url(' , $boardurl , '/tp-files/tp-articles/illustrations/' , !empty($mg['illustration']) ? $mg['illustration'] : 'TPno_illustration.png' , ')no-repeat;"></div>
					</dd>
				</dl>
				<dl class="settings">
					<dt>
						<label for="field_name">', $txt['tp-illustration2'], '</label><br>
						<div><img id="tp-illu" src="' , $boardurl , '/tp-files/tp-articles/illustrations/' , !empty($mg['illustration']) ? $mg['illustration'] : 'TPno_illustration.png' , '" alt="" /></div>
					</dt>
					<dd>
							<select size="10" name="tp_article_illustration" onchange="changeIllu(document.getElementById(\'tp-illu\'), this.value);">
								<option value=""' , $mg['illustration']=='' ? ' selected="selected"' : '' , '>' . $txt['tp-none2'] . '</option>';
			foreach($context['TPortal']['articons']['illustrations'] as $ill)
				echo '<option value="'.$ill['file'].'"' , $ill['file']==$mg['illustration'] ? ' selected="selected"' : '' , '>'.$ill['file'].'</option>';
			echo '
							</select>
							<p>' . $txt['tp-uploadicon'] . ':<br><input type="file" name="tp_article_illupload"></p>
					</dd>
				</dl>
					';
				// set options for an article...
				$opts = array('','date','title','author','linktree','top','cblock','rblock','lblock','bblock','tblock','lbblock','category','catlist','comments','commentallow','commentupshrink','views','rating','ratingallow','nolayer','avatar','inherit','social','nofrontsetting');
				$tmp = explode(',',$mg['options']);
				$options=array();
				foreach($tmp as $tp => $val){
					if(substr($val,0,11)=='rblockwidth')
						$options['rblockwidth']=substr($val,11);
					elseif(substr($val,0,11)=='lblockwidth')
						$options['lblockwidth']=substr($val,11);
					else
						$options[$val]=1;
				}
				echo '
					<div>
						<div class="font-strong">'.$txt['tp-articleoptions'].'</div>
						<div class="article-details">';
				// article details options
				echo '
							<div class="title_bar"><h3 class="titlebg">' . $txt['tp-details'] . '</h3></div>
							<br>
							<dl class="settings">
								<dt>
									<label for="field_name">', $txt['tp-articleoptions1'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[1].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[1]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions2'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[2].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[2]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions12'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[12].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[12]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions13'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[13].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[13]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions3'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[3].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[3]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions4'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[4].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[4]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions14'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[14].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[14]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions15'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[15].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[15]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions5'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[5].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[5]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions16'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[16].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[16]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions17'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[17].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[17]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions18'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[18].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[18]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions19'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[19].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[19]]) ? 'checked' : '' , '><br><br>
								</dd>
							</dl>
								<div class="title_bar">
								<h3 class="titlebg">' . $txt['tp-panels'] . '</h3>
								</div><br>
							<dl class="settings">
								<dt>
									<label for="field_name">', $txt['tp-articleoptions24'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[22].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[22]]) ? 'checked' : '' , '>
								</dd>
							</dl>
						<hr /><br>
							<dl class="settings">
								<dt>
									<label for="field_name">', $txt['tp-articleoptions8'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[8].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[8]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions23'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_lblockwidth" type="text" value="', !empty($options['lblockwidth']) ?  $options['lblockwidth'] : '' ,'"><br>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions7'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[7].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[7]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions22'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_rblockwidth" type="text" value="', !empty($options['rblockwidth']) ?  $options['rblockwidth'] : '' ,'"><br>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions10'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[10].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[10]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions6'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[6].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[6]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions11'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[11].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[11]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions9'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[9].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[9]]) ? 'checked' : '' , '>
								</dd>
							</dl><br>
								<div class="title_bar">
								<h3 class="titlebg">' . $txt['tp-others'] . '</h3>
								</div><br>
							<dl class="settings">
								<dt>
									<label for="field_name">', $txt['tp-articleoptions20'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[20].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[20]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-articleoptions21'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[21].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[21]]) ? 'checked' : '' , '>
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-showsociallinks'], '</label><br>
								</dt>
								<dd>
									<input name="tp_article_options_'.$opts[23].'" type="checkbox" value="'.$mg['id'].'" ' , isset($options[$opts[23]]) ? 'checked' : '' , '>
								</dd>
							</dl>
						<br><hr>
							<dl class="settings">
								<dt>
									<label for="field_name">', $txt['tp-checkall'], '</label><br>
								</dt>
								<dd>
									<input type="checkbox" onclick="invertAll(this, this.form, \'tp_article_options_\');" />
								</dd>
								<dt>
									<label for="field_name">', $txt['tp-chosentheme'], '</label><br>
								</dt>
								<dd>
									<select size="1" name="tp_article_idtheme">';
									echo '			<option value="0" ', $mg['id_theme']==0 ? 'selected' : '' ,'>'.$txt['tp-none-'].'</option>';
									foreach($context['TPthemes'] as $them)
										echo '
														<option value="'.$them['id'].'" ',$them['id']==$mg['id_theme'] ? 'selected' : '' ,'>'.$them['name'].'</option>';
									echo '
								</select>
								</dd>
							</dl>
								<div>
										' , $txt['tp-articleheaders'] , '<br>
										<textarea id="tp_article_intro" name="tp_article_headers" rows="5" cols="40">' , $mg['headers'] , '</textarea>
								</div>
				    </div>
				</div>
					<div style="padding:1%;"><input type="submit" class="button button_submit" value="'.$txt['tp-send'].'" name="'.$txt['tp-send'].'"></div>
				</div>
			</div>
		</div>
	</form>';



    $context['insert_after_template'] =
        '<script>
        $(function () {
                $(\'input[type=radio][name=tp_article_useintro]\').change(function() {
                    switch($(this).val()){
                        case "1":
                            $("#tp_article_show_intro").show()
                            break;
                        case "0":
                            $("#tp_article_show_intro").hide()
                            break;
                        default:
                            $("#tp_article_show_intro").hide()
                }
            });
        });
        </script>';
}

function template_submitsuccess()
{
	global $txt;

	echo '
		<div class="tborder">
                <div class="cat_bar">
				    <h3 class="catbg">'.$txt['tp-submitsuccess2'].'</h3>
                </div>
					<div class="windowbg padding-div" style="text-align: center;">'.$txt['tp-submitsuccess'].'
					<div class="padding-div">&nbsp;</div></div>
		</div>';
}
?>
