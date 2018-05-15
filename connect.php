<?php
$dbh = new PDO('mysql:host=localhost;dbname=severyuhina', "severyuhina", "neto1715");

$res = $dbh->query("SET NAMES 'utf-8'");
$res = $dbh->query("SET NAMES utf8 COLLATE utf8_general_ci");
$res = $dbh->query("SET time_zone = '+00:00'");
$res = $dbh->query("SET foreign_key_checks = 0");
