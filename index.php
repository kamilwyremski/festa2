<?php
/************************************************************************
 * The script of accommodation FESTA 2.3.4
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

session_start();

header('Content-Type: text/html; charset=utf-8');
header('X-Frame-Options: SAMEORIGIN');

require_once('config/config.php');

$loader = new \Twig\Loader\FilesystemLoader('views/' . $settings['template']);
$twig = new \Twig\Environment($loader, [
	'cache' => 'tmp',
]);

$twig->addFilter(new \Twig\TwigFilter('trans', 'trans'));
$twig->addFilter(new \Twig\TwigFilter('showCurrency', 'showCurrency'));
$twig->addFunction(new \Twig\TwigFunction('path', 'path'));
$twig->addFunction(new \Twig\TwigFunction('generateToken', 'generateToken'));

$render_variables = [];
$user = new user();

$controller = 'index';

class noFoundException extends Exception
{
}

try {

	if (!empty($_GET['path'])) {

		if (substr($_GET['path'], -1, 1) == '/') {
			$_GET['path'] = substr($_GET['path'], 0, strlen($_GET['path']) - 1);
		}

		$controller = '';

		$pos_slash = strpos($_GET['path'], '/');
		if ($pos_slash) {
			$controller = array_search(substr($_GET['path'], 0, $pos_slash), $links);
		} else {
			$controller = array_search($_GET['path'], $links);
		}

		if (($pos_slash or !$controller) and substr($_GET['path'], $pos_slash + 1, strlen($_GET['path']))) {
			if (!$controller) {
				$_GET['slug'] = substr($_GET['path'], $pos_slash, strlen($_GET['path']));
			} else {
				$_GET['slug'] = substr($_GET['path'], $pos_slash + 1, strlen($_GET['path']));
			}
			$pos_dash = strpos($_GET['slug'], '-');
			if ($pos_dash) {
				$_GET['id'] = intval(substr($_GET['slug'], 0, $pos_dash));
				$_GET['slug'] = substr($_GET['slug'], $pos_dash + 1, strlen($_GET['slug']));
				if (!$controller and intval($_GET['id']) > 0 and $_GET['slug']) {
					$controller = 'classified';
				}
			}
		}

		if (
			!$controller and (
				substr($_GET['path'], 0, strlen(_PREFIX_STATE_)) == _PREFIX_STATE_ or
				substr($_GET['path'], 0, strlen(_PREFIX_CATEGORY_)) == _PREFIX_CATEGORY_ or
				substr($_GET['path'], 0, strlen(_PREFIX_LOCATION_)) == _PREFIX_LOCATION_)
		) {
			$controller = 'classifieds';
		}

		if (!$controller) {
			throw new noFoundException();
		}
	}
	require_once('controller/' . $controller . '.php');
} catch (noFoundException $e) {
	require_once('controller/404.php');
}

$newsletter_array = newsletter::check();
$render_variables['newsletter_info'] = $newsletter_array['newsletter_info'];
$render_variables['newsletter_input'] = $newsletter_array['newsletter_input'];
$render_variables['newsletter_error'] = $newsletter_array['newsletter_error'];

$settings['logo_facebook'] = getFullUrl($settings['logo_facebook']);

checkInfo();

echo $twig->render($controller . '.html', array_merge($render_variables, ['settings' => $settings, 'user' => $user->user_data, 'controller' => $controller, 'get' => $_GET]));
