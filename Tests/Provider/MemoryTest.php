<?php
namespace Azera\Cache\Provider;

class MemoryTest extends \PHPUnit_Framework_TestCase {

	function testCache() {

		$cache = new Memory();

		$users = [ 'Masoud' , 'Alireza' ];

		$cache->write( 'users' , $users );

		$this->assertCount( 1 , $cache->stats() );

		$this->assertTrue( $cache->contains('users') );

		$this->assertEquals( $users , $cache->read( 'users' ) );

		$this->assertFalse( $cache->expired('users' , 1) );

		$i = 0;

		$posts = [ 'Post 1' , 'Post 2' ];

		for ( $j = 0 ; $j < 10 ; $j++ )
		$result = $cache->remember( 'posts' , function() use ( &$i , $posts ) {

			$i++;

			return $posts;

		} , 10 );

		$this->assertEquals( 1 , $i );
		$this->assertEquals( $posts , $result );
		$this->assertEquals( $posts , $cache->read( 'posts' ) );
		$this->assertCount( 2 , $cache->stats() );

		$cache->delete( 'users' );

		$this->assertFalse( $cache->contains( 'users' ) );

		$this->assertTrue( $cache->contains( 'posts' ) );

		$cache->deleteAll();

		$this->assertCount( 0 , $cache->stats() );

	}

}
?>