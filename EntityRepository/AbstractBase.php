<?php
namespace Ibram\Core\BaseBundle\EntityRepository;

use Doctrine\ORM\EntityRepository;

/**
 * @author pablo.sanchez
 *
 */
abstract class AbstractBase extends EntityRepository
{
    /** Verifica se o filtro existe nos parâmetros passados
     *
     * @param string[] $arr
     * @param string $key
     * @return boolean
     */
    public function hasFilter($arr, $key)
    {
        return (isset($arr[$key]) && trim($arr[$key]) != '');
    }

    /**
     * Cria SQL para pesquisa simples para grids
     */
    public function getSimpleGridSearchQuery($searchData = null)
    {
        $query = $this->createQueryBuilder('g')->getQuery();
        
        if ($searchData) {
            $and = ' where ';
            foreach ($searchData as $field => $criteria) {
                if (trim($searchData[$field]) != "") {
                    $query->setDQL($query->getDQL() . $and . 'g.' . $field . ' like :' . $field . ' ');
                    $query->setParameter($field, '%' . str_replace(' ', '%', $criteria) . '%');
                    $and = ' and ';
                }
            }
        }
        
        return $query;
    }
}