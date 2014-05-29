<?php

namespace SanSIS\Core\BaseBundle\Component\HttpKernel;

use \Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    protected static $instance = null;
    /**
     * Constructor.
     *
     * @param string  $environment The environment
     * @param Boolean $debug       Whether to enable debugging or not
     *
     * @api
     */
    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug);
        
        if (!self::$instance)
            self::$instance = $this;
    }
    
    /**
     * @TODO - Finalizar o getInstance
     * @throws \Exception
     */
    public static function getInstance()
    {
    	if (self::$instance)
            return self::$instance; 
    	else
    	    throw new \Exception('Kernel não instanciado!');
    }
    
    public function registerBundles()
    {
        $bundles = array();
    
        return $bundles;
    }

    public function getAppDir()
    {
        $arr = explode('/',$_SERVER["SCRIPT_NAME"]);
        array_pop($arr);
        return implode('/',$arr);
    }

    public function getCacheDir()
    {
        return $this->rootDir.'/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return $this->rootDir.'/logs/'.$this->environment;
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader){}
}