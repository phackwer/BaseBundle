<?php 

namespace Ibram\Core\BaseBundle\ServiceLayer\Exception;

class WrongTypeRootEntityException extends \Exception{
    protected $message = 'A entidade informada é de tipo diferente da esperada.';
}
