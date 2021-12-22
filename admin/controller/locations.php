<?php
/************************************************************************
 * The script of accommodation FESTA2
 * Copyright (c) 2018 - 2022 by IT Works Better https://itworksbetter.net
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
		if($_POST['action']=='add_location' and !empty($_POST['name']) and checkToken('admin_add_location')){

      location::add($_POST);
			$render_variables['alert_success'][] = trans('Successfully added new location').' '.strip_tags($_POST['name']);

		}elseif($_POST['action']=='edit_location' and isset($_POST['id']) and $_POST['id']>0 and !empty($_POST['name']) and checkToken('admin_edit_location')){

      location::edit($_POST['id'],$_POST);
			$render_variables['alert_success'][] = trans('Changes have been saved');

		}elseif($_POST['action']=='remove_location' and isset($_POST['id']) and $_POST['id']>0 and checkToken('admin_remove_location')){

      location::remove($_POST['id']);
			$render_variables['alert_danger'][] = trans('Successfully deleted');

		}
	}

	$render_variables['locations'] = location::list();

	$title = trans('Locations').' - '.$title_default;

}
