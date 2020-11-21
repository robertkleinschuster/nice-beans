<?php

namespace Niceshops\Bean\Cache;


trait BeanCacheTrait
{
    /**
     * @var array|null
     */
    private ?array $cache = null;

    /**
     * @return $this
     */
    public function clearCache()
    {
        $this->cache = null;
        return $this;
    }

    /**
     * @param string $method
     * @param string $name
     * @param $value
     * @return mixed
     */
    private function cache(string $method, $name = '', $value = null)
    {
        $name = implode(' - ', [$method, strval($name)]);
        if (null === $this->cache) {
            $this->cache = [];
        }
        if ($value !== null) {
            $this->cache[$name] = $value;
        }
        return isset($this->cache[$name]) ? $this->cache[$name] : null;
    }

}
