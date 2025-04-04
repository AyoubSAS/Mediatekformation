<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * 
 * @extends ServiceEntityRepository<Categorie>
 *
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function add(Categorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Categorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithFormationCount(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.name, COUNT(f.id) as formationCount')
            ->leftJoin('c.formations', 'f')
            ->groupBy('c.id, c.name')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Retourne la liste des catégories des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('c')
                ->join('c.formations', 'f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('c.name', 'ASC')   
                ->getQuery()
                ->getResult();        
    }    

    /**
     * Enregistrements dont un champ est égal à une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $name
     * @return Categorie[]
     */
    public function findAllEqual($name) : array {
            return $this->createQueryBuilder('c') // alias de la table
                    ->select('c.name name')
                    ->where('c.name=:name')
                    ->setParameter('name', $name)
                    ->getQuery()
                    ->getResult();
    }
    
    /**
     * Retourne toutes les catégories triées sur un champ
     * @param type $champ
     * @param \App\Repository\type|string|null $ordre
     * @return Categorie[]
     */
    public function findAllOrderBy($champ, $ordre): array{
            return $this->createQueryBuilder('c')
                    ->orderBy('c.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
    }
}

