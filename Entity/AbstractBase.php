<?php
namespace SanSIS\Core\BaseBundle\Entity;

use SanSIS\Core\BaseBundle\Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

/**
 *
 * @author pablo.sanchez
 *
 */
abstract class AbstractBase
{
	static protected $toArray = array();

    static protected $converted = array();

    public function fromArray(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }

        return $this;
    }

    public function toArray($parent = null)
    {
        $data = array();
        if (!in_array($this, self::$toArray)) {
            self::$toArray[] = $this;
            $methods = get_class_methods($this);
            foreach ($methods as $method) {
                if ('get' === substr($method, 0, 3)) {
                    $value = $this->$method();
                    if (\is_array($value) || $value instanceof ArrayCollection || $value instanceof PersistentCollection) {
                        $subvalues = array();
                        foreach ($value as $key => $subvalue) {
                            if ($subvalue instanceof AbstractBase && $parent != $subvalue) {
                                $subvalues[$key] = $subvalue->toArray($this);
                            } else
                                if ($value instanceof \DateTime) {
                                    $subvalue = $subvalue->format('Y-m-d h:m:i');
                                } else
                                    if (is_object($subvalue) && $parent != $subvalue) {
                                        $subvalues[$key] = $subvalue->toString();
                                    } else
                                        if ($parent != $subvalue) {
                                            $subvalues[$key] = $subvalue;
                                        }
                        }
                        $value = $subvalues;
                    }
                    if ($value instanceof AbstractBase && $parent != $value) {
                        $value = $value->toArray($this);
                    } else
                        if ($value instanceof \DateTime) {
                            $value = $value->format('Y-m-d h:m:i');
                        } else
                            if (is_object($value) && $parent != $value) {
                                /*@TODO - verificar tipo de objeto*/
                                if (method_exists($value, 'toString'))
                                    $value = $value->toString();
                                if (method_exists($value, '__toString'))
                                	$value = $value->__toString();
                            }

                    if (! $parent || ($parent && (($value instanceof AbstractBase && $parent != $value) || ! ($value instanceof AbstractBase)))) {
                        $data[lcfirst(substr($method, 3))] = $value;
                    }
                }
            }
            self::$converted[spl_object_hash($this)] = $data;
        }
        else {
            if (isset(self::$converted[spl_object_hash($this)])) {
                $data = self::$converted[spl_object_hash($this)];
            }
        }

        return $data;
    }
}