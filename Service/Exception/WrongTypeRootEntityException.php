<?php 

namespace SanSIS\Core\BaseBundle\Service\Exception;

class WrongTypeRootEntityException extends \Exception{
    protected $message = 'A entidade informada é de tipo diferente da esperada.';
}
