<?php 

namespace SanSIS\Core\BaseBundle\Service\Exception;

class NoImplementationException extends \Exception{
    protected $message = 'Implementação de método obrigatória - populate, validate e verify são exclusivas para cada service';
}
