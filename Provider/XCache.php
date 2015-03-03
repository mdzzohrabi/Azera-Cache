<?php
namespace Azera\Cache\Provider;

/**
 * Azera Cache XCache Provider
 * 
 * @author Masoud Zohrabi <mdzzohrabi@gmail.com>
 * @license MIT
 */
class XCache extends Provider {

	protected $namespace = 'xcache';

	public function __construct( array $options = array() ) {

		if ( !extension_loaded('xcache') )
			throw new \RuntimeException( 'XCache does not installed or enabled in php.ini' );

		parent::__construct( $options );

	}

	public function getName( $key ) {

		return $this->namespace . '_' . parent::getName( $key );

	}

	/**
	 * {@inheritdoc}
	 */
	public function write( $key , $value ) {

		list( $key , $value ) = $this->prepare( $key , $value );

		xcache_set( $key , [ 'created' => time() , 'value' => $value ] );

		return $value;

	}

	/**
	 * {@inheritdoc}
	 */
	public function read( $key ) {

		return $this->contains( $key ) ? xcache_get( $this->getName( $key ) )['value'] : false; 

	}

	/**
	 * {@inheritdoc}
	 */
	public function delete( $key ) {

		xcache_unset( $this->getName( $key ) );

	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteAll() {

		xcache_unset_by_prefix( $this->namespace . '_' );

	}

	/**
	 * {@inheritdoc}
	 */
	public function contains( $key ) {

		return xcache_isset( $this->getName( $key ) );

	}

	/**
	 * {@inheritdoc}
	 */
	public function expired( $key , $mins ) {

		$key = $this->getName( $key );

		if ( xcache_isset( $key ) )
			return (time() - xcache_get( $key )['created']) > $mins * 60;

	}

	/**
	 * {@inheritdoc}
	 */
	public function stats() {

        return xcache_count();

	}

}
?>