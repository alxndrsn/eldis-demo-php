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


$q->author = 'lopez';
assertEquals('http://api.ids.ac.uk/searchapi/index.cfm/search/object/document/author/lopez/full.xml', $q->get_url());


$q->publisher = 'random';
assertEquals('http://api.ids.ac.uk/searchapi/index.cfm/search/object/document/author/lopez/publisher/random/full.xml', $q->get_url());

$q->author = NULL;
assertEquals('http://api.ids.ac.uk/searchapi/index.cfm/search/object/document/publisher/random/full.xml', $q->get_url());


$q->theme = 101;
assertEquals('http://api.ids.ac.uk/searchapi/index.cfm/search/object/document/publisher/random/theme/101/full.xml', $q->get_url());

$q->publisher = NULL;
assertEquals('http://api.ids.ac.uk/searchapi/index.cfm/search/object/document/theme/101/full.xml', $q->get_url());

testComplete();
