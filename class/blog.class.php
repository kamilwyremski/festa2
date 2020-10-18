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

class blog {

	public static function list(int $limit=10){
		global $db;
		$blogs = [];
		$sth = $db->prepare('SELECT SQL_CALC_FOUND_ROWS * FROM '._DB_PREFIX_.'blog ORDER BY date desc LIMIT :limit_from, :limit_to');
		$sth->bindValue(':limit_from', paginationPageFrom($limit), PDO::PARAM_INT);
		$sth->bindValue(':limit_to', $limit, PDO::PARAM_INT);
		$sth->execute();
		while($row = $sth->fetch(PDO::FETCH_ASSOC)) {$blogs[] = $row;}
		generatePagination($limit);
		return $blogs;
	}

	public static function show(int $id){
		global $db;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'blog WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	public static function add(array $data){
		global $db;
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'blog`(`name`, `slug`) VALUES (:name, :slug)');
		$sth->bindValue(':name', trim($data['name']), PDO::PARAM_STR);
		$sth->bindValue(':slug', slug($data['name']), PDO::PARAM_STR);
		$sth->execute();
		$id = $db->lastInsertId();
		static::edit($id, $data);
		return $id;
	}

	public static function edit(int $id,array $data){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'blog` SET `name`=:name, `slug`=:slug, `thumb`=:thumb, `content`=:content, `lid`=:lid, `keywords`=:keywords, `description`=:description WHERE id=:id limit 1');
		$sth->bindValue(':name', trim($data['name']), PDO::PARAM_STR);
		$sth->bindValue(':slug', slug($data['name']), PDO::PARAM_STR);
		$sth->bindValue(':thumb', $data['thumb'], PDO::PARAM_STR);
		$sth->bindValue(':content', $data['content'], PDO::PARAM_STR);
		$sth->bindValue(':lid', $data['lid'], PDO::PARAM_STR);
		$sth->bindValue(':keywords', $data['keywords'], PDO::PARAM_STR);
		$sth->bindValue(':description', $data['description'], PDO::PARAM_STR);
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}

	public static function remove(int $id){
		global $db;
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'blog` WHERE id=:id limit 1');
		$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
		$sth->execute();
	}

}
