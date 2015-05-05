<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="RPH Sample Web Site" />
		<meta name="keywords" content="PC" />
		<meta name="author" content="PC" />

		<script src="javascript/jquery-1.10.2.min.js" type="text/javascript"></script>
		<script src="javascript/jquery.blockUI.js" type="text/javascript"></script>
		<script src="javascript/rph_site.js" type="text/javascript"></script>

		<script src="javascript/highcharts.js" type="text/javascript"></script>
		<script src="javascript/rph_graphs.js" type="text/javascript"></script>

		<link rel="stylesheet" href="css/styles.css" type="text/css"/>

		<title>Sample Web Site</title>
	</head>

	<body>
	<div id="body_wrapper">
<?php include('includes/functions.php'); ?>

		<!-- HEADER -->
<?php include('template/header.php'); ?>

		<!-- NAVIGATION -->
<?php include('template/nav.php'); ?>

		<!-- CONTENT -->
<?php include('template/content.php'); ?>

		<!-- FOOTER -->
<?php include('template/footer.php'); ?>

	</div>
	</body>

</html>
