<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\EntiteFormulaire;
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

    public function gigaRequeteDeSesMortsDeMerde(EntiteFormulaire $entiteFormulaire, Participant $user)
    {
        $qb = $this->createQueryBuilder('s');

        if ($entiteFormulaire->getSortiesInscrit()) {

            $qb->addSelect('p')
                ->innerJoin('s.participants', 'p')
                ->andWhere('p = ?1')
                ->setParameter(1, $user);
        }

        if ($entiteFormulaire->getSortiesOrganisees()) {

            $qb->addSelect('o')
                ->innerJoin('s.organisateur', 'o')
                ->andWhere('o = ?2')
                ->setParameter(2, $user);
        }

        if ($entiteFormulaire->getSortiesNonInscrit()) {

            $qb->addSelect('np')
                ->innerJoin('s.participants', 'np')
                ->andWhere('?3 NOT MEMBER OF s.participants')
                ->setParameter(3, $user);
        }

        if (!is_null($entiteFormulaire->getRecherche())) {

            $qb->andWhere('s.nom LIKE ?4')
                ->setParameter(4, '%' . $entiteFormulaire->getRecherche() . '%');
        }

        if (is_null($entiteFormulaire->getDateDebut())) {
            $debut = new \DateTime();
            $debut->sub(new \DateInterval('P1M'));
            $entiteFormulaire->setDateDebut($debut);
        }

        $qb->andWhere('s.dateHeureDebut > ?5')
            ->setParameter(5, $entiteFormulaire->getDateDebut());

        if (is_null($entiteFormulaire->getDateFin())) {
            $fin = new \DateTime();
            $fin->add(new \DateInterval("P1Y"));
            $entiteFormulaire->setDateFin($fin);
        }
        $qb->andWhere('s.dateHeureDebut < ?6')
            ->setParameter(6, $entiteFormulaire->getDateFin());

        if ($entiteFormulaire->getSortiesPasses()) {

            $qb->andWhere('s.etat = ?7')
                ->setParameter(7, "passé");
        }

        $qb->andWhere('s.campus = ?8')
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->setParameter(8, $entiteFormulaire->getCampus());

        return $qb->getQuery()->getResult();
    }
}
