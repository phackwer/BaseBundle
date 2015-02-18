<?php

namespace SanSIS\Core\BaseBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

/**
 * Type that maps an SQL CLOB to a PHP string.
 *
 * @since 2.0
 */
class AutoEncodeTextType extends TextType
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

        return $value;

        return mb_convert_encoding($value, $dbchar, mb_detect_encoding($value));
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $p)
    {
        $value = is_resource($value) ? stream_get_contents($value) : $value;

        $dbchar = \AppKernel::getInstance()->getContainer()->getParameter('database_charset');
        if ($dbchar == 'latin1') {
            $dbchar = 'CP1252';
        }

        return $value;

        return mb_convert_encoding($value, 'UTF-8', $dbchar);
    }
}
