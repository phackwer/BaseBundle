<?php

namespace SanSIS\Core\BaseBundle\Doctrine\ORM;

use \Doctrine\ORM\UnitOfWork as UW;

/**
 * {@inheritdoc}
 */
class UnitOfWork extends UW
{
    /**
     * The identity map that holds references to all managed entities that have
     * an identity. The entities are grouped by their class name.
     * Since all classes in a hierarchy must share the same identifier set,
     * we always take the root class name of the hierarchy.
     *
     * @var array
     */
    private $identityMap = array();

    /**
     * Map of all identifiers of managed entities.
     * Keys are object ids (spl_object_hash).
     *
     * @var array
     */
    private $entityIdentifiers = array();

    /**
     * Map of the original entity data of managed entities.
     * Keys are object ids (spl_object_hash). This is used for calculating changesets
     * at commit time.
     *
     * @var array
     * @internal Note that PHPs "copy-on-write" behavior helps a lot with memory usage.
     *           A value will only really be copied if the value in the entity is modified
     *           by the user.
     */
    private $originalEntityData = array();

    /**
     * Map of entity changes. Keys are object ids (spl_object_hash).
     * Filled at the beginning of a commit of the UnitOfWork and cleaned at the end.
     *
     * @var array
     */
    private $entityChangeSets = array();

    /**
     * The (cached) states of any known entities.
     * Keys are object ids (spl_object_hash).
     *
     * @var array
     */
    private $entityStates = array();

    /**
     * Map of entities that are scheduled for dirty checking at commit time.
     * This is only used for entities with a change tracking policy of DEFERRED_EXPLICIT.
     * Keys are object ids (spl_object_hash).
     *
     * @var array
     * @todo rename: scheduledForSynchronization
     */
    private $scheduledForDirtyCheck = array();

    /**
     * A list of all pending entity insertions.
     *
     * @var array
     */
    private $entityInsertions = array();

    /**
     * A list of all pending entity updates.
     *
     * @var array
     */
    private $entityUpdates = array();

    /**
     * Any pending extra updates that have been scheduled by persisters.
     *
     * @var array
     */
    private $extraUpdates = array();

    /**
     * A list of all pending entity deletions.
     *
     * @var array
     */
    private $entityDeletions = array();

    /**
     * All pending collection deletions.
     *
     * @var array
     */
    private $collectionDeletions = array();

    /**
     * All pending collection updates.
     *
     * @var array
     */
    private $collectionUpdates = array();

    /**
     * List of collections visited during changeset calculation on a commit-phase of a UnitOfWork.
     * At the end of the UnitOfWork all these collections will make new snapshots
     * of their data.
     *
     * @var array
     */
    private $visitedCollections = array();

    /**
     * The EntityManager that "owns" this UnitOfWork instance.
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * The calculator used to calculate the order in which changes to
     * entities need to be written to the database.
     *
     * @var \Doctrine\ORM\Internal\CommitOrderCalculator
     */
    private $commitOrderCalculator;

    /**
     * The entity persister instances used to persist entity instances.
     *
     * @var array
     */
    private $persisters = array();

    /**
     * The collection persister instances used to persist collections.
     *
     * @var array
     */
    private $collectionPersisters = array();

    /**
     * The EventManager used for dispatching events.
     *
     * @var \Doctrine\Common\EventManager
     */
    private $evm;

    /**
     * Orphaned entities that are scheduled for removal.
     *
     * @var array
     */
    private $orphanRemovals = array();

    /**
     * Read-Only objects are never evaluated
     *
     * @var array
     */
    private $readOnlyObjects = array();

    /**
     * Map of Entity Class-Names and corresponding IDs that should eager loaded when requested.
     *
     * @var array
     */
    private $eagerLoadingEntities = array();

    /**
     * Initializes a new UnitOfWork instance, bound to the given EntityManager.
     *
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->evm = $em->getEventManager();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityPersister($entityName)
    {
//        $class = new $entityName;
//        $ref = new \ReflectionClass($class);
//        $ann = $ref->getDocComment();
//        var_dump($this->em->getClassMetadata($entityName));die;

        if (isset($this->persisters[$entityName])) {
            return $this->persisters[$entityName];
        }
    
        $class = $this->em->getClassMetadata($entityName);
    
        switch (true) {
        	case ($class->isInheritanceTypeNone()):
        	    $persister = new \Doctrine\ORM\Persisters\BasicEntityPersister($this->em, $class);
        	    break;
    
        	case ($class->isInheritanceTypeSingleTable()):
        	    $persister = new \Doctrine\ORM\Persisters\SingleTablePersister($this->em, $class);
        	    break;
    
        	case ($class->isInheritanceTypeJoined()):
        	    $persister = new \SanSIS\Core\BaseBundle\Doctrine\ORM\Persisters\JoinedSubclassPersister($this->em, $class);
        	    break;
    
        	default:
        	    $persister = new \Doctrine\ORM\Persisters\UnionSubclassPersister($this->em, $class);
        }
    
        $this->persisters[$entityName] = $persister;
    
        return $this->persisters[$entityName];
    }
}