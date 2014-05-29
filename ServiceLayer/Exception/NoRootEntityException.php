<?php 

namespace SanSIS\Core\BaseBundle\ServiceLayer\Exception;

class NoRootEntityException extends \Exception{
    protected $message = 'Não há entidade raiz definida na declaração da Service';
}
