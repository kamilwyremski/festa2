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

if (!isset($settings['base_url'])) {
	die('Access denied!');
}

if (!empty($_GET['slug']) && $controller == 'add') {
	throw new noFoundException();
}

if ($settings['add_classifieds_not_logged'] || $user->getId()) {

	if (!empty($_GET['code'])) {
		$code = $_GET['code'];
	} else {
		$code = '';
	}
	if (
		isset($_POST['action']) &&
		($user->getId() || isset($_POST['rules']) || $_POST['action'] == 'edit') &&
		!empty($_POST['name']) &&
		!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) &&
		!empty($_POST['session_code']) && sessionClassified::check($_POST['session_code']) &&
		(!empty($_POST['phone'] || !$settings['required_phone'])) &&
		(!empty($_POST['address'] || !$settings['required_address'])) &&
		((!empty($_POST['category_id']) && !empty(category::show($_POST['category_id'])) || !$settings['required_category'] || count(category::list()) == 0)) &&
		((!empty($_POST['location_id']) && !empty(location::showById($_POST['location_id'])) || !$settings['required_location'] || count(location::list()) == 0)) &&
		((!empty($_POST['state_id']) && !empty(state::showById($_POST['state_id'])) || !$settings['required_state'] || count(state::list()) == 0))
	) {

		if ($_POST['action'] == 'add') {
			if (settings::checkEmailBlackList($_POST['email']) || settings::checkIpBlackList(getClientIp()) || slug(trim(settings::checkWordsBlackList(strip_tags($_POST['name'])))) == '') {
				$render_variables['alert_danger'][] = trans('The classified could not be added');
			} else {
				$classified = classified::add($_POST);
				if ($classified['active']) {
					$_SESSION['flash'] = 'classified_activated';
				}
				if ($user->getId()) {
					header("Location: " . path('classified', $classified['id'], $classified['slug']));
				} else {
					header("Location: " . path('classified', $classified['id'], $classified['slug']) . '?code=' . $classified['code']);
				}
				die('redirect');
			}
		} elseif ($_POST['action'] == 'edit' && isset($_GET['id']) && $_GET['id'] > 0 && classified::checkPermissions($_GET['id'], $code)) {
			if (slug(trim(settings::checkWordsBlackList(strip_tags($_POST['name'])))) == '') {
				$render_variables['alert_danger'][] = trans('The classified could not be added');
			} else {
				$classified = classified::edit($_GET['id'], $_POST, true);
				$_SESSION['flash'] = 'classified_saved';
				if ($user->getId()) {
					header("Location: " . path('classified', $classified['id'], $classified['slug']));
				} else {
					header("Location: " . path('classified', $classified['id'], $classified['slug']) . '?code=' . $code);
				}
				die('redirect');
			}
		}
	} elseif (isset($_POST['action']) && $_POST['action'] == 'remove_classified' && isset($_GET['id']) && $_GET['id'] > 0 && isset($_POST['code']) && checkToken('remove_classified')) {
		if (classified::checkPermissions($_GET['id'], $_POST['code'])) {
			classified::remove($_GET['id']);
			$_SESSION['flash'] = 'classified_deleted';
			header("Location: " . path('add'));
			die('redirect');
		}
	} elseif (isset($_GET['add_similar']) && $_GET['add_similar'] > 0 && classified::checkPermissions($_GET['add_similar'])) {
		$render_variables['classified'] = classified::show($_GET['add_similar'], 'add_similar');
	}

	if (!$user->logged_in) {
		$render_variables['alert_danger'][] = trans('You are not logged in. Log in to fully enjoy functionality of website!');
	}

	$render_variables['session_code'] = sessionClassified::new();

	$render_variables['states'] = state::listAll();
	$render_variables['locations'] = location::list();
	$render_variables['durations'] = duration::list();

	$settings['seo_title'] = trans('Add classified') . ' - ' . $settings['title'];
	$settings['seo_description'] = trans('Add classified') . ' - ' . $settings['description'];
} else {
	header("Location: " . path('login') . "?redirect=" . path('add'));
	die('redirect');
}
