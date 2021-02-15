<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * @var AuteurRepository
     */
    private $auteurRepos;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function listDesArticlePublies(){
        /*
         * Un article publié => article avec le boolean 'publie' à vrai
         * ET une date de publication qui est dans le passé.
         */
        //construction de la requete via le QueryBuilder
        $qb = $this->createQueryBuilder('a');
        $qb->andWhere('a.publie = TRUE');
        $qb->andWhere('a.dateDePublication < CURRENT_TIMESTAMP()');
        $qb->orderBy('a.dateDePublication', 'DESC');
        $qb->setMaxResults(5);

        //récuperation de la requete
        $query = $qb->getQuery();

        //récup du resultat
        $resultat = $query->getResult();
        return $resultat;
    }

    public function getArticleDunAuteurPrecis(string $auteur){
        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin('a.auteur', 'aut');
        $qb->andWhere('aut.nom = :nom');
        $qb->setParameter('nom', $auteur);
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
