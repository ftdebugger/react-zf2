<?php
/**
 * @author     Evgeny Shpilevsky <evgeny@shpilevsky.com>
 * @license    MIT
 * @date       5/4/13 12:44
 */

namespace ReactZF\Mvc;

use React\EventLoop\Factory;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;
use Zend\Mvc\Application as ZendApplication;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

class Application extends ZendApplication
{

    /**
     * @var ApplicationOptions
     */
    protected $serverOptions;

    /**
     * Constructor
     *
     * @param array          $configuration
     * @param ServiceManager $serviceManager
     */
    public function __construct($configuration, ServiceManager $serviceManager)
    {
        $this->configuration = $configuration;
        $this->serviceManager = $serviceManager;

        $this->setEventManager($serviceManager->get('EventManager'));
        $this->request = new Request();
        $this->response = new Response();
    }

    /**
     * @return void|\Zend\Stdlib\ResponseInterface
     */
    public function run()
    {
        $loop = Factory::create();
        $socket = new SocketServer($loop);
        $http = new HttpServer($socket);

        $this->getEventManager()->attach(MvcEvent::EVENT_FINISH, [$this, 'renderRequest'], -1000);

        $http->on('request', [$this, 'processRequest']);
        $socket->listen($this->serverOptions->getPort(), $this->serverOptions->getHost());
        $loop->run();
    }

    /**
     * @param \React\Http\Request  $request
     * @param \React\Http\Response $response
     */
    public function processRequest($request, $response)
    {
        $this->request = new Request();
        $this->request->setReactRequest($request);

        $this->response = new Response();
        $this->response->setReactResponse($response);

        $allow = $this->getServiceManager()->getAllowOverride();

        $this->getServiceManager()->setAllowOverride(true);
        $this->getServiceManager()->setService('Request', $this->request);
        $this->getServiceManager()->setService('Response', $this->response);
        $this->getServiceManager()->setAllowOverride($allow);

        $event = $this->getMvcEvent();
        $event->setError(null);
        $event->setRequest($this->getRequest());
        $event->setResponse($this->getResponse());

        parent::run();
    }

    /**
     * @param MvcEvent $event
     */
    public function renderRequest(MvcEvent $event)
    {
        /** @var Response $zendResponse */
        $zendResponse = $event->getResponse();
        $zendResponse->send();
        $event->stopPropagation();
    }

    /**
     * Set value of Options
     *
     * @param \ReactZF\Mvc\ApplicationOptions $options
     */
    public function setServerOptions($options)
    {
        $this->serverOptions = $options;
    }

    /**
     * Return value of Options
     *
     * @return \ReactZF\Mvc\ApplicationOptions
     */
    public function getServerOptions()
    {
        return $this->serverOptions;
    }

}
