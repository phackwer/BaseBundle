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
    	if ($dbchar == 'latin1') $dbchar = 'ISO-8859-1';
        // convert from utf8 to latin1
        return mb_convert_encoding($value, $dbchar, 'UTF-8');
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $p)
    {
    	$dbchar =  \AppKernel::getInstance()->getContainer()->getParameter('database_charset'); 
    	if ($dbchar == 'latin1') $dbchar = 'ISO-8859-1';
        // convert from latin1 to utf8
        return mb_convert_encoding($value, 'UTF-8', $dbchar);
    }
}