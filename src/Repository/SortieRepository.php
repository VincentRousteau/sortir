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


    public function gigaRequeteDeSesMortsDeMerde(Campus $campus, $recherche, \DateTime $dateDebut, \DateTime $dateFin, Participant $orga, Participant $inscrit, Participant $nonInscrit, Etat $passe)
    {
        $qb = $this->createQueryBuilder('s');

        if (!is_null($inscrit->getNom())) {

            $qb->addSelect('p')
                ->innerJoin('s.participants', 'p')
                ->andWhere('p = ?1')
                ->setParameter(1, $inscrit);
        }

        if (!is_null($orga->getNom())) {

            $qb->addSelect('o')
                ->innerJoin('s.organisateur', 'o')
                ->andWhere('o = ?2')
                ->setParameter(2, $orga);
        }

        if (!is_null($nonInscrit->getNom())) {

            $qb->addSelect('np')
                ->innerJoin('s.participants', 'np')
                ->andWhere('?3 NOT MEMBER OF s.participants')
                ->setParameter(3, $nonInscrit);
        }

        if (!is_null($recherche)) {

            $qb->andWhere('s.nom LIKE ?4')
                ->setParameter(4, '%' . $recherche . '%');
        }

        $qb->andWhere('s.dateHeureDebut > ?5')
            ->setParameter(5, $dateDebut);

        $qb->andWhere('s.dateHeureDebut < ?6')
            ->setParameter(6, $dateFin);

        if (!is_null($passe->getLibelle())) {

            $qb->andWhere('s.etat = ?7')
                ->setParameter(7, $passe);
        }

        $qb->andWhere('s.campus = ?8')
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setParameter(8, $campus);

        return $qb->getQuery()->getResult();
    }
}
