<?php
namespace SanSIS\Core\BaseBundle\Service;

use \Doctrine\ORM\EntityManager;
use \SanSIS\Core\BaseBundle\Doctrine\ORM\Mapping\OracleQuoteStrategy;
<<<<<<< HEAD
=======
use JMS\DiExtraBundle\Annotation as DI;
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4

abstract class ServiceAbstract
{
    /**
<<<<<<< HEAD
     * @var \Monolog\Logger 
     */
    protected $logger;
    
=======
     * @var \Monolog\Logger
     */
    protected $logger;

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     *
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    protected $securityContext;

    /**
     * @var string
     */
    protected $reportViewRoute = null;

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
<<<<<<< HEAD
    protected $container = null;    
    
    protected $secFactory = null;
    
    protected $secContext = null;

    /**
     * @param EntityManager $entityManager
=======
    protected $container = null;

    protected $secFactory = null;

    protected $secContext = null;

    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.entity_manager"),
     * })
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
     */
    public function __construct(EntityManager $entityManager = null)
    {
        $entityManager->getConfiguration()->setQuoteStrategy(new OracleQuoteStrategy());
        $this->setEntityManager($entityManager);
        $this->startTimer();
    }
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    public function setSecFactory(\Symfony\Component\Security\Core\Encoder\EncoderFactory $secFactory)
    {
        $this->secFactory = $secFactory;
    }
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    public function setSecContext(\Symfony\Component\Security\Core\SecurityContext $secContext)
    {
        $this->secContext = $secContext;
    }

    public function setServiceContainer($container)
    {
        $this->container = $container;
    }

    public function getServiceContainer()
    {
        return $this->container->getContainer();
    }

    /**
     * Executa uma action de forma assíncrona
     * ### IMPORTANTE !!! ###
     * Lembrar de chamar a função session_write_close() no método que será chamado via parametro $route
     * ### IMPORTANTE !!! ###
     *
     * @param string $route
     * @param array $params
     */
    public function phpAsync($route, $params = array(), $sendSession = true)
    {
        $host = $this->getServiceContainer()->get('request')->getHost();
        $port = $this->getServiceContainer()->get('request')->getPort();

        $socket = fsockopen($host, $port, $errno, $errstr);

        if (false === $socket) {
            throw new \Exception("Não foi possível disparar a requisição assíncrona. ERRNO: {$errno}, ERRSTR: {$errstr}.");
        }

        $url = $this->getServiceContainer()->get('router')->generate($route, $params);

        $header  = "POST {$url} HTTP 1.1\r\n";
        $header .= "Host: {$host}\r\n";

        $sessionId = session_id();
        if ($sendSession && $sessionId) {
            $sessionName = session_name();
            $header .= "Cookie: {$sessionName}={$sessionId}; path=/\r\n";
        }

        $header .= "Connection: Close\r\n\r\n";

        fwrite($socket, $header);
        $return = stream_get_contents($socket);
        fclose($socket);

        return $return;
    }

    public function processAsyncJsonReturn($response)
    {
        $arr = explode("\r\n\r\n", $response);
        array_shift($arr);
        return json_decode(implode("\r\n\r\n",$arr));
    }

    public function getReportViewRoute()
    {
        return $this->reportViewRoute;
    }
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    /**
     * Inicia contador de tempo para verificação de performance
     */
    protected function startTimer()
    {
        $this->timerStart = microtime(true);
    }

    /**
     * Para contador de tempo para verificação de performance
     */
    protected function stopTimer()
    {
        $this->timerEnd = microtime(true);
    }

    /**
     * Totaliza contador de tempo para verificação de performance
     */
    protected function totalTime()
    {
        return $this->timerEnd - $this->timerStart;
    }

    public function __destruct()
    {
        $this->stopTimer();
        $total = $this->totalTime();
        // echo $logString = "<p>--- Tempo total de execução da service: {($total)}</p>";
    }

    /**
     * @param EntityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
<<<<<<< HEAD
        
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    /**
     * @param mixed $val
     * @param string $seqName
     * @param string $entityClass
     * @return \SanSIS\Core\BaseBundle\Entity\AbstractBase
     */
    protected function getObj($val, $seqName, $entityClass) {
        if (trim($val)) {
            return $this->getEntityManager()
                        ->getRepository($entityClass)
                        ->findOneBy(array($seqName => $val));
        } else {
            return null;
        }
    }

    /**
<<<<<<< HEAD
     * @param \Symfony\Component\Security\Core\SecurityContext $securityContext            
=======
     * @param \Symfony\Component\Security\Core\SecurityContext $securityContext
>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
     */
    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;
<<<<<<< HEAD
        
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
        return $this;
    }

    /**
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * @return SanSIS\Core\BaseBundle\Entity\User
     */
    public function getUser()
    {
        return $this->getSecurityContext()
            ->getToken()
            ->getUser();
    }
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    /**
     * @return \AppKernel
     */
	public function getKernel()
    {
        return \AppKernel::getInstance();
    }
<<<<<<< HEAD
    
=======

>>>>>>> 0567d9a7991222d74a0abbf1de0631919494d6f4
    /**
     * @param \Monolog\Logger $logger
     * @return ServiceAbstract
     */
    public function setLogger($logger) {
        $this->logger = $logger;
        return $this;
    }
}