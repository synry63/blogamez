<?php

namespace synry63\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use synry63\BlogBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller implements PHPInterface {

// recupere un mot aleatoire
    private function mot_aleatoire() {
// definition de la taille de la chaine
        $taille = rand(1, 12);
//tableau des consonnes
        $c1 = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z', 'dd', 'ff', 'll', 'mm', 'nn', 'pp', 'rr', 'ss', 'tt');
//tableau des voyelles. certaines voyelles ont été doublées ou triplé, comme le a ou le e, car elles sont tres repandu, contrairement au y
        $c2 = array('a', 'a', 'a', 'e', 'e', 'e', 'i', 'i', 'o', 'o', 'u', 'u', 'y');
        $code = "";
//generation du code
        for ($i = 1; $i < $taille; $i++)
            $code .= ($i % 2 == 0) ? $c1[rand(0, count($c1) - 1)] : $c2[rand(0, count($c2) - 1)];
//on peut encore ajouter un nombre, pour augmenter les possibilités
//$code .= "_" . rand(0, 999);
        return $code;
    }

//generateur de texte aléatoire
    private function lipsum($nb_parag) {
        $nb_mot_parag = rand(70, 100);
        //echo "nb mot para=". $nb_mot_parag;
        //echo "<br> nb parag=".$nb_parag;
        $texte = "";
        for ($i = 0; $i < $nb_parag; $i++) {
            $texte .= "";
            for ($j = 1; $j < $nb_mot_parag; $j++) {
                $texte .= $this->mot_aleatoire() . " ";
            }
            $texte .="";
        }
        return ($texte);
    }
    public function themeTraitementAction(){
        $request = $this->get('request');
        if ($request->isXmlHttpRequest()) {
            $id = $request->get('id');
            $user = $this->container->get('security.context')->getToken()->getUser();
            $tu = $this->get('wmd.theme_utilisateur_manager')->getRepository()->findOneBy(array('theme' => $id, 'user' => $user->getId()));
            if(is_object($tu) && $tu->getEtatProg()>=$tu->getTheme()->getMaxProg()){
                return new Response($this->get('translator')->trans($tu->getTheme()->getNom()));
            }
            else{
                return new Response(-1);
            }
        }
    }
    public function rafraichirHistoriqueAction(){
        $request = $this->get('request');
        if ($request->isXmlHttpRequest()) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            if(is_object($user)){
            $historique = $this->get('wmd.article_utilisateur_manager')->getRepository()->findBy(array('user' => $user->getId()),array('dateVue'=>'desc'));
            return $this->render('synry63BlogBundle:Default:menu_gauche_historique.html.twig', array(
                        'historique' => $historique,'ajax'=>1));
        
            }
            return new Response("");
        }
        
    }
    // traitement ranking
    public function rankAction(){
        $request = $this->get('request');
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($request->isXmlHttpRequest()){
            $idArticle= $request->get('idBox');
            $rate= $request->get('rate');
           // getRepository()->findOneBy(array('theme' => $theme->getId(), 'user' => $user->getId()));
            $au =  $this->get('wmd.article_utilisateur_manager')->getRepository()->findOneBy(array('article' => $idArticle, 'user' => $user->getId()));
            $au->setRank($rate);
            $this->get('wmd.article_utilisateur_manager')->flush();
            return new Response(1);
        }
        
    }
    // Recupere les news du site
    public function newsAction() {
       return $this->render('synry63BlogBundle:Default:construction.html.twig');
    }
    // forum 
    public function communityAction(){
        return $this->render('synry63BlogBundle:Default:construction.html.twig');
    }

    //randomise la liste des images admin only
    public function randomImagesAction() {
        //$root = "Symfony/web/images/";
        //$root = "images/images_fond_general";
        $dir = "images/images_fond_general";
        $nbMax = 235;
        $test = array();
        // Ouvre un dossier bien connu, et liste tous les fichiers
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != ".." && $file != "0.gif") {
                        $look = true;
                        while ($look) {
                            $randNb = rand(1, $nbMax);
                            $same = $test[$nbMax];
                            if ($same == null) {
                                $src = $dir . "/" . $file;
                                $new = $dir . "/" . $randNb . ".jpg";
                                $result = rename($src, $new);
                                $test[$randNb] = true;
                                $look = false;
                            }
                        }
                    }




                    //echo $result." ; ";    
                }
                closedir($dh);
            }
        }
    }

    //ajoute plein d'article de test admin only
    public function massArticleAction($nb, $l) {
        $repository = $this->getDoctrine()
                ->getEntityManager()
                ->getRepository('synry63BlogBundle:ColorTheme');
        $color_theme = $repository->find(1);

        $repository = $this->getDoctrine()
                ->getEntityManager()
                ->getRepository('synry63BlogBundle:Theme');

        $t = $repository->find(1);
        $repository = $this->getDoctrine()
                ->getEntityManager()
                ->getRepository('synry63BlogBundle:Categorie');
        $c = $repository->find(1);
         $repository = $this->getDoctrine()
                ->getEntityManager()
                ->getRepository('synry63BlogBundle:Image');
        $image  = $repository->find(1);
        //$image->setName("093e080f3e1b1d773b79f86c2a11fe3ae394f620");
        //$image->setPath("093e080f3e1b1d773b79f86c2a11fe3ae394f620.jpg");
        for ($i = 0; $i < $nb; $i++) {
            $article = new Article();
            $article->setTexte($this->lipsum(1));
            $article->setTitre($this->mot_aleatoire());
            $article->setlang($l);
            $article->setTheme($t);
            //$article->setImage($image);
            $article->setColorTheme($color_theme);
            $article->setCategorie($c);
            $article->setLink('http://www.siteduzero.com/');
            $article->setValidate(1);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($article);
        }
        $em->flush();


        return new Response("pour toi c'est ok");
    }

    //langue modification
    public function choisirLangueAction($langue = null) {
      //  $request = $this->get('request');
     //   $route =  $this->get('router');

        if ($langue != null) {
            // On enregistre la langue en session

            $this->get('session')->setLocale($langue);
            $request = $this->container->get('request');
            $routeName = $request->get('_route');
            //$url = $this->container->get('request')->headers;
            $url = $this->container->get('request')->headers->get('referer');
            $url = str_replace(array("fr","es","en"),$langue, $url);
            
        }
       // $test = $this->generateUrl('synry63BlogBundle_homepage',array("_locale"=>$langue));
       // $url = str_replace("http://localhost", "", $url);
       return $this->redirect($url);
       //return $this->redirect($this->generateUrl('synry63BlogBundle_homepage',array("_locale"=>$langue)));
          
    }
    // verifie si l' utilisateur a tout les themes
    private function updateThemeUser($nb){
         $liste_themes =  $liste_themes = $this->get('wmd.theme_manager')->getRepository()->findAll();
         
         if($nb!=sizeof($liste_themes)){
             $list_themes_a_rajouter = array_slice($liste_themes,$nb);
             foreach ($list_themes_a_rajouter as $key => $value) {
                 $tu = new \synry63\BlogBundle\Entity\ThemeUtilisateur();
                 $tu->setTheme($value);
                 $tu->setUser($this->container->get('security.context')->getToken()->getUser());
                 $tu->setEtatProg(0);
                 $em = $this->getDoctrine()->getEntityManager();
                 $em->persist($tu);
             }
              $em->flush();
         }
    }
    public function getLangueNavigateur(){
       $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
       $lang = $language{0}.$language{1};
       return $lang;
       //return "en"; 
    }
    public function langAction(){
    $this->get('session')->setLocale($this->getLangueNavigateur());
    $lang = $this->get('session')->getLocale();
    return  $this->redirect($this->generateUrl("synry63BlogBundle_homepage",array("_locale"=>$lang)));
    }
    //recupere default presentation du site
    public function indexAction() {
        $lang = $this->get('session')->getLocale();
        $presentation = new \stdClass();
        $presentation->titre = "PRESENTATION_TITRE";
        $presentation->texte = "PRESENTATION_TEXTE";
        $presentation->color = "#424242";


        $user = $this->container->get('security.context')->getToken()->getUser();
        $nameImageRand = $this->getRandImageName();
        if (is_object($user)) {
            $nbThemeUser =(int) $this->get('wmd.theme_utilisateur_manager')->getRepository()->getNbThemeUser($user->getId());
            $this->updateThemeUser($nbThemeUser);
            $liste_themes_user = $this->get('wmd.theme_utilisateur_manager')->getRepository()->findBy(array('user' => $user->getId()));
            $historique = $this->get('wmd.article_utilisateur_manager')->getRepository()->findBy(array('user' => $user->getId()),array('dateVue'=>'desc'));
            return $this->render('synry63BlogBundle:Default:index.html.twig', array('presentation' => $presentation,
                        'liste_themes_user' => $liste_themes_user,
                        'historique' => $historique,
                        'image_debut'=>$nameImageRand,
                        'generalPath' => $this->getGeneralPath(),
                        'imagePath' => $this->getImageRandPath($nameImageRand)));
        } else {
            // get themes
            $liste_themes = $this->get('wmd.theme_manager')->getRepository()->findAll();

            return $this->render('synry63BlogBundle:Default:index.html.twig', array('presentation' => $presentation,
                        'liste_themes' => $liste_themes,
                        'image_debut'=>$nameImageRand,
                        'generalPath' => $this->getGeneralPath(),
                        'imagePath' => $this->getImageRandPath($nameImageRand)));
        }
    }
   
    public function getImageRandPath($name) {
        return $this->getGeneralPath() .$name. '.jpg';
    }
    
    public function getGeneralPath() {
        return $this->get('request')->getBasePath() . '/images/images_fond_general/';
    }
 
    public function getRandImageName() {
        return rand(1, $this->container->getParameter('valeur_max_image'));
    }

}
