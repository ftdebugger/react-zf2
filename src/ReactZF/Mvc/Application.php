<?php
/**
 * @author     Evgeny Shpilevsky <evgeny@shpilevsky.com>
 * @license    LICENSE.txt
 * @date       5/4/13 12:44
 */

namespace ReactZF\Mvc;

use React\EventLoop\Factory;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;
use Zend\Console\Console;
use Zend\Mvc\Application as ZendApplication;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class Application extends ZendApplication
{

    /**
     * @var ApplicationConfig
     */
    protected $config;

    /**
     * @param array $configuration
     *
     * @return ZendApplication
     */
    public static function init($configuration = array())
    {
        Console::overrideIsConsole(false);

        $smConfig = isset($configuration['service_manager']) ? $configuration['service_manager'] : array();

        $serviceManager = new ServiceManager(new ServiceManagerConfig($smConfig));
        $serviceManager->setService('ApplicationConfig', $configuration);
        $serviceManager->get('ModuleManager')->loadModules();

        $application = (new Application($configuration, $serviceManager));
        $allow = $serviceManager->getAllowOverride();

        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('application', $application);
        $serviceManager->setAllowOverride($allow);

        return $application->bootstrap();
    }

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

        $this->config = new ApplicationConfig(isset($configuration['react-zf']) ? $configuration['react-zf'] : []);
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
        $socket->listen($this->config->getPort(), $this->config->getHost());
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

}
