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

function sitemap_generator(){
	global $settings, $db, $links;

	$sitemapFile = dirname(__FILE__)."/../sitemap.xml";

	chmod($sitemapFile, 0777);

	$sitemap_links = [];

	$sitemap_links[] = ['priority'=>'1','url'=>$settings['base_url']];

	foreach($links as $link => $url){
		if(!(($link=='blog' and !$settings['enable_blog']) or $link=='blog' or $link=='my_classifieds' or $link=='edit' or $link=='settings' or $link=='profile')){
			$sitemap_links[] = ['priority'=>'0.5','url'=>$settings['base_url'].'/'.$url];
		}
	}

	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'classified WHERE active=1 ORDER BY promoted desc, id desc');
	while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
		$sitemap_links[] = ['priority'=>'0.8','url'=>path('classified',$row['id'],$row['slug'])];
	}

	if($settings['enable_blog']){
		$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'blog ORDER BY date desc');
		while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$sitemap_links[] = ['priority'=>'0.6','url'=>path('blog',$row['id'],$row['slug'])];
		}
	}

	$fh = fopen($sitemapFile, 'w');

	$html = '<?xml version="1.0" encoding="UTF-8"?>
	<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">';
	fwrite($fh, $html);

	foreach($sitemap_links as $row){
		$entry = "\n";
		$entry .= '<url>';
		$entry .= "\n";
		$entry .= '  <loc>'.$row['url'].'</loc>';
		$entry .= "\n";
		$entry .= '  <changefreq>daily</changefreq>';
		$entry .= "\n";
		$entry .= '  <priority>'.$row['priority'].'</priority>';
		$entry .= "\n";
		$entry .= '</url>';
		fwrite($fh, $entry);
	}

	$html = '
	</urlset>';
	fwrite($fh, $html);
	fclose($fh);

	chmod($sitemapFile, 0644);
}
