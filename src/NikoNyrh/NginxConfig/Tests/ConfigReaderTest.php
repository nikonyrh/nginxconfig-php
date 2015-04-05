<?php
namespace NikoNyrh\NginxConfig\Tests;

class ConfigReaderTest extends \PHPUnit_Framework_TestCase
{
	public function testParsing()
	{
		$fname  = __DIR__ . '/nginx_1.conf';
		$reader = new \NikoNyrh\NginxConfig\ConfigReader($fname);
		$result = json_encode($reader->getConfig(), JSON_PRETTY_PRINT);
		
		//TODO: Fix this very bad assert :(
		$this->assertEquals(file_get_contents(__DIR__ . '/nginx_1.json'), $result);
	}
}