<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findAllSortiesOrganisees(Participant $personne)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->addSelect('o')
            ->innerJoin('s.organisateur', 'o')
            ->where('o = ?1')
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setParameter(1, $personne);

        return $qb->getQuery()->getResult();
    }

    public function findAllsortiesInscrites(Participant $personne)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->addSelect('p')
            ->innerJoin('s.participants', 'p')
            ->where('p = ?1')
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setParameter(1, $personne);

        return $qb->getQuery()->getResult();

    }

    public function findAllsortiesNonInscrites(Participant $personne)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->addSelect('p')
            ->innerJoin('s.participants', 'p')
            ->where('?1 NOT MEMBER OF s.participants')
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setParameter(1, $personne);

        return $qb->getQuery()->getResult();

    }

    public function findAllsortiesPassees(Etat $etat)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->addSelect('p')
            ->innerJoin('s.participants', 'p')
            ->where('s.etat = ?1')
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setParameter(1, $etat);

        return $qb->getQuery()->getResult();

    }

    public function findAllsortiesParRecherche(String $recherche)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->where('s.nom LIKE ?1')
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setParameter(1, $recherche);

        return $qb->getQuery()->getResult();

    }
}
