<?php
namespace Azera\Cache\Provider;

use Closure;
use InvalidArugmentException;

abstract class Provider implements ProviderInterface {

	protected $hashCache = array();

	protected $hashFunction = 'md5';

	protected $allowObject = false;

	/**
	 * {@inheritdoc}
	 */
	public function __construct( array $options = array() ) {

	}

	public function setHashFunction( $hashFunction ) {

		$this->hashFunction = $hashFunction;

	}

	public function hash( $value ) {

		return call_user_func_array( $this->hashFunction , [ $value ] );

	}

	public function getName ( $key ) {

		return $this->hashCache[$key] ?: $this->hashCache[$key] = $this->hash( $key );

	}

	public function getValue( $value ) {

		if ( $value instanceof Closure )
			return call_user_func( $value );

		if ( is_object( $value ) && !$this->allowObject )
			throw new InvalidArgumentException( 'Invalid cache value , ' . gettype($value) . ' given' );

		return $value;

	}
	
	public function prepare( $key , $value ) {

		$key = $this->getName( $key );
		$value = $this->getValue( $value );

		return [ $key , $value ];

	}

	public function remember( $key , $value , $minutes ) {

		if ( !$this->expired( $key , $minutes ) && $result = $this->read( $key ) )
			return $result;

		return $this->write( $key , $value );

	}

}
?>