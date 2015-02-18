<?php

namespace SanSIS\Core\BaseBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class AutoEncodeStringType extends StringType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $p)
    {
        $dbchar = \AppKernel::getInstance()->getContainer()->getParameter('database_charset');
        if ($dbchar == 'latin1') {
            $dbchar = 'CP1252';
        }

        return mb_convert_encoding($value, $dbchar, mb_detect_encoding($value));
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $p)
    {
        $dbchar = \AppKernel::getInstance()->getContainer()->getParameter('database_charset');
        if ($dbchar == 'latin1') {
            $dbchar = 'CP1252';
        }

        // echo mb_convert_encoding($value, 'UTF-8', $dbchar);

        // die;
        // echo mb_detect_encoding($value);

        return mb_convert_encoding($value, 'UTF-8', mb_detect_encoding($value));

        // return mb_convert_encoding($value, 'UTF-8', $dbchar;

    }
}
