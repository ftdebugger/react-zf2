<?php
/**
 * @author     Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ReactZFTest\Mvc;


use ReactZF\Mvc\Request;
use React\Http\Request as ReactRequest;

class RequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Request
     */
    protected $object;


    protected function setUp()
    {
        $this->object = new Request();
    }

    public function testSetReactRequest()
    {
        $request = new ReactRequest('POST', '/test/', ['a' => 1], '1.1', ['User-Agent' => 'phpunit']);
        $this->object->setReactRequest($request);

        $this->assertTrue($this->object->isPost());
        $this->assertEquals('/test/', $this->object->getUriString());
        $this->assertCount(1, $this->object->getHeaders());
        $this->assertEquals('phpunit', $this->object->getHeader('user-agent')->getFieldValue());
        $this->assertEquals('1', $this->object->getQuery()->get('a'));
    }

    public function testGetReactRequest()
    {
        $request = new ReactRequest('POST', '/test/', ['a' => 1], '1.1', ['User-Agent' => 'phpunit']);
        $this->object->setReactRequest($request);
        $this->assertInstanceOf('React\Http\Request', $this->object->getReactRequest());
    }

    public function testSetContent()
    {
        $this->object->setContent('a=11&b=12');
        $this->assertEquals('a=11&b=12', $this->object->getContent());
        $this->assertEquals('11', $this->object->getPost('a'));
        $this->assertEquals('12', $this->object->getPost('b'));
    }

    public function testSetContentJson()
    {
        $this->object->setContent('{"status": 1}');
        $this->assertEquals('{"status": 1}', $this->object->getContent());
    }

}
