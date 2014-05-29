<?php 

namespace Ibram\Core\BaseBundle\ServiceLayer\Exception;

class NoImplementationException extends \Exception{
    protected $message = 'Implementação de método obrigatória - populate, validate e verify são exclusivas para cada service';
}
