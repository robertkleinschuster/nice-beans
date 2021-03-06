<?php

namespace Pars\Bean\Cache;


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
        if ($value !== null) {
            $this->cache[$method][$name] = $value;
        }
        return isset($this->cache[$method][$name]) ? $this->cache[$method][$name] : null;
    }

}
