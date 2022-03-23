<?php

namespace App\Repository;

use App\Entity\DataFromSensor;
use App\Entity\DataSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataFromSensor|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataFromSensor|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataFromSensor[]    findAll()
 * @method DataFromSensor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataFromSensorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataFromSensor::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(DataFromSensor $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(DataFromSensor $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return DataFromSensor[] Returns an array of DataFromSensor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataFromSensor
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByLocal($local, $order)
    {

        return $this->createQueryBuilder('d')
            ->where('d.local = :localId')
            ->setParameter('localId', $local)
            ->orderBy('d.sendedAt', $order)
            ->getQuery()
            ->getResult();
    }

    public function findDataByLocal($local)
    {
        return $this->createQueryBuilder('d')
            ->Where('d.local = :val')
            ->setParameter('val', $local)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findDataById($id)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.id = :val')
            ->setParameter('val', $id)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ;
    }

    public function findDataByType($type)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.type = :val')
            ->setParameter('val', $type)
            ->orderBy('d.id', 'DESC')
            ->getQuery()
            ;
    }

    public function findDataBySearch(DataSearch $dataSearch)
    {

        $query =  $this->createQueryBuilder('d');
        if($dataSearch->getType()){
            $query = $query
            ->addSelect('r') // to make Doctrine actually use the join
            ->leftJoin('d.type', 'r')
            ->andWhere('r.value = :type')
            ->setParameter('type', $dataSearch->getType());
        }
        if($dataSearch->getLocal()){
            $query = $query
            ->andWhere('d.local = :local')
            ->setParameter('local', $dataSearch->getLocal());
        }
        if($dataSearch->getFrequence()){
            $query = $query
            ->andWhere('d.sendedAt >= :date');
            if($dataSearch->getFrequence() == "Week"){
                $query = $query ->setParameter('date', new \DateTime('-7 days'));
            }
            else if($dataSearch->getFrequence() == "Month"){
                $query = $query ->setParameter('date', new \DateTime('-1 month'));
            }else if($dataSearch->getFrequence() == "Trismeste"){
                $query = $query ->setParameter('date', new \DateTime('-3 months'));
            }else{
                $query = $query ->setParameter('date', new \DateTime('-1 year'));
            }
            
        }
            $query = $query ->orderBy('d.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;

            return $query;
    }

    public function findLastDataByLocal($local): ?DataFromSensor
    {
        return $this->createQueryBuilder('e')
            ->addSelect('r') // to make Doctrine actually use the join
            ->leftJoin('e.type', 'r')
            ->where('r.value = :dataType')
            ->setParameter('dataType', "CO2")
            ->andWhere('e.local = :local')
            ->setParameter('local', $local)
            ->orderBy('e.sendedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}