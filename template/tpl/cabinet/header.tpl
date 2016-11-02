<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?= SITE_NAME ?></title>
	<link rel="shortcut icon" href="template/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/template/css/reset.css">
	<link rel="stylesheet" type="text/css" href="/template/css/main.css">
	<link rel="stylesheet" type="text/css" href="/template/css/cabinet.css">
	<script type="text/javascript" src="/template/js/jquery.js"></script>
</head>
<body>

<div class="header">


<?php
	include "menu.top.tpl";
	if (is_user()) include "menu.cabinet.tpl";
?>

</div>

<div class="main">
<div class="cabinet">
