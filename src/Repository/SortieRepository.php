<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

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


//    public function findAllSortiesOrganisees(Participant $personne)
//    {
//        $qb = $this->createQueryBuilder('s');
//
//        $qb->addSelect('o')
//            ->innerJoin('s.organisateur', 'o')
//            ->where('o = ?1')
//            ->orderBy('s.dateHeureDebut', 'ASC')
//            ->setParameter(1, $personne);
//
//        return $qb->getQuery()->getResult();
//    }
//
//    public function findAllsortiesInscrites(Participant $personne)
//    {
//        $qb = $this->createQueryBuilder('s');
//
//        $qb->addSelect('p')
//            ->innerJoin('s.participants', 'p')
//            ->where('p = ?1')
//            ->orderBy('s.dateHeureDebut', 'ASC')
//            ->setParameter(1, $personne);
//
//        return $qb->getQuery()->getResult();
//
//    }
//
//    public function findAllsortiesNonInscrites(Participant $personne)
//    {
//        $qb = $this->createQueryBuilder('s');
//
//        $qb->addSelect('p')
//            ->innerJoin('s.participants', 'p')
//            ->where('?1 NOT MEMBER OF s.participants')
//            ->orderBy('s.dateHeureDebut', 'ASC')
//            ->setParameter(1, $personne);
//
//        return $qb->getQuery()->getResult();
//
//    }
//
//    public function findAllsortiesPassees(Etat $etat)
//    {
//        $qb = $this->createQueryBuilder('s');
//
//        $qb->addSelect('p')
//            ->innerJoin('s.participants', 'p')
//            ->where('s.etat = ?1')
//            ->orderBy('s.dateHeureDebut', 'ASC')
//            ->setParameter(1, $etat);
//
//        return $qb->getQuery()->getResult();
//
//    }
//
//    public function findAllsortiesParRecherche(string $recherche)
//    {
//        $qb = $this->createQueryBuilder('s');
//
//        $qb->where('s.nom LIKE ?1')
//            ->orderBy('s.dateHeureDebut', 'ASC')
//            ->setParameter(1, '%' . $recherche . '%');
//
//        return $qb->getQuery()->getResult();
//
//    }

    public function gigaRequeteDeSesMortsDeMerde(Campus $campus, string $recherche, \DateTime $dateDebut, \DateTime $dateFin, Participant $orga, Participant $inscrit, Participant $nonInscrit, Etat $passe)
    {
        $qb = $this->createQueryBuilder('s');

        if (!is_null($inscrit)) {

            $qb->addSelect('p')
                ->innerJoin('s.participants', 'p')
                ->andWhere('p = ?1')
                ->setParameter(1, $inscrit);
        }

        if (!is_null($orga)) {

            $qb->addSelect('o')
                ->innerJoin('s.organisateur', 'o')
                ->andWhere('o = ?2')
                ->setParameter(2, $orga);
        }

        if (!is_null($nonInscrit)) {

            $qb->addSelect('np')
                ->innerJoin('s.participants', 'np')
                ->andWhere('?3 NOT MEMBER OF s.participants')
                ->setParameter(3, $nonInscrit);
        }

        if (!is_null($recherche)) {

            $qb->andWhere('s.nom LIKE ?4')
                ->setParameter(4, '%' . $recherche . '%');
        }

        if (!is_null($dateDebut)) {

            $qb->andWhere('s.dateHeureDebut > ?5')
                ->setParameter(5, $dateDebut);
        }

        if (!is_null($dateFin)) {

            $qb->andWhere('s.dateHeureDebut < ?6')
                ->setParameter(6, $dateFin);
        }

        if (!is_null($passe)) {

            $qb->andWhere('s.etat < ?7')
                ->setParameter(7, $passe);
        }


        $qb->andWhere('s.campus = ?8')
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setParameter(8, $campus);

        return $qb->getQuery()->getResult();
    }
}
