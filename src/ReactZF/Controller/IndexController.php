<?php
/**
 * @author     Evgeny Shpilevsky <evgeny@shpilevsky.com>
 * @license    MIT
 * @date       5/7/13 14:44
 */

namespace ReactZF\Controller;

use ReactZF\Service\ApplicationManager;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{

    /**
     * @var ApplicationManager
     */
    protected $applicationManager;

    /**
     * Run react application
     */
    public function startAction()
    {
        $manager = $this->getApplicationManager();

        if ($this->params()->fromRoute('all')) {
            $manager->runAll($this->getRequest());
        } else {
            $name = $this->params()->fromRoute('server', 'default');
            $manager->createServer($name)->run();
        }
    }

    /**
     * @param \ReactZF\Service\ApplicationManager $applicationManager
     */
    public function setApplicationManager($applicationManager)
    {
        $this->applicationManager = $applicationManager;
    }

    /**
     * @return \ReactZF\Service\ApplicationManager
     */
    public function getApplicationManager()
    {
        if (!$this->applicationManager) {
            $this->applicationManager = $this->getServiceLocator()->get('ReactZFApplicationManager');
        }

        return $this->applicationManager;
    }

}
