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

if($admin->is_logged()){

	if(!_ADMIN_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='remove_blog' and isset($_POST['id']) and $_POST['id']>0 and checkToken('admin_remove_blog')){
		  blog::remove($_POST['id']);
			$render_variables['alert_danger'][] = trans('Successfully deleted');
		}
	}

	$render_variables['blogs'] = blog::list(100);

	$title = trans('Blog').' - '.$title_default;

}
