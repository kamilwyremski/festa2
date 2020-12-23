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
		if($_POST['action']=='add_blog' and !empty($_POST['name']) and checkToken('admin_add_blog')){
      $id = blog::add($_POST);
			header('Location: ?controller=blog&id='.$id);
			die('redirect');
		}elseif($_POST['action']=='edit_blog' and isset($_POST['id']) and $_POST['id']>0 and !empty($_POST['name']) and checkToken('admin_edit_blog')){
			blog::edit($_POST['id'],$_POST);
			$render_variables['alert_success'][] = trans('Changes have been saved');
		}
	}

	$title = trans('Blog').' - '.$title_default;

	if(isset($_GET['id']) and $_GET['id']>0){
		$blog = blog::show($_GET['id']);
		if($blog!=''){
			$title = $blog['name'].' - '.$title;
			$render_variables['blog'] = $blog;
		}
	}

}
