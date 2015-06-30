LdcZf1DiLeagueContainer
=======================

## What?

It's a simple glue layer which injects services registered in a [League\Container](http://container.thephpleague.com) into a ZF1 controller. 

Heavily based on [@weierophinney](https://github.com/weierophinney)'s now-many-moons-ago blog post "[A Simple Resource Injector for ZF Action Controllers](https://mwop.net/blog/235-A-Simple-Resource-Injector-for-ZF-Action-Controllers.html)".

## How?

1. Install the [Composer](https://getcomposer.org/) package:

    ```
    composer require adamlundrigan/ldc-zf1-di-league-container:1.*@stable
    ```

2. Register the helper in your ZF1 application bootstrap:
    
    ```
    protected function _initContainer()
    {
        $container = new League\Container\Container();
        // Fill your container    
        return $container;
    }

    protected function _initContainerResourceInjector()
    {
        $this->bootstrap('container');
        
        Zend_Controller_Action_HelperBroker::addHelper(
            new \LdcZf1DiLeagueContainer\DependencyInjector()
        );
    }
    ```

3. In each controller, define a list of dependencies to inject:

   ```
   class FooController extends Zend_Controller_Action
   {
       public $dependencies = array(
           'db',
           'layout',
           'navigation',
       );
   }
   ```

4.  Profit!  The injector will create a public property on the controller instance for each named service. 
