<?php
namespace Azera\Cache\Provider;

/**
 * Azera Cache File Provider
 * 
 * @author Masoud Zohrabi <mdzzohrabi@gmail.com>
 * @license MIT
 */
class File extends Provider {

	/**
	 * Directory path
	 * 
	 * @var string
	 */
	public $directory;

	public function __construct( array $options = array() ) {

		parent::__construct( $options );

		$this->directory = $options['directory'];

		if ( !is_dir( $this->directory ) && !@mkdir( $this->directory , 0777 , true ) )
			throw new \InvalidArgumentException(sprintf(
					'The directory "%s" does not exists and could not be created.',
					$this->directory
				));

	}

	/**
	 * {@inheritdoc}
	 */
	public function getValue( $value ) {

		$value = parent::getValue( $value );

		if ( is_array( $value ) )
			return serialize( $value );

		return $value;

	}

	/**
	 * Get cached file path
	 * 
	 * @param  string $key 	Cache key
	 * @return string 		Path
	 */
	public function getPath( $key ) {

		return $this->directory . '/' . $this->getName( $key );

	}

	/**
	 * {@inheritdoc}
	 */
	public function write( $key , $value ) {

		$value = $this->getValue( $value );

		file_put_contents( $this->getPath( $key ) , $value );

		return $value;

	}

	/**
	 * {@inheritdoc}
	 */
	public function read( $key ) {

		if ( !$this->contains( $key ) )
			return false;

		$value = file_get_contents( $this->getPath( $key ) );

		if ( ( $array = @unserialize( $value ) ) !== false )
			return $array;

		return $value;

	}

	/**
	 * {@inheritdoc}
	 */
	public function delete( $key ) {

		@unlink( $this->getPath( $key ) );

	}

	private function getCachedFiles() {

		return glob( $this->directory . '/*' );

	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteAll() {

		foreach ( $this->getCachedFiles() as $file )
			if ( is_file( $file ) )
				@unlink( $file );

	}

	/**
	 * {@inheritdoc}
	 */
	public function contains( $key ) {

		return file_exists( $this->getPath( $key ) );

	}

	/**
	 * {@inheritdoc}
	 */
	public function expired( $key , $mins ) {

		if ( !$this->contains( $key ) ) return false;

		return (time() - filemtime( $this->getPath( $key ) )) > $mins * 60;

	}

	/**
	 * {@inheritdoc}
	 */
	public function stats() {

        return $this->getCachedFiles();

	}

}
?>