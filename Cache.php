<?php
namespace Azera\Cache;

class Cache {

	/**
	 * Cache Profiles
	 * 
	 * @var array
	 */
	private static $profiles = array();

	/**
	 * Instances
	 * 
	 * @var Azera\Cache\Provider\Provider[]
	 */
	private static $instances = array();

	/**
	 * Custom Providers
	 * 
	 * @var Azera\Cache\Provider\Provider[]
	 */
	private static $providers = array();

	public static function createProfile( $profile , Provider\Provider $provider , array $options = array() ) {

		self::$profiles[ $profile ] = new $provider( $options );

	}
    
	public static function &profile( $profile ) {

		return self::$profiles[ $profile ];

	}

	private static function &get( $class , array $options = array() ) {

		return self::$instances[ $class ] ?: self::$instances[ $class ] = new $class( $options );

	}

	public static function memory() {

		return self::get( Provider\Memory::class );

	}

	public static function file() {

		return self::get( Provider\File::class , [ 'directory' => __DIR__ . '/tmp' ] );

	}

	public static function xcache() {

		return self::get( Provider\XCache::class );

	}

	public static function create( $name , Provider\Provider $provider ) {

		self::$providers[ $name ] = $provider;

	}

	public static function __callStatic( $name , $args = array() ) {

		return self::$providers[ $name ];

	}
    
}
?>