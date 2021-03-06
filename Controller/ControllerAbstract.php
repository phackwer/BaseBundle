<?php

namespace SanSIS\Core\BaseBundle\Controller;

use SanSIS\Core\BaseBundle\Doctrine\ORM\Mapping\OracleQuoteStrategy;
use SanSIS\Core\BaseBundle\Service\ServiceData;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class ControllerAbstract extends Controller
{
    /**
     * @var string - nome da service principal do controller
     */
    protected $service;

    /**
     * @var string - nome da view a ser renderizada
     */
    protected $indexView;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->get('doctrine.orm.entity_manager')->getConfiguration()->setQuoteStrategy(new OracleQuoteStrategy());
    }

    /**
     * Action default da controller
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        if (method_exists($this->getService(), 'getFormData')) {
            $params = array(
                'formData' => $this->getService()->getFormData(),
            );
        } else {
            $params = array();
        }

        return $this->render($this->indexView, $params);
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
        $host = $this->getRequest()->getHost();
        $port = $this->getRequest()->getPort();

        $socketcon = fsockopen($host, $port, $errno, $errstr);

        if (false === $socketcon) {
            throw new \Exception("Não foi possível disparar a requisição assíncrona. ERRNO: {$errno}, ERRSTR: {$errstr}.");
        }

        $url = $this->generateUrl($route, $params);

        $header = "POST {$url} HTTP 1.1\r\n";
        $header .= "Host: {$host}\r\n";

        $sessionId = session_id();
        if ($sendSession && $sessionId) {
            $sessionName = session_name();
            $header .= "Cookie: {$sessionName}={$sessionId}; path=/\r\n";
        }

        $header .= "Connection: Close\r\n\r\n";

        fwrite($socketcon, $header);
        fclose($socketcon);
    }

    /**
     * Renderiza um array em formato do excel.
     * @param  [type] $arr [description]
     * @return [type]      [description]
     */
    public function renderExcel($arr)
    {
        $this->exportArrayToExcel($arr);
    }

    public function exportArrayToExcel($arr)
    {
        $cab = array();
        foreach ($arr[0] as $k => $v) {
            $cab[$k] = $k;
        }
        //Cria o excel e adiciona o conteúdo a ele
        $excel = new \PHPExcel();
        $sheet = $excel->setActiveSheetIndex(0);
        // //cabeçalho
        $sheet->fromArray($cab, null, 'A1');
        // //corpo
        $sheet->fromArray($arr, null, 'A2');

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $objWriter->save('php://output');
        exit; //mudar isso para padrão Symfony, pelamordedeus
    }

    /**
     * Pega um conjunto de dados e retorna no formato JSON
     * @param mixed $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderJson($data)
    {

        $resp = json_encode($data);

        $header = array(
            'Content-Type' => 'application/json',
        );

        $response = new Response($resp, 200, $header);

        return $response;
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['registeredBundles'] = \AppKernel::getInstance()->getRegisteredBundleList();
        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }

    /**
     * Retorna a Service respectiva da Controller
     *
     * @return object
     */
    protected function getService()
    {
        return $this->get($this->service);
    }

    /**
     * Retorna a Service respectiva da Controller mapeando para um banco de dados temporário SQLite
     *
     * @return object
     */
    protected function getTmpService()
    {
        $srv = $this->get($this->service);
        $srv->setEntityManager($this->get('doctrine')->getManager('tmp'));

        return $srv;
    }

    /**
     * @param string $message
     * @param string $type
     */
    protected function addMessage($message, $type = 'info')
    {
        $this->get('session')->getFlashBag()->add($type, $message);
    }

    /**
     * Redireciona de volta para a página anterior
     *
     * @param integer $status
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectByReferer($status = 302)
    {
        $params = $this->getRefererRoute();
        return $this->redirectByRouteName($params[0], $status, $params[1]);
    }

    /**
     * Obtém o nome da rota do referer - útil para redirects
     *
     * @return array
     */
    protected function getRefererRoute()
    {
        $request = $this->getRequest();

        //look for the referer route
        $referer = $request->headers->get('referer');
        $lastPath = substr($referer, strpos($referer, $request->getBaseUrl()));
        $lastPath = str_replace($request->getBaseUrl(), '', $lastPath);
        $lastPath = explode('?', $lastPath);
        $routePath = $lastPath[0];

        $matcher = $this->get('router')->getMatcher();
        $parameters = $matcher->match($routePath);
        $route = $parameters['_route'];

        $tmp = array();

        if (isset($lastPath[1])) {
            $params = explode('&', $lastPath[1]);
            foreach ($params as $param) {
                $par = explode('=', $param);
                $tmp[$par[0]] = $par[1];
            }
        }

        $params = $tmp;

        return array($route, $params);
    }

    /**
     * Redireciona com base no nome da rota
     *
     * @param string $routeName
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectByRouteName(
        $routeName,
        $status = 302,
        $parameters = array(),
        $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ) {
        return $this->redirect($this->generateUrl($routeName, $parameters, $referenceType), $status);
    }

    /**
     *
     */
    protected function getRefererUrl()
    {
        $params = $this->getRefererRoute();
        return $this->generateUrl($params[0], $params[1], UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * @return ServiceData
     */
    public function buildServiceData()
    {
        $request = $this->getRequest();

        $params = array_merge($request->query->all(), $request->request->all());

        $files = $request->files->all();

        if (!empty($files)) {
            $params['files'] = $files;
        }

        return ServiceData::build($params);
    }
}
