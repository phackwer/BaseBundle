<?php

namespace SanSIS\Core\BaseBundle\Doctrine\ORM\Mapping;

use Doctrine\ORM\Mapping\DefaultQuoteStrategy;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class OracleQuoteStrategy extends DefaultQuoteStrategy
{
	/**
     * {@inheritdoc}
     */
    public function getColumnAlias($columnName, $counter, AbstractPlatform $platform, ClassMetadata $class = null)
    {
        // Trim the column alias to the maximum identifier length of the platform.
        // If the alias is to long, characters are cut off from the beginning.
        // And strip non alphanumeric characters
        $columnName = $columnName . $counter;
        $columnName = substr($columnName, -$platform->getMaxIdentifierLength());
        $columnName = preg_replace('/[^A-Za-z0-9]/', '', $columnName);

        return $platform->getSQLResultCasing($columnName);
    }
}