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

if($admin->is_logged()){

	if(isset($_GET['state_id']) and $_GET['state_id']>0 and is_numeric($_GET['state_id'])){
		$state = state::showById($_GET['state_id']);
		$render_variables['state'] = $state;
		$state_id = $state['id'];
	}else{
		$state_id = 0;
	}

	if(!_ADMIN_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='add_state' and !empty($_POST['name']) and checkToken('admin_add_state')){

      state::add($_POST, $state_id);
			$render_variables['alert_success'][] = trans('Successfully added new state').' '.strip_tags($_POST['name']);

		}elseif($_POST['action']=='edit_state' and isset($_POST['id']) and $_POST['id']>0 and !empty($_POST['name']) and checkToken('admin_edit_state')){

      state::edit($_POST['id'], $_POST);
			$render_variables['alert_success'][] = trans('Changes have been saved');

		}elseif($_POST['action']=='remove_state' and isset($_POST['id']) and $_POST['id']>0 and checkToken('admin_remove_state')){

      state::remove($_POST['id']);
			$render_variables['alert_danger'][] = trans('Successfully deleted');

		}
	}

	$render_variables['states'] = state::list($state_id);

	$title = trans('States').' - '.$title_default;

}
