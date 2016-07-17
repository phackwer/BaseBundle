<?php

namespace SanSIS\Core\BaseBundle\Component\HttpKernel;

use \Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    protected static $instance = null;
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
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
<<<<<<< HEAD
        
        if (!self::$instance)
            self::$instance = $this;
        
=======

        if (!self::$instance)
            self::$instance = $this;

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
        // replace the default string type
        \Doctrine\DBAL\Types\Type::overrideType(\Doctrine\DBAL\Types\Type::STRING, '\SanSIS\Core\BaseBundle\Doctrine\DBAL\Types\AutoEncodeStringType');
        \Doctrine\DBAL\Types\Type::overrideType(\Doctrine\DBAL\Types\Type::TEXT, '\SanSIS\Core\BaseBundle\Doctrine\DBAL\Types\AutoEncodeTextType');
    }
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    /**
     * @throws \Exception
     */
    public static function getInstance()
    {
    	if (self::$instance)
<<<<<<< HEAD
            return self::$instance; 
    	else
    	    throw new \Exception('Kernel não instanciado!');
    }
    
    public function registerBundles()
    {
        $bundles = array();
    
        return $bundles;
    }

=======
            return self::$instance;
    	else
    	    throw new \Exception('Kernel não instanciado!');
    }

    public function registerBundles()
    {
        $bundles = array();

        return $bundles;
    }

    public function getRegisteredBundleList()
    {
        $arr = array();
        foreach ($this->getBundles() as $bundle) {
            $class = get_class($bundle);
            $class = strrev($class);
            $slashPos = strpos($class, '\\');
            $class = strrev(substr($class, 0, $slashPos));
            $arr[$class] = $class;
        }
        return $arr;
    }

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
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

//     public function getLogDir()
//     {
//         return $this->rootDir.'/logs/'.$this->environment;
//     }
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    public function registerContainerConfiguration(LoaderInterface $loader){}
}