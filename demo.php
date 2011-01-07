<?
function demo_header_print($title='IDS Data Sharing demo') {
	?><html>
		<head>
			<title><? echo $title ?></title>
		</head>
		<body><div id="container">
	<?
}

function demo_footer_print() {
	lg_print(); ?>
		</div></body>
	</html>
	<?
}
?>
