<?php
namespace Azera\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase {
	
	function testCache() {

		$this->assertInstanceOf( Provider\Memory::class , Cache::memory() );

		$this->assertInstanceOf( Provider\File::class , Cache::file() );

	}

	/**
	 * @requires extension xcache
	 */
	function testXCache() {

		$this->assertInstanceOf( Provider\XCache::class , Cache::xcache() );

	}

}
?>