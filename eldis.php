<?

require_once('.conf.php');

/*
 * LOGGING METHODS
 */
$log = array();
function lg($message) {
	global $log;
	$log[] = $message;
}
lg("Logs initialised");
function lg_print() {
	global $log;
	if(isset($_GET['show_log']) && $_GET['show_log']) {
		?><div id="log">
			<h2>Log</h2>
			<p>Log entries: <? echo sizeof($log); ?></p>
			<? foreach($log as $m) { echo "<p>$m</p>"; } ?>
		</div><?
	}
}

/*
 * JSON METHODS
 */
function getJson($url) {
	global $eldis_username, $eldis_password;
	$context = stream_context_create(array(
	    'http' => array(
		'header'  => "Authorization: Basic " . base64_encode("$eldis_username:$eldis_password")
	    )
	));
	$json = file_get_contents($url, false, $context);
	lg($json);
	$json_output = json_decode($json);
	return $json_output;
}

/*
 * ELDIS METHODS
 */
function eldis_get_search_term($prefix='', $get_var_name='s') {
	$get_var_name = $prefix . $get_var_name;
	if(isset($_GET[$get_var_name])) {
		$search_term = $_GET[$get_var_name];
		$terms = preg_split ("/\s+/", $search_term);
		if(sizeof($terms) > 1) $search_term = $terms;
	} else {
		$search_term = 'kampala';
	}
	lg("Search term: $search_term");
	return $search_term;
}

function eldis_search($search_term, $max_records=NULL) {
	$url = 'http://api.ids.ac.uk/searchapi/index.cfm/search/object/document/';
	
	if(is_array($search_term)) $search_term = implode('/', $search_term);
	$url .= 'query/' . $search_term;
	if($max_records) $url .= '/noRecords/' . $max_records;
	$url .= '/full.json';
	lg("Search url: $url");
	return getJson($url);
}

function eldis_get_title($doc) {
	return $doc->title;
}

function eldis_get_url($doc) {
	return $doc->document_urls[0]->locationUrl;
}

?>
