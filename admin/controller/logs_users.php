<?php
/************************************************************************
 * The script of accommodation FESTA2
 * Copyright (c) 2018 - 2021 by IT Works Better https://itworksbetter.net
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
		if($_POST['action']=='remove_logs' and !empty($_POST['type']) and checkToken('admin_remove_logs')){
			if($_POST['type']=='only_removed'){
        logsUser::removeWithoutUsers();
				header('Location: ?controller=logs_users');
				die('redirect');
			}elseif($_POST['type']=='all'){
				logsUser::removeAll();
				header('Location: ?controller=logs_users');
				die('redirect');
			}
		}
	}

  $render_variables['logs_users'] = logsUser::list();

	$title = trans('Logs users').' - '.$title_default;
}
