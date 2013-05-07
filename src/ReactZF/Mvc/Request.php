<?php
/**
 * @author     Evgeny Shpilevsky <evgeny@shpilevsky.com>
 * @license    MIT
 * @date       5/4/13 21:43
 */

namespace ReactZF\Mvc;

use \Zend\Http\PhpEnvironment\Request as ZendRequest;
use \React\Http\Request as ReactRequest;

class Request extends ZendRequest
{

    /**
     * @var ReactRequest
     */
    protected $reactRequest;

    /**
     * Set value of ReactRequest
     *
     * @param \React\Http\Request $reactRequest
     */
    public function setReactRequest($reactRequest)
    {
        $this->reactRequest = $reactRequest;
        $this->setUri($reactRequest->getPath());
    }

    /**
     * Return value of ReactRequest
     *
     * @return \React\Http\Request
     */
    public function getReactRequest()
    {
        return $this->reactRequest;
    }

}
