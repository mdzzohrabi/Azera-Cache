<?php
namespace Azera\Cache\Provider;

/**
 * Azera Cache Provider Interface
 * 
 * @author  Masoud Zohrabi <mdzzohrabi@gmail.com>
 * @license MIT
 */
interface ProviderInterface {
    
    /**
     * Constructor
     * 
     * @param array $options Options
     */
    public function __construct( array $options = array() );
    
    /**
     * Write to cache
     * 
     * @param   string $key     Cache key
     * @param   mixed  $value   Cache value
     * @return  mixed           Value
     */
    public function write( $key , $value );
    
    /**
     * Read from cache
     * 
     * @param  string $key Cache key
     * @return mixed    Cached value
     */
    public function read( $key );
    
    /**
     * Check existing an cache key
     * 
     * @param  string $key Cache key
     * @return boolean
     */
    public function contains( $key );
    
    /**
     * Read data from cache if exists otherwise write to it
     * 
     * @param  string     $key      Cache key
     * @param  mixed      $value    Cache value
     * @param  int|string $minutes  Expires minutes
     * @return mixed
     */
    public function remember( $key , $value , $minutes );
    
    /**
     * Delete cached key
     * 
     * @param string $key Cache key
     */
    public function delete( $key );

    /**
     * Delete all cached keys
     */
    public function deleteAll();

    /**
     * Check if cached item expired
     * 
     * @param  string  $key          Cache key
     * @param  integer $limitMinutes Limit Minutes
     * @return boolean
     */
    public function expired( $key , $limitMinutes );

    /**
     * Return stats of cached items
     * 
     * @return array
     */
    public function stats();
        
}
?>