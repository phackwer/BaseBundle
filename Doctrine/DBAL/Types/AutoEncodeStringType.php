<?php 

namespace SanSIS\Core\BaseBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class AutoEncodeStringType extends StringType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $p)
    {
    	$dbchar =  \AppKernel::getInstance()->getContainer()->getParameter('database_charset'); 
        // convert from db encoding to latin1
        return mb_convert_encoding($value, $dbchar, 'UTF-8');
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $p)
    {
    	$dbchar =  \AppKernel::getInstance()->getContainer()->getParameter('database_charset'); 
        // convert from db encoding to utf8
        return mb_convert_encoding($value, 'UTF-8', $dbchar);
    }
}