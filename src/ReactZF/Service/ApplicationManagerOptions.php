<?php
/**
 * @author     Evgeny Shpilevsky <evgeny@shpilevsky.com>
 * @license    MIT
 * @date       5/7/13 14:54
 */

namespace ReactZF\Service;

use ReactZF\Exception\RuntimeException;
use ReactZF\Mvc\ApplicationOptions;
use Zend\Stdlib\AbstractOptions;

class ApplicationManagerOptions extends AbstractOptions
{

    /**
     * @var array
     */
    protected $servers = array();

    /**
     * Set value of Servers
     *
     * @param array $servers
     */
    public function setServers(array $servers)
    {
        $this->servers = $servers;
    }

    /**
     * Return value of Servers
     *
     * @return array
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \ReactZF\Exception\RuntimeException
     */
    public function getServer($name)
    {
        if (!isset($this->servers[$name])) {
            throw new RuntimeException('Configuration of server "' . $name . '" not found');
        }

        return new ApplicationOptions($this->servers[$name]);
    }

}