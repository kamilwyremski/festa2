<?php
/************************************************************************
 * The script of accommodation FESTA2
 * Copyright (c) 2018 - 2023 by IT Works Better https://itworksbetter.net
 * Project by Kamil Wyremski https://wyremski.pl
 *
 * All right reserved
 *
 * *********************************************************************
 * THIS SOFTWARE IS LICENSED - YOU CAN MODIFY THESE FILES
 * BUT YOU CAN NOT REMOVE OF ORIGINAL COMMENTS!
 * ACCORDING TO THE LICENSE YOU CAN USE THE SCRIPT ON ONE DOMAIN. DETECTION
 * COPY SCRIPT WILL RESULT IN A HIGH FINANCIAL PENALTY AND WITHDRAWAL
 * LICENSE THE SCRIPT
 * *********************************************************************/

if(!isset($settings['base_url'])){
	die('Access denied!');
}

if(!empty($_GET['slug']) and $controller=='add'){
	throw new noFoundException();
}

if($settings['add_classifieds_not_logged'] or $user->getId()){

	if(!empty($_GET['code'])){$code = $_GET['code'];}else{$code = '';}
	if(isset($_POST['action']) and
		($user->getId() or isset($_POST['rules']) or $_POST['action']=='edit') and
		!empty($_POST['name']) and
		!empty($_POST['email']) and filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) and
		!empty($_POST['session_code']) and sessionClassified::check($_POST['session_code']) and
		(!empty($_POST['phone'] or !$settings['required_phone'])) and
		(!empty($_POST['address'] or !$settings['required_address'])) and
		((!empty($_POST['category_id']) and !empty(category::show($_POST['category_id'])) or !$settings['required_category'] or count(category::list())==0)) and
		((!empty($_POST['location_id']) and !empty(location::showById($_POST['location_id'])) or !$settings['required_location'] or count(location::list())==0)) and
		((!empty($_POST['state_id']) and !empty(state::showById($_POST['state_id'])) or !$settings['required_state'] or count(state::list())==0))
		){

		if($_POST['action']=='add'){
			if(settings::checkEmailBlackList($_POST['email']) or settings::checkIpBlackList(getClientIp()) or slug(trim(settings::checkWordsBlackList(strip_tags($_POST['name']))))==''){
				$render_variables['alert_danger'][] = trans('The classified could not be added');
			}else{
				$classified = classified::add($_POST);
				if($classified['active']){
					$_SESSION['flash'] = 'classified_activated';
				}
				if($user->getId()){
					header("Location: ".path('classified',$classified['id'],$classified['slug']));
				}else{
					header("Location: ".path('classified',$classified['id'],$classified['slug']).'?code='.$classified['code']);
				}
				die('redirect');
			}
		}elseif($_POST['action']=='edit' and isset($_GET['id']) and $_GET['id']>0 and classified::checkPermissions($_GET['id'],$code)){
			if(slug(trim(settings::checkWordsBlackList(strip_tags($_POST['name']))))==''){
				$render_variables['alert_danger'][] = trans('The classified could not be added');
			}else{
				$classified = classified::edit($_GET['id'],$_POST,true);
				$_SESSION['flash'] = 'classified_saved';
				if($user->getId()){
					header("Location: ".path('classified',$classified['id'],$classified['slug']));
				}else{
					header("Location: ".path('classified',$classified['id'],$classified['slug']).'?code='.$code);
				}
				die('redirect');
			}
		}
	}elseif(isset($_POST['action']) and $_POST['action']=='remove_classified' and isset($_GET['id']) and $_GET['id']>0 and isset($_POST['code']) and checkToken('remove_classified')){
		if(classified::checkPermissions($_GET['id'],$_POST['code'])){
			classified::remove($_GET['id']);
			$_SESSION['flash'] = 'classified_deleted';
			header("Location: ".path('add'));
			die('redirect');
		}
	}elseif(isset($_GET['add_similar']) and $_GET['add_similar']>0 and classified::checkPermissions($_GET['add_similar'])){
		$render_variables['classified'] = classified::show($_GET['add_similar'], 'add_similar');
	}

	if(!$user->logged_in){
		$render_variables['alert_danger'][] = trans('You are not logged in. Log in to fully enjoy functionality of website!');
	}

	$render_variables['session_code'] = sessionClassified::new();

	$render_variables['states'] = state::listAll();
	$render_variables['locations'] = location::list();
	$render_variables['durations'] = duration::list();

	$settings['seo_title'] = trans('Add classified').' - '.$settings['title'];
	$settings['seo_description'] = trans('Add classified').' - '.$settings['description'];
}else{
	header("Location: ".path('login')."?redirect=".path('add'));
	die('redirect');
}
