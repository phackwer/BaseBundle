<?php

namespace SanSIS\Core\BaseBundle\ServiceLayer;

use SanSIS\Core\BaseBundle\ServiceLayer\ServiceDataException;

class ServiceData implements \ArrayAccess
{
    /**
     * @var mixed[]
     */
    protected $data = null;

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container = null;

    /**
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct($container = null)
    {
        $this->container = $container;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * carrega no objeto o array passado
     * @param array $data
     */
    public function load(array $data)
    {
        $this->data = $data;
    }

    /**
     * fabrica um objeto, podendo opcionalmente carregar os dados
     * @param array $data
     * @return ServiceData
     */
    static public function build(array $data = null)
    {
        $sd = new self();
        if ($data !== null) {
            $sd->load($data);
        }
        return $sd;
    }

    /**
     * alias para o metodo offsetSet()
     * @param string || integer $offset
     * @param mixed $value
     */
    public function set($offset, $value)
    {
        $this->offsetSet($offset, $value);
        return $this;
    }

    /**
     * alias para o metodo offsetGet(). Se nao for passado o $offset, retorna o array inteiro.
     * @param string || integer || null $offset
     * @return mixed
     */
    public function get($offset = null)
    {
        if ($offset === null) {
            if (empty($this->data)) {
                throw new ServiceDataException('O objeto ainda nao foi carregado.');
            }
            return $this->data;
        }
        return $this->offsetGet($offset);
    }

    /**
     * @param string || integer $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
        return $this;
    }

    /**
     * @param  string || integer $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data) && array_key_exists($offset, $this->data);
    }

    /**
     * @param string || integer $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @param string || integer || null $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (isset($this->data) && isset($this->data[$offset])) {
            return $this->data[$offset];
        }else{
            throw new \InvalidArgumentException($offset . ' nao existe.');
        }
    }
}