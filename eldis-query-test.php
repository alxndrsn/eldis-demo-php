<?
require_once('unit-test.php');
require_once('eldis.php');

testStart('EldisQuery');

$q = new EldisQuery();
assertEquals('http://api.ids.ac.uk/searchapi/index.cfm/search/object/document/short.json', $q->get_url());

$q->size = 'full';
assertEquals('http://api.ids.ac.uk/searchapi/index.cfm/search/object/document/full.json', $q->get_url());

$q->format = 'xml';
assertEquals('http://api.ids.ac.uk/searchapi/index.cfm/search/object/document/full.xml', $q->get_url());

testComplete();
