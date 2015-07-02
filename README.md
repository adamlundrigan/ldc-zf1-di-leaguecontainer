LdcZf1DiLeagueContainer
=======================


[![Latest Stable Version](https://poser.pugx.org/adamlundrigan/ldc-zf1-di-leaguecontainer/v/stable.svg)](https://packagist.org/packages/adamlundrigan/ldc-zf1-di-leaguecontainer) [![License](https://poser.pugx.org/adamlundrigan/ldc-zf1-di-leaguecontainer/license.svg)](https://packagist.org/packages/adamlundrigan/ldc-zf1-di-leaguecontainer) [![Build Status](https://travis-ci.org/adamlundrigan/ldc-zf1-di-leaguecontainer.svg?branch=master)](https://travis-ci.org/adamlundrigan/ldc-zf1-di-leaguecontainer) [![Code Coverage](https://scrutinizer-ci.com/g/adamlundrigan/ldc-zf1-di-leaguecontainer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/adamlundrigan/ldc-zf1-di-leaguecontainer/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/adamlundrigan/ldc-zf1-di-leaguecontainer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/adamlundrigan/ldc-zf1-di-leaguecontainer/?branch=master)


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

   If you use a non-numeric key for any value of `$dependencies` the injector will use that as the controller property to inject into.

4.  Profit!  The injector will create a public property on the controller instance for each named service. 

