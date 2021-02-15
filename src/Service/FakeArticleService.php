<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\CategorieRepository;
use Curl\Curl;

/**
 * Classe servant à la génération de faux articles
 */
class FakeArticleService
{
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    public function getFakeArticle(){
        //ce sera cette méthode qui retourne le faux article.
        $curl = new Curl();
        $article = new Article();

        $categoriesList = $this->categorieRepository->findAll(); //liste des categories
        $catNumber = random_int(2, count($categoriesList)); // tirage aléatoire du nombre de categorie à ajouter
        $idx = array_rand($categoriesList, $catNumber); //tirage aléatoire des categories
        foreach ($idx as $i){
            $article->addCategory($categoriesList[$i]);
        }

        //recup un titre
        $curl->get('http://loripsum.net/api/1/short/plaintext');
        if($curl->error){
            throw new \Exception("Error in FakeArticleService");
        }
        $titre = $curl->response;
        $titre = substr($titre, 0, 255);
        $article->setTitre($titre);

        //recup d'un sous titre
        $curl->get('http://loripsum.net/api/1/short/plaintext');
        if($curl->error){
            throw new \Exception("Error in FakeArticleService");
        }
        $soustitre = $curl->response;
        $soustitre = substr($soustitre, 0, 255);
        $article->setSousTitre($soustitre);

        //recup du contenu
        $curl->get('http://loripsum.net/api/3/long/plaintext');
        if($curl->error){
            throw new \Exception("Error in FakeArticleService");
        }
        $article->setContenu($curl->response);

        $article->setPublie(true);
        $article->setDateDePublication(new \DateTime());

        return $article;
    }
}