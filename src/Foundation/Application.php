<?php
/**
 * Created by PhpStorm.
 * User: Laraver
 * Date: 2017/2/22
 * Time: 22:30
 */

namespace Laraver\Waimai\Foundation;


use Doctrine\Common\Cache\FilesystemCache;
use Pimple\Container;

/**
 * Class Application
 */
abstract class Application extends Container
{

    protected $providers;

    public function __construct($config)
    {
        parent::__construct();

        $this['config'] = function () use ($config) {
            return new Config($config);
        };

        $this['cache'] = function () {
            return new FilesystemCache(sys_get_temp_dir());
        };

        $this->registerProviders();
        
        $this['access_token'] = $this->getAccessToken($this['config'], $this['cache']);
    }

    abstract protected function getAccessToken($config, $cache);

    /**
     * Register providers.
     */
    private function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }
    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

}