<?
require_once('.conf.php');
require_once('demo.php');
require_once('eldis.php');

demo_header_print();
?>
		<div>
			<h2>ELDIS search</h2>
			<?
				$search_term = eldis_get_search_term();
				$search_term_display = is_array($search_term) ? implode(',', $search_term) : $search_term;
				echo "<p>Search term(s): $search_term_display</p>";

				$json = eldis_search($search_term, 10);
				echo '<p>Results: ' . sizeof($json) . '</p>';
				echo '<ol>';
				foreach($json as $doc) {
					$docUrl = eldis_get_url($doc);
					$docTitle = eldis_get_title($doc);
					echo "<li><a href='$docUrl'>$docTitle</a></li>";
				}
			?>
			</ol>
		</div>
<?
demo_footer_print();
?>
