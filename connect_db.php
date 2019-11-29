<?php

$ini = parse_ini_file('app.ini');
$pdo = null;
$host = $ini['db_host'];
$db = $ini['db_name'];
$user = $ini['db_user'];
$pass = $ini['db_password'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

/**
 * Queries Part
 */
function getRandomPage() {
	global $pdo;
	if ($pdo) {
		$page = $pdo->query('SELECT * FROM pages ORDER BY RAND() LIMIT 1;')->fetch();
		if ($page) {
			return $page;
		} else {
			return null;
		}
	} else {
		echo "PDO's busted, fix it";
		exit;
	}
}

