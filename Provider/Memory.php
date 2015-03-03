<?php
namespace Azera\Cache\Provider;

/**
 * Azera Cache Memory Provider
 * 
 * @author Masoud Zohrabi <mdzzohrabi@gmail.com>
 * @license MIT
 */
class Memory extends Provider {

	/**
	 * Cache Repository stored in memory
	 * 
	 * @var array
	 */
	private $repo = array();

	protected $allowObject = true;

	/**
	 * {@inheritdoc}
	 */
	public function write( $key , $value ) {

		list( $key , $value ) = $this->prepare( $key , $value );

		$this->repo[ $key ] = [ 'value' => $value , 'created' => time() ];

		return $value;

	}

	/**
	 * {@inheritdoc}
	 */
	public function read( $key ) {

		return ($cache = $this->repo[ $this->getName( $key ) ]) !== null ? $cache['value'] : false;

	}

	/**
	 * {@inheritdoc}
	 */
	public function delete( $key ) {

		unset( $this->repo [ $this->getName( $key ) ] );

	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteAll() {

		$this->repo = array();

	}

	/**
	 * {@inheritdoc}
	 */
	public function contains( $key ) {

		return isset( $this->repo[ $this->getName( $key ) ] );

	}

	/**
	 * {@inheritdoc}
	 */
	public function expired( $key , $mins ) {

		return (time() - $this->repo[ $this->getName( $key ) ]['created']) > $mins * 60;

	}

	/**
	 * {@inheritdoc}
	 */
	public function stats() {

        return $this->repo;

	}

}
?>