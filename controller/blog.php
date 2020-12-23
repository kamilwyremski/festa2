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

if($settings['enable_blog']){
	if(isset($_GET['id']) and $_GET['id']>0 and !empty($_GET['slug'])){

		$blog = blog::show($_GET['id']);
		if($blog){
			if($_GET['slug']!=$blog['slug']){
				header("Location: ".path('blog', $blog['id'], $blog['slug']));
				die('redirect');
			}else{
				$render_variables['blog'] = $blog;
				$settings['seo_title'] = $blog['name'].' - '.$settings['title'];
				if($blog['description']){
					$settings['seo_description'] = $blog['description'];
				}else{
					$settings['seo_description'] = $blog['name'].' - '.$settings['description'];
				}
				if($blog['keywords']){
					$settings['seo_keywords'] = $blog['keywords'];
				}
				if($blog['thumb']){$settings['logo_facebook'] = $blog['thumb'];}
			}
		}else{
			throw new noFoundException();
		}
	}else{
		$render_variables['blogs'] = blog::list(10);
		$settings['seo_title'] = trans('Blog').' - '.$settings['title'];
		$settings['seo_description'] = trans('Blog').' - '.$settings['description'];
	}
}else{
	throw new noFoundException();
}
