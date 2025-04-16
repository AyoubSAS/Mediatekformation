<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use const FCATEGORIES;
use const PFORMATIONS;

define("PIDID", "p.id id");
define("PNAMENAME", "p.name name");
define("CNAME", "c.name");
define("PFORMATIONS", "p.formations");
define("CNCATEGORIENAME", "c.name categoriename");
define("FCATEGORIES", "f.categories");
/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository
{
    private const FORMATIONS_RELATION = 'p.formations';
    private const CATEGORIES_RELATION = 'f.categories';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
    * Retourne toutes les playlists triées sur le nom de la playlist
    * @param string $ordre
    * @return Playlist[]
    */
    public function findAllOrderByName($ordre): array{
        return $this->createQueryBuilder('p')
                    ->leftjoin(PFORMATIONS, 'f')
                    ->leftjoin('f.categories','c')
                    ->groupBy('p.id')
                    ->orderBy('p.name', $ordre)
                    ->getQuery()
                    ->getResult();
    }
    /**
    * Retourne toutes les playlists triées sur le nombre de formations
    * @param string $ordre
    * @return Playlist[]
    */
    public function findAllOrderByNbFormations($ordre): array{
        return $this->createQueryBuilder('p')
                    ->leftjoin(PFORMATIONS, 'f')
                    ->groupBy('p.id')
                    ->orderBy('count(p.name)', $ordre)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Tous les enregistrements si la valeur est vide
     * ou enregistrement dont un champ contient une valeur.
     * @param type $champ
     * @param type $valeur
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur): array{
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        } else {
            return $this->createQueryBuilder('p')
                        ->leftjoin(PFORMATIONS, 'f')
                        ->where('p.'.$champ.' LIKE :valeur')
                        ->setParameter('valeur', '%'.$valeur.'%')
                        ->groupBy('p.id')
                        ->orderBy('p.name', 'ASC')
                        ->getQuery()
                        ->getResult();
    }
    }
    
    /**
     * Enregistrements dont un champ contient une valeur
     * Et "table" en paramètre
     * @param type $champ
     * @param type $valeur
     * @param type $table
     * @return Playlist[]
     */
    public function findByContainValueTable($champ, $valeur, $table): array{
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        } else {
        return $this->createQueryBuilder('p')
                    ->leftjoin(PFORMATIONS, 'f')
                    ->leftjoin(FCATEGORIES, 'c')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();
            
        }
    }

    public function findAllWithFormationCount(string $sortBy = 'name', string $order = 'ASC'): array
    {
        // Valider l'ordre de tri
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        $qb = $this->createQueryBuilder('p')
            // Sélectionner l'objet playlist complet ET le compte des formations
            ->select('p as playlist', 'COUNT(f.id) as formationCount')
            // Joindre les formations (adaptez 'formations' si la relation a un autre nom)
            ->leftJoin(self::FORMATIONS_RELATION, 'f')
            ->groupBy('p.id'); // Grouper par playlist pour que COUNT fonctionne correctement

        // Appliquer le tri demandé
        if ($sortBy === 'formationCount') {
            $qb->orderBy('formationCount', $order);
            // Ajouter un tri secondaire par nom pour la cohérence si les comptes sont égaux
            $qb->addOrderBy('p.name', 'ASC');
        } else { // Tri par défaut ou par nom ('name')
            // Adaptez 'p.name' si le champ nom dans votre entité Playlist s'appelle différemment
            $qb->orderBy('p.name', $order);
        }

        // Exécuter la requête et retourner les résultats
        return $qb->getQuery()->getResult();
    }
    

}