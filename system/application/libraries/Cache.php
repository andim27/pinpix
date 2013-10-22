<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Object Caching Library
 *  caching system for use with memchached daemon
 *
 * @author          Warg
 */
 
class Cache {
    
    private $object;                //connection object
    public  $CI;                    //codeigniter instance reference
    
    public $prefix = '';
    public $active = true;
    private $type = '';
    private $ready = false;
    
    function Cache()
    {
        $this->CI =&get_instance();
        
        $this->type = $this->CI->config->item('cache.type');
        $this->prefix = $this->CI->config->item('cache.prefix');
        if(strlen($this->prefix) > 35) $this->prefix = substr($this->prefix, 0, 35);
        switch ($this->type) {
            case 'memcache':
                if(class_exists('memcache')) {
                    $this->object = new Memcache;
                    $params = $this->CI->config->item('cache.connection');
                    if($params == false) $params = array('host' => "127.0.0.1", 'port' => 11211);
                    $this->ready = @$this->object->connect($params['host'], $params['port']);
                }
                else show_error("memcache php extension not found");
            break;
            case 'memcached':
                if(class_exists('memcached')) {
                    $this->object = new Memcached();
                    $params = $this->CI->config->item('cache.connection');
                    if($params == false) $params = array('host' => "127.0.0.1", 'port' => 11211);
                    $this->ready = @$this->object->addServer($params['host'], $params['port']);
                }
                else show_error("memcached php library not found");
            break;
            default:
        }
    }
    
    // extended functions to automate cache data manipulation
    function process($key, $callback, $arguments = array(), $expire = 0, $flag = 0)
    {
        if(($data = $this->read($key))) return $data;
        else {
            if(is_callable($callback)) {
                $data = call_user_func_array($callback, $arguments);
                $this->write($key, $data, $expire, $flag);
                return $data;
            }
            else return false;
        }
    }
    
    function query($sql, $binds = false, $return_object = true, $return_type = 'object', $expire = 0, $flag = 0)
    {        
        if(($data = $this->read($sql))) return $data;
        else {
            //$data = $this->CI->db->query($sql, $binds, $return_object);
            if($return_type == 'object') $data = $this->CI->db->query($sql, $binds, $return_object)->result();
            if($return_type == 'array') $data = $this->CI->db->query($sql, $binds, $return_object)->result_array();
            if($return_type == 'num_rows') $data = $this->CI->db->query($sql, $binds, $return_object)->num_rows();
            $this->write($sql, $data, $expire, $flag);
            return $data;
        }
    }
    
    //control and configuration functions 
    function on() { $this->active = true; }
    function off() { $this->active = false; }
    
    // basic functions
    /* TODO: other reading ablities */
    function read($key)
    {
        if($this->ready && $this->active) {
            $key = $this->regenerate($key);
            switch ($this->type) {
                case 'memcache':
                    return $this->object->get($key);
                break;
                case 'memcached':
                    return $this->object->get($key);
                break;
            } 
        }
        else return false;
    }
    
    function write($key, $value, $expire = 0, $flag = 0)
    {
        if($this->ready && $this->active) {
            $key = $this->regenerate($key);
            switch ($this->type) {
                case 'memcache':
                    return $this->object->set($key, $value, $flag, $expire);
                break;
                case 'memcached':
                    return $this->object->set($key, $value, $expire);
                break;
            } 
        }
        else return false;
    }

    function add($key, $value, $expire = 0, $flag = 0) 
    {
        if($this->ready && $this->active) {
            $key = $this->regenerate($key);
            switch ($this->type) {
                case 'memcache':
                    return $this->object->add($key, $value, $flag, $expire);
                break;
                case 'memcached':
                    return $this->object->add($key, $value, $expire);
                break;
            } 
        }
        else return false;
    }

    function replace($key, $value, $expire = 0, $flag = 0)
    {
        if($this->ready && $this->active) {
            $key = $this->regenerate($key);
            switch ($this->type) {
                case 'memcache':
                    return $this->object->replace($key, $value, $flag, $expire);
                break;
                case 'memcached':
                    return $this->object->replace($key, $value, $expire);
                break;
            } 
        }
        else return false;        
    }
    
    /* TODO: something with time checking as memcached can accept Unix time until which */
    function delete($key, $timeout = 0)
    {
        if($this->ready && $this->active) {
            $key = $this->regenerate($key);
            switch ($this->type) {
                case 'memcache':
                    $this->object->delete($key, $timeout);
                break;
                case 'memcached':
                    $this->object->delete($key, $timeout);
                break;
            } 
        }
        else return false;
    }
    
    function flush($delay = 0)
    {
        if($this->ready && $this->active) {
            $key = $this->regenerate($key);
            switch ($this->type) {
                case 'memcache':
                    $this->object->flush();
                break;
                case 'memcached':
                    $this->object->flush($delay);
                break;
            } 
        }
        else return false;
    }
    
    //service functions
    function regenerate($key)
    {
        $key = md5($this->CI->uri->uri_string()).md5($key);
        if($this->prefix == '' || $this->prefix == false) return $key;
        else return $this->prefix."_".$key;
    }
    
}

?>