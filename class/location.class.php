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

class location {

	public static function add(array $data){
		global $db;
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'location`(`slug`, `name`, `position`) VALUES (:slug,:name,:position)');
		$sth->bindValue(':slug', slug($data['name']), PDO::PARAM_STR);
		$sth->bindValue(':name', trim($data['name']), PDO::PARAM_STR);
		$sth->bindValue(':position', getPosition('location'), PDO::PARAM_INT);
		$sth->execute();
	}

	public static function edit(int $id, array $data){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'location` SET slug=:slug, name=:name WHERE id=:id limit 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->bindValue(':slug', slug($data['name']), PDO::PARAM_STR);
		$sth->bindValue(':name', trim($data['name']), PDO::PARAM_STR);
		$sth->execute();
	}

	public static function remove(int $id){
		global $db;
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'location` WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}

	public static function showBySlug(string $slug){
		global $db;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'location WHERE slug=:slug LIMIT 1');
		$sth->bindValue(':slug', $slug, PDO::PARAM_STR);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	public static function showById(int $id = 0){
		global $db;
		$location = '';
		if($id > 0){
			$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'location WHERE id=:id LIMIT 1');
			$sth->bindValue(':id', $id, PDO::PARAM_INT);
			$sth->execute();
			$location = $sth->fetch(PDO::FETCH_ASSOC);
		}
		return $location;
	}

	public static function list(){
		global $db;
		$locations = [];
		$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'location ORDER BY position');
		foreach($sth as $row){$locations[$row['id']] = $row;}
		return $locations;
	}
}
