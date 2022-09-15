<?php

namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voiture>
 *
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    public function add(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCarsByFilters($constructors = null, $fuels = null)
    {
        // si les deux paramètres sont fournis
        if (isset($constructors) && isset($fuels)) {
            return $this->findCarsByConstructorsAndFuels($constructors, $fuels);

            // si seul $constructors est fourni
        } elseif (isset($constructors)) {
            return $this->findCarsByConstructors($constructors);

            // si seul $fuels est fourni
        } elseif (isset($fuels)) {
            return $this->findCarsByFuels($fuels);

            // sinon, si aucun paramètre n'est fourni, on retourne tous les véhicules
        } else {
            return $this->findAll();
        }
    }

    public function findCarsByConstructorsAndFuels($constructors, $fuels)
    {
        return $this->createQueryBuilder('v')
            ->join('v.constructor', 'v_c')
            ->andWhere('v_c IN (:brands)')
            ->setParameter(':brands', array_values($constructors))
            ->andWhere('v.energy IN (:fuels)')
            ->setParameter(':fuels', array_values($fuels))
            ->getQuery()
            ->getResult();
    }

    public function findCarsByConstructors($constructors)
    {
        return $this->createQueryBuilder('v')
            ->join('v.constructor', 'v_c')
            ->andWhere('v_c IN (:vals)')
            ->setParameter(':vals', array_values($constructors))
            ->getQuery()
            ->getResult();
    }

    public function findCarsByFuels(array $fuels)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.energy IN (:vals)')
            ->setParameter(':vals', array_values($fuels))
            ->getQuery()
            ->getResult();
    }
}
