<?php
/**
 * @author     Evgeny Shpilevsky <evgeny@shpilevsky.com>
 * @license    MIT
 * @date       5/4/13 21:43
 */

namespace ReactZF\Mvc;

use \Zend\Http\PhpEnvironment\Request as ZendRequest;
use \React\Http\Request as ReactRequest;
use Zend\Stdlib\Parameters;

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
        $this->getHeaders()->addHeaders($reactRequest->getHeaders());
        $this->setMethod($reactRequest->getMethod());
        $this->setQuery(new Parameters($reactRequest->getQuery()));
    }

    /**
     * @param string $value
     *
     * @return \Zend\Stdlib\Message
     */
    public function setContent($value)
    {
        parse_str($value, $arr);
        if (is_array($arr)) {
            $this->setPost(new Parameters($arr));
        }

        return parent::setContent($value);
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
