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

session_start();

require_once('../config/config.php');

$user = new user();

if(isset($_POST['action'])){
	if($_POST['action']=='getCoordinates' and !empty($_POST['address'])){
		echo json_encode(getCoordinates($_POST['address']));
	}elseif($_POST['action']=='get_categories_and_options' and isset($_POST['category_id']) and $_POST['category_id']>=0){
		if(!empty($_POST['load_options'])){
			echo(json_encode([
				'categories'=>category::list($_POST['category_id']),
				'options'=>option::list($_POST['category_id'],'add')
			]));
		}else{
			echo(json_encode(['categories'=>category::list($_POST['category_id'])]));
		}
	}elseif($_POST['action']=='add_photo' and ($settings['add_classifieds_not_logged'] or $user->logged_in) and $settings['photo_add'] and isset($_FILES["file"]["type"])){
		echo(json_encode(photo::add()));
	}
}elseif(isset($_GET['action'])){
	if($_GET['action']=='classifieds_sugested_keywords' and !empty($_GET['keywords'])){
		echo(json_encode(classified::listNames($_GET['keywords'])));
	}
}
