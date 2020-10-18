<?php
/************************************************************************
 * The script of accommodation FESTA2
 * Copyright (c) 2018 - 2020 by IT Works Better https://itworksbetter.net
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

if(!empty($_GET['slug'])){
	$profile = user::showProfile($_GET['slug']);

	if($profile){
		$settings['seo_title'] = trans('Profile of').': '.$profile['username'].' - '.$settings['title'];
		$settings['seo_description'] = trans('Profile of').': '.$profile['username'].' - '.$settings['description'];
		$render_variables['profile'] = $profile;

		if($settings['show_contact_form_profile'] and isset($_POST['action']) and $_POST['action']=='send_message' and !empty($_POST['name']) and (!empty($_POST['email']) or $user->getId()) and !empty($_POST['message']) and (isset($_POST['captcha']) or isset($_POST['g-recaptcha-response']) and (isset($_POST['rules'])) or $user->getId())){

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
			if(mail::send('profile',$profile['email'],['name'=>$_POST['name'], 'email'=>$email, 'message'=>$_POST['message'], 'username'=>$profile['username']])){
				$render_variables['alert_success'][] = trans('The message was correctly sent');
			}else{
				$render_variables['alert_danger'][] = trans('The message was not sent');
			}
		}
	}
	}else{
		throw new noFoundException();
	}
}else{
	throw new noFoundException();
}
