<?php 

	import_request_variables('g');

	$content = file_get_contents($feedUrl);
	$feed = new SimpleXmlElement($content);

	header("Cache-Control: max-age=".strtotime('-1 hours'));
    header("Expires: " . gmdate('D, d M Y H:i:s', strtotime('-1 hours')));
	header('Content-type: application/json');
	echo json_encode($feed);
	exit;

?>