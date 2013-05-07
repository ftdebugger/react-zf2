<?php
/**
 * @author     Evgeny Shpilevsky <evgeny@shpilevsky.com>
 * @license    MIT
 * @date       5/4/13 21:43
 */

namespace ReactZF\Mvc;

use \Zend\Http\PhpEnvironment\Response as ZendResponse;
use \React\Http\Response as ReactResponse;

class Response extends ZendResponse
{
    /**
     * @var ReactResponse
     */
    protected $reactResponse;

    /**
     * @return ZendResponse
     */
    public function sendHeaders()
    {
        $this->getReactResponse()->writeHead($this->getStatusCode(), $this->getHeaders()->toArray());

        return $this;
    }

    /**
     * @return ZendResponse
     */
    public function sendContent()
    {
        $this->getReactResponse()->end($this->getContent());

        return $this;
    }

    /**
     * Set value of ReactResponse
     *
     * @param \React\Http\Response $reactResponse
     */
    public function setReactResponse($reactResponse)
    {
        $this->reactResponse = $reactResponse;
    }

    /**
     * Return value of ReactResponse
     *
     * @return \React\Http\Response
     */
    public function getReactResponse()
    {
        return $this->reactResponse;
    }

}
