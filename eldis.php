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
function getJson($destination) {
	global $eldis_username, $eldis_password;

	if($destination instanceof EldisQuery || $destination instanceof EldisCategoryQuery) {
		$url = $destination->get_url();
	} else {
		$url = $destination;
	}

	lg("Getting JSON from URL: $url");

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
	$q = new EldisQuery();
	$q->limit = $max_records;
	$q->query = $search_term;
	$q->size = 'full';
	return getJson($q);
}
	
function eldis_get_title($doc) {
	return $doc->title;
}

function eldis_get_url($doc) {
	return $doc->document_urls[0]->locationUrl;
}

function eldis_get_base_categories() {
	lg("Getting base categories...");
	$q = new EldisCategoryQuery();
	$q->name = 'theme';
	$json = getJson($q);
	lg("Fetched: " . sizeof($json));
	$categories = array();
	foreach($json as $j) {
		$categories[] = new EldisCategory($j);
	}
	return $categories;
}

class EldisCategory {
	private $json;
	function __construct($json) {
		$this->json = $json;
	}
	public function get_name() {
		return $this->json->name;
	}
	public function get_id() {
		return $this->json->id;
	}
}

class EldisCategoryQuery {
	const BASE_URL = 'http://api.ids.ac.uk/searchapi/index.cfm/category';

	public $id;
	public $name;
	public $format = 'json';

	public function get_url() {
		$url = '';
		
		if($this->id !== NULL) $url .= '/id/' . ($this->id + 0);
		if($this->name !== NULL) $url .= '/name/' . $this->name;

		$format = $this->format;
		return self::BASE_URL . $url . '/category.' . $format;
	}
}

class EldisQuery {
	const BASE_URL = 'http://api.ids.ac.uk/searchapi/index.cfm/search/object/document';

	public $query;
	public $author;
	public $publisher;
	public $theme;
	public $pubdate;
	public $limit;
	public $startPosition;
	public $format = 'json';
	public $size = 'short';

	public function get_url() {
		$url = '';

		if($this->query !== NULL) {
			$url .= '/query/';
			if(is_array($this->query)) {
				implode('/', $search_term);
			} else {
				$url .= $this->query;
			}
		}
		if($this->author !== NULL) $url .= '/author/' . $this->author;
		if($this->publisher !== NULL) $url .= '/publisher/' . $this->publisher;
		if($this->theme !== NULL) $url .= '/theme/' . ($this->theme + 0);
		if($this->pubDate !== NULL) $url .= '/pubDate/' . ($this->pubDate + 0);
		if($this->limit !== NULL) $url .= '/noRecords/' . ($this->limit + 0);
		if($this->start !== NULL) $url .= '/startPosition/' . ($this->start + 0);

		$size = $this->size;
		$format = $this->format;
		return self::BASE_URL . $url . "/$size.$format";
	}
}

?>
