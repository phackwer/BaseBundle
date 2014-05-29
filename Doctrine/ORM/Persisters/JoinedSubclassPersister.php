<?php

namespace Ibram\Core\BaseBundle\Doctrine\ORM\Persisters;

use \Doctrine\ORM\Persisters\JoinedSubclassPersister as JSP;
use \Doctrine\ORM\Mapping\ClassMetadata;
use \Doctrine\ORM\Query\ResultSetMapping;

use \Doctrine\DBAL\LockMode;
use \Doctrine\DBAL\Types\Type;

use \Doctrine\Common\Collections\Criteria;

use \Doctrine\Common\Annotations\AnnotationReader;

/**
 * {@inheritdoc}
 */
class JoinedSubclassPersister extends JSP
{
    /**
     * {@inheritdoc}
     */
    private $_owningTableMap = array();

    /**
     * {@inheritdoc}
     */
    private $_quotedTableMap = array();

    /**
     * @var \Doctrine\Common\Annotations\AnnotationReader
     */
    protected $reader;
    
    /**
     * {@inheritdoc}
     */
    protected function _getSelectEntitiesSQL($criteria, $assoc = null, $lockMode = 0, $limit = null, $offset = null, array $orderBy = null)
    {
        $idColumns = $this->_class->getIdentifierColumnNames();
        $baseTableAlias = $this->_getSQLTableAlias($this->_class->name);

        //var_dump($this->getClassMetadata($this->_class->name));die;

        // Create the column list fragment only once
        if ($this->_selectColumnListSql === null) {

            $this->_rsm = new ResultSetMapping();
            $this->_rsm->addEntityResult($this->_class->name, 'r');

            // Add regular columns
            $columnList = '';

            foreach ($this->_class->fieldMappings as $fieldName => $mapping) {
                if ($columnList != '') $columnList .= ', ';

                $columnList .= $this->_getSelectColumnSQL(
                    $fieldName,
                    isset($mapping['inherited']) ? $this->_em->getClassMetadata($mapping['inherited']) : $this->_class
                );
            }

            // Add foreign key columns
            foreach ($this->_class->associationMappings as $assoc2) {
                if ($assoc2['isOwningSide'] && $assoc2['type'] & ClassMetadata::TO_ONE) {
                    $tableAlias = isset($assoc2['inherited']) ? $this->_getSQLTableAlias($assoc2['inherited']) : $baseTableAlias;

                    foreach ($assoc2['targetToSourceKeyColumns'] as $srcColumn) {
                        if ($columnList != '') $columnList .= ', ';

                        $columnList .= $this->getSelectJoinColumnSQL(
                            $tableAlias,
                            $srcColumn,
                            isset($assoc2['inherited']) ? $assoc2['inherited'] : $this->_class->name
                        );
                    }
                }
            }

            //@FIX: Should only add when defined by user!!!
            // Add discriminator column (DO NOT ALIAS, see AbstractEntityInheritancePersister#_processSQLResult).
            $discrColumn = $this->_class->discriminatorColumn['name'];
            if ($discrColumn && $discrColumn != 'dtype') {
                $tableAlias  = ($this->_class->rootEntityName == $this->_class->name) ? $baseTableAlias : $this->_getSQLTableAlias($this->_class->rootEntityName);
                $columnList .= ', ' . $tableAlias . '.' . $discrColumn;

                $resultColumnName = $this->_platform->getSQLResultCasing($discrColumn);

                $this->_rsm->setDiscriminatorColumn('r', $resultColumnName);
                $this->_rsm->addMetaResult('r', $resultColumnName, $discrColumn);
            }
            else {
                foreach ($idColumns as $idColumn) {
                    $columnList .= ', ' . $tableAlias . '.' . $idColumn;
                }
                $columnList. ' DTYPE ';
            }
        }

        // inheritancejoinedby
        if ($inheritanceJoinedBy = $this->isInheritedJoinedBy()) {
            //Replace by recursive method
            $joinSql = $this->recursiveClassJoin($inheritanceJoinedBy);
        } else {
            $joinSql = ' ';
            foreach ($this->_class->parentClasses as $parentClassName) {
                $parentClass = $this->_em->getClassMetadata($parentClassName);
                $tableAlias = $this->_getSQLTableAlias($parentClassName);
                $joinSql .= ' INNER JOIN ' . $this->quoteStrategy->getTableName($parentClass, $this->_platform) . ' ' . $tableAlias . ' ON ';
                $first = true;

                foreach ($idColumns as $idColumn) {
                    if ($first) $first = false; else $joinSql .= ' AND ';

                    $joinSql .= $baseTableAlias . '.' . $idColumn . ' = ' . $tableAlias . '.' . $idColumn;
                }
            }
        }

        //trecho comentado para evitar comportamento não esperado: 
//         // OUTER JOIN sub tables
//         foreach ($this->_class->subClasses as $subClassName) {
//             $subClass = $this->_em->getClassMetadata($subClassName);
//             $tableAlias = $this->_getSQLTableAlias($subClassName);

//             if ($this->_selectColumnListSql === null) {
//                 // Add subclass columns
//                 foreach ($subClass->fieldMappings as $fieldName => $mapping) {
//                     if (isset($mapping['inherited'])) continue;

//                     $columnList .= ', ' . $this->_getSelectColumnSQL($fieldName, $subClass);
//                 }

//                 // Add join columns (foreign keys)
//                 foreach ($subClass->associationMappings as $assoc2) {
//                     if ($assoc2['isOwningSide'] && $assoc2['type'] & ClassMetadata::TO_ONE && ! isset($assoc2['inherited'])) {
//                         foreach ($assoc2['targetToSourceKeyColumns'] as $srcColumn) {
//                             if ($columnList != '') $columnList .= ', ';

//                             $columnList .= $this->getSelectJoinColumnSQL(
//                                 $tableAlias,
//                                 $srcColumn,
//                                 isset($assoc2['inherited']) ? $assoc2['inherited'] : $subClass->name
//                             );
//                         }
//                     }
//                 }
//             }

//             // Add LEFT JOIN
//             $joinSql .= ' LEFT JOIN ' . $this->quoteStrategy->getTableName($subClass, $this->_platform) . ' ' . $tableAlias . ' ON ';
//             $first = true;

//             foreach ($idColumns as $idColumn) {
//                 if ($first) $first = false; else $joinSql .= ' AND ';

//                 $joinSql .= $baseTableAlias . '.' . $idColumn . ' = ' . $tableAlias . '.' . $idColumn;
//             }
//         }

        $joinSql .= ($assoc != null && $assoc['type'] == ClassMetadata::MANY_TO_MANY) ? $this->_getSelectManyToManyJoinSQL($assoc) : '';

        $conditionSql = ($criteria instanceof Criteria)
            ? $this->_getSelectConditionCriteriaSQL($criteria)
            : $this->_getSelectConditionSQL($criteria, $assoc);

        // If the current class in the root entity, add the filters
        if ($filterSql = $this->generateFilterConditionSQL($this->_em->getClassMetadata($this->_class->rootEntityName), $this->_getSQLTableAlias($this->_class->rootEntityName))) {
            if ($conditionSql) {
                $conditionSql .= ' AND ';
            }

            $conditionSql .= $filterSql;
        }

        $orderBy = ($assoc !== null && isset($assoc['orderBy'])) ? $assoc['orderBy'] : $orderBy;
        $orderBySql = $orderBy ? $this->_getOrderBySQL($orderBy, $baseTableAlias) : '';

        if ($this->_selectColumnListSql === null) {
            $this->_selectColumnListSql = $columnList;
        }

        $lockSql = '';

        if ($lockMode == LockMode::PESSIMISTIC_READ) {
            $lockSql = ' ' . $this->_platform->getReadLockSql();
        } else if ($lockMode == LockMode::PESSIMISTIC_WRITE) {
            $lockSql = ' ' . $this->_platform->getWriteLockSql();
        }

//        echo 'SELECT ' . $this->_selectColumnListSql
//            . ' FROM ' . $this->quoteStrategy->getTableName($this->_class, $this->_platform) . ' ' . $baseTableAlias
//            . $joinSql;die;

        return $this->_platform->modifyLimitQuery('SELECT ' . $this->_selectColumnListSql
                . ' FROM ' . $this->quoteStrategy->getTableName($this->_class, $this->_platform) . ' ' . $baseTableAlias
                . $joinSql
                . ($conditionSql != '' ? ' WHERE ' . $conditionSql : '') . $orderBySql, $limit, $offset)
                . $lockSql;
    }

    public function getAnnotationReader()
    {
        if (!$this->reader) {
            $this->reader = new AnnotationReader();
        }

        return $this->reader;
    }

    public function isInheritedJoinedBy()
    {
        return $this->getClassAnnotation();
    }

    public function getClassAnnotation($className = null, $annotation = 'Ibram\Core\BaseBundle\Doctrine\ORM\Mapping\InheritanceJoinedBy')
    {
        if (!$className)
            $className = $this->_class->name;
        $currObj = new $className;
        $ref = new \ReflectionClass($currObj);

        return $this->getAnnotationReader()->getClassAnnotation($ref, $annotation);
    }

    /**
     * Mapeia a herança de acordo com as definições do desenvolvedor, e não do automagicamente
     * @param \Ibram\Core\BaseBundle\Doctrine\ORM\Mapping\InheritanceJoinedBy $inheritanceJoinedBy
     * @param string $from
     */
    public function recursiveClassJoin($inheritanceJoinedBy, $from = null)
    {
        if(!$from)
            $from = $this->_class->name;

//        var_dump($inheritanceJoinedBy);

        $fromColumn = $inheritanceJoinedBy->mappedBy;
        $to         = $inheritanceJoinedBy->targetEntity;
        $toColumn   = $inheritanceJoinedBy->inversedBy;

//        var_dump($this->_em->getClassMetadata($to));

        $joinSql = ' ';
//        if ($from != $this->_class->name)
//            $joinSql .= $this->quoteStrategy->getTableName($this->getClassMetadata($from), $this->_platform);
        $joinSql .= ' INNER JOIN ' . $this->quoteStrategy->getTableName($this->_em->getClassMetadata($to), $this->_platform) . ' ' . $this->_getSQLTableAlias($to) . ' ON ';
        $joinSql .= $this->_getSQLTableAlias($from) . '.' . $fromColumn . ' = ' . $this->_getSQLTableAlias($to) . '.' . $toColumn;
        if ($inheritanceJoinedBy = $this->getClassAnnotation($to))
            $joinSql .= $this->recursiveClassJoin($inheritanceJoinedBy, $to);

        return $joinSql;
    }
}
