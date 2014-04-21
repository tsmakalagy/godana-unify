<?php
namespace GodanaTest\Entity\Util;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Service\ServiceManagerConfig;
 
use Doctrine\Common\DataFixtures\Purger\ORMPurger as FixturePurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor as FixtureExecutor;
 
use Doctrine\ORM\Tools\SchemaTool;
 
/**
 * Base test case to be used when a new service manager instance is required
 */
abstract class ServiceManagerFactory
{
    /**
     * @var array
     */
    private static $config = array();
 
    /**
     * @static
     * @param array $config
     */
    public static function setApplicationConfig(array $config)
    {
        static::$config = $config;
    }
 
    /**
     * @static
     * @return array
     */
    public static function getApplicationConfig()
    {
        return static::$config;
    }
 
    /**
     * @param  array|null $config
     * @return ServiceManager
     */
    public static function getServiceManager(array $config = null)
    {
        $config = $config ?: static::getApplicationConfig();
        $serviceManager = new ServiceManager(new ServiceManagerConfig(
            isset($config['service_manager']) ? $config['service_manager'] : array()
        ));
        $serviceManager->setService('ApplicationConfig', $config);
        /* @var $moduleManager \Zend\ModuleManager\ModuleManagerInterface */
        $moduleManager = $serviceManager->get('ModuleManager');
        $moduleManager->loadModules();
 
        // @todo move to own factory class/add to merged configuration? Create a test module?
        $serviceManager->setFactory(
            'Doctrine\Common\DataFixtures\Executor\AbstractExecutor',
            function(ServiceLocatorInterface $sl)
            {
                /* @var $em \Doctrine\ORM\EntityManager */
                $em = $sl->get('Doctrine\ORM\EntityManager');
                /*$schemaTool = new SchemaTool($em);
                $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());*/
                return new FixtureExecutor($em, new FixturePurger($em));
            }
        );
 
        return $serviceManager;
    }
}