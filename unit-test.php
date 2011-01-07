<?
function testStart($className) {
	echo "<div>Unit test for: $className</div>";
}
function testComplete() {
	echo "<div>Test complete.</div>";
}

function assertEquals($expected, $actual) {
	if($expected === $actual) {
		echo "<div>PASS</div>";
	} else {
		echo "<div>FAIL<br/>Expected: $expected<br/>Actual: $actual</div>";
	}
}
?>
