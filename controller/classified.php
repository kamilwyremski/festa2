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

if(isset($_GET['activate']) and !empty($_GET['code'])){
	classified::activateCode($_GET['code']);
}

if(!empty($_GET['code'])){$code = $_GET['code'];}else{$code = '';}

if(isset($_GET['id']) and $_GET['id']>0 and classified::checkActive($_GET['id'],$code)){

	if(isset($_GET['status'])){
		if($_GET['status']=='OK'){
			$render_variables['alert_success'][] = trans('Payment correct');
		}elseif($_GET['status']=='FAIL'){
			$render_variables['alert_danger'][] = trans('Payment error. Please contact with administrator');
		}
	}

	if($settings['show_contact_form_classified'] and isset($_POST['action']) and $_POST['action']=='send_message' and !empty($_POST['name']) and (!empty($_POST['email']) or $user->getId()) and !empty($_POST['message']) and (isset($_POST['captcha']) or isset($_POST['recaptcha_response'])) and (isset($_POST['rules']) or $user->getId())){

		if(!settings::checkCaptcha($_POST)){
			$error['captcha'] = trans('Invalid captcha code.');
		}elseif(!$user->getId() and !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$error['email'] = trans('Incorrect e-mail address');
		}
		if(settings::checkEmailBlackList($_POST['email']) or settings::checkIpBlackList(getClientIp())){
			$error = true;
		}

		if(isset($error)){
			$render_variables['error'] = $error;
			$render_variables['alert_danger'][] = trans('The message was not sent');
			$render_variables['input'] = ['name'=>$_POST['name'], 'email'=>$_POST['email'], 'message'=>$_POST['message']];
		}else{
			if($user->getId()){
				$email = $user->email;
			}else{
				$email = $_POST['email'];
			}
			$classified = classified::show($_GET['id']);
			if(mail::send('classified',$classified['email'],['name'=>$_POST['name'], 'email'=>$email, 'message'=>$_POST['message'], 'classified_name'=>$classified['name'], 'classified_url'=>path('classified',$classified['id'],$classified['slug']), 'user_id'=>$user->getId()])){
				$render_variables['alert_success'][] = trans('The message was correctly sent');
			}else{
				$render_variables['error'] = $error;
				$render_variables['alert_danger'][] = trans('The message was not sent');
				$render_variables['input'] = ['name'=>$_POST['name'], 'email'=>$_POST['email'], 'message'=>$_POST['message']];
			}
		}
		$render_variables['showContactTab'] = true;
	}elseif(isset($_POST['action']) and $_POST['action']=='clipboard_add' and checkToken('clipboard_add')){
		if($user->getId()){
			clipboard::add($_GET['id']);
			$render_variables['alert_success'][] = trans('Classified added to clipboard');
		}else{
			$render_variables['alert_danger'][] = trans('You must be logged in to post ad to clipboard');
		}
	}elseif(isset($_POST['action']) and $_POST['action']=='clipboard_remove' and checkToken('clipboard_remove')){
		clipboard::remove($_GET['id']);
		$render_variables['alert_success'][] = trans('Classified remove from clipboard');

	}elseif(isset($_POST['action']) and $_POST['action']=='activate_classified' and classified::countCost($_GET['id'])['total']==0 and checkToken('activate_classified')){
		classified::activate($_GET['id']);
		$render_variables['alert_success'][] = trans('The classified has been correctly activated on the site');
	}

	$classified = classified::show($_GET['id'], 'classified');

	if($_GET['slug']!=$classified['slug']){
		header("Location: ".path('classified', $classified['id'], $classified['slug']));
		die('redirect');
	}

	if($settings['show_similar_classifieds']){
		$render_variables['classifieds'] = classified::listSimilar($classified,$settings['limit_similar']);
	}

	$email_parts = explode('@', $classified['email']);
	$classified['email_part_0'] = $email_parts[0];
	$classified['email_part_1'] = $email_parts[1];

	if(!$classified['active']){
		$render_variables['classified_cost'] = classified::countCost($classified['id']);
	}

	if(!empty($classified['photos'])){
		$settings['logo_facebook'] = $settings['base_url'].'/upload/photos/'.$classified['photos'][0]['folder'].$classified['photos'][0]['url'];
	}

	$settings['seo_title'] = $classified['name']." - ".$settings['title'];
	$settings['seo_description'] = substr(trim(preg_replace('/\s\s+/', ' ', strip_tags($classified['description']))),0,200);

	$render_variables['classified'] = $classified;

}else{
	throw new noFoundException();
}
