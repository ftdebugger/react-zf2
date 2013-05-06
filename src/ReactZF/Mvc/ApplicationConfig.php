<?php
/**
 * @author     Evgeny Shpilevsky <evgeny.shpilevsky@gmail.com>
 * @license    LICENSE.txt
 * @date       5/6/13 15:43
 */

namespace ReactZF\Mvc;

use Zend\Stdlib\AbstractOptions;

class ApplicationConfig extends AbstractOptions
{

    /**
     * @var int
     */
    protected $port = 1337;

    /**
     * @var string
     */
    protected $host = '127.0.0.1';

    /**
     * Set value of Host
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Return value of Host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set value of Port
     *
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * Return value of Port
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

}
