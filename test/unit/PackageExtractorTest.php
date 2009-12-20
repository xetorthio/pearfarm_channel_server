<?php
	require_once('nimblize/nimble_test/lib/phpunit_testcase.php');
	require_once(NIMBLE_ROOT . '/lib/package_extractor.php');
	class PackageExtractorTest extends PHPUnit_Framework_TestCase {	
	
		public function setUp() {
			$this->package = new PackageExtractor(__DIR__ . '/../data/nimblize-0.1.0.tgz');
		}
		
		public function testExtraction() {
			$e = $this->package->get_package_xml();
			$this->assertFalse(empty($e));
		}
		
		
		public function attrProvider() {
			return array(
										array('name', 'nimblize'),
										array('summary', '1'),
										array('description', '1'),
										array('date', '2009-12-20'),
										array('time', '01:50:23')
									);
		}
		
		/**
			* @dataProvider attrProvider
			*/
		public function testGetAttrs($name, $value) {
			$this->assertEquals($this->package->$name(), $value);
		}

		public function testGetLead() {
			$lead = $this->package->lead();
			$this->assertEquals($lead['name'], 'scott');
			$this->assertEquals($lead['user'], 'sdavis');
			$this->assertEquals($lead['email'], 'jetviper21@gmail.com');
			$this->assertEquals($lead['active'], 'yes');
		}
		
		
	
	}