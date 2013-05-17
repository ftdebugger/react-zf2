<?php
/**
 * @author     Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ReactZFTest\Mvc;


use ReactZF\Mvc\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Response
     */
    protected $object;


    protected function setUp()
    {
        $this->object = new Response();
    }

    public function testSendHeaders()
    {
        $this->object->setStatusCode(400);
        $this->object->getHeaders()->addHeaderLine('Content-Type', 'plain/text');

        $response = $this->getMockBuilder('React\Http\Response')
            ->setMethods(['writeHead'])
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('writeHead')
            ->with($this->equalTo(400), $this->equalTo(['Content-Type' => 'plain/text']));

        $this->object->setReactResponse($response);
        $this->object->sendHeaders();
    }

    public function testSendContent()
    {
        $this->object->setContent('body');

        $response = $this->getMockBuilder('React\Http\Response')
            ->setMethods(['end'])
            ->disableOriginalConstructor()
            ->getMock();

        $response->expects($this->once())
            ->method('end')
            ->with($this->equalTo('body'));

        $this->object->setReactResponse($response);
        $this->object->sendContent();
    }
}
