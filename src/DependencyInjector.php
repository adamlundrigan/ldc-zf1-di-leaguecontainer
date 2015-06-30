<?php
namespace LdcZf1DiLeagueContainer;

use League\Container\Container;

/**
 * Simple dependency injector for ZF1 applications using League\Container
 *
 * @allGloryTo https://mwop.net/blog/235-A-Simple-Resource-Injector-for-ZF-Action-Controllers.html
 */
class DependencyInjector extends \Zend_Controller_Action_Helper_Abstract
{
    public function preDispatch()
    {
        $bootstrap  = $this->getFrontController()->getParam('bootstrap');
        $controller = $this->getActionController();

        if (!isset($controller->dependencies)
            || !is_array($controller->dependencies)
            || empty($controller->dependencies)
        ) {
            return;
        }

        $container = $bootstrap->getResource('container');
        if ( ! $container instanceof Container ) {
            return;
        }

        foreach ($controller->dependencies as $name) {
            if ( ! $container->isRegistered($name) ) {
                throw new \DomainException("Unable to find dependency by name '$name'");
            }
            $controller->$name = $container->get($name);
        }
    }
}
