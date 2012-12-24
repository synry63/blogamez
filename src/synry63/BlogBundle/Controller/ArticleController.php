<?php

namespace synry63\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use synry63\BlogBundle\Entity\Article;
use synry63\BlogBundle\Entity\Image;
use synry63\BlogBundle\Entity\ArticleUtilisateur;
use synry63\BlogBundle\Entity\ThemeUtilisateur;
use synry63\BlogBundle\Form\ThemeType;

class ArticleController extends Controller implements PHPInterface {

    //get an article vue
    public function historiqueArticleAction(Article $article) {
        $request = $this->get('request');
            
        // ajax requete
        if ($request->isXmlHttpRequest()) {
            $rank = true;
            $user = $this->container->get('security.context')->getToken()->getUser();
            $moy = $this->get('wmd.article_utilisateur_manager')->getRepository()->getAverage($article->getId());
            //$aus = $article->getUsers();
            //$au = $aus->setUser();
             //$au = $aus[0]; recupere un articleUtilisateur
            if ($moy == null)
                   $moy = 5;
            $au = $this->get('wmd.article_utilisateur_manager')->getRepository()->findOneBy(array('article'=>$article->getId(),'user'=>$user->getId()));
            if(is_object($au)){
                if( $au->getRank()==null) $rank = false;
            }
            return $this->render('synry63BlogBundle:Default:article.html.twig', array('article' => $article,'moy'=>$moy,'rank'=>$rank));
        }
    }

    // get an article rand
    public function articleAction() {
        $lang = $this->get('session')->getLocale();
        $request = $this->get('request');
        $filtreId = 0;
        // ajax requete
        if ($request->isXmlHttpRequest()) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            //get article no see by user
            if (is_object($user)) {
                $id = $user->getId();
                $filtreId  = (int) $request->get('idFiltre');
                if($filtreId==0) 
                    $articles = $this->get('wmd.article_manager')->getRepository()->getArticlesNoSee($id, $lang);
                else
                    $articles = $this->get('wmd.article_manager')->getRepository()->getArticlesNoSeeFiltre($id, $lang,$filtreId);
                
                $nbMax = count($articles);
                if ($nbMax > 0) {
                    $randNb = rand(0, ($nbMax - 1));
                    $article = $articles[$randNb];
                    $moy = $this->get('wmd.article_utilisateur_manager')->getRepository()->getAverage($article->getId());
                    if ($moy == null)
                        $moy = 5;
                    $this->ajouterArticleUtilisateur($article);
                    $tu = $this->updateProgThemeUser($article);
                    return $this->render('synry63BlogBundle:Default:article.html.twig', array('article' => $article,'tu' => $tu, 'moy' => $moy));
                }
               
            }
            //get any article demo
            $i =  $this->get('session')->getFlash('i');
            if($i==null) $i=0;
           $articles = $this->get('wmd.article_manager')->getRepository()->findBy(array('demo' => 1, 'lang' => $lang));
            $nb = count($articles);
            if ($nb <= 0)
               return new Response("pas d'article disponible");
            if($i==($nb)) $i=0;
            $this->get('session')->setFlash('i',($i+1));
            //$nb = count($articles);
            //if ($nb <= 0)
            //    return new Response("pas d'article disponible");
            
            return $this->render('synry63BlogBundle:Default:article.html.twig', array('article' => $articles[$i],'demo'=>1,'filtre'=>$filtreId));
        }
    }

    //update progretion theme
    private function updateProgThemeUser($article) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $theme = $article->getTheme();
        $tu = $this->get('wmd.theme_utilisateur_manager')->getRepository()->findOneBy(array('theme' => $theme->getId(), 'user' => $user->getId()));
        if (is_object($tu)) {
            if ($tu->getEtatProg() < $tu->getTheme()->getMaxProg()) {
                $tu->setEtatProg($tu->getEtatProg() + 1);
                $this->get('wmd.theme_utilisateur_manager')->flush();
            }
        } else {
            $tu = new ThemeUtilisateur();
            $tu->setTheme($article->getTheme());
            $tu->setUser($this->container->get('security.context')->getToken()->getUser());
            $tu->setEtatProg(($tu->getEtatProg() + 1));
            $this->get('wmd.theme_utilisateur_manager')->persistAndFlush($tu);
        }
        return $tu;
    }

    // insert article see by user
    private function ajouterArticleUtilisateur($article) {
        $acticleUtilisateur = new ArticleUtilisateur();
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Et pour vérifier que l'utilisateur est authentifié (et non un anonyme)
        if (is_object($user)) {
            $acticleUtilisateur->setUser($user);
            $acticleUtilisateur->setArticle($article);
            $acticleUtilisateur->setDateVue(new \DateTime());
            $this->get('wmd.article_utilisateur_manager')->persistAndFlush($acticleUtilisateur);
        }
    }

    // get the preview article 
    public function previewAction() {
        $request = $this->get('request');
        // validation de l'article
        if ($request->getMethod() == 'POST') {
            $id = $request->get('id');

            $this->get('wmd.article_manager')->getRepository()->valideArticle($id);
            $this->get('session')->setFlash('notice', 'Article bien enregistré');
            return $this->redirect($this->generateUrl('synry63BlogBundle_homepage'));
        }
        // recuperation de la page preview
        else {
            $id = $request->get('id');
            $article = $this->get('wmd.article_manager')->getRepository()->find($id);
            // get themes
            $liste_themes = $this->get('wmd.theme_manager')->getRepository()->findAll();
            //get colorTheme
            $liste_color_themes = $this->get('wmd.color_theme_manager')->getRepository()->findAll();


            return $this->render('synry63BlogBundle:Default:article_preview.html.twig', array('article_preview' => $article,
                        'liste_themes' => $liste_themes,
                        'liste_color_themes' => $liste_color_themes,
                        'image_debut'=>$this->getRandImageName(),
                        'generalPath' => $this->getGeneralPathImage(),
                        'imagePath' => $this->getImageRandPath($this->container->getParameter('valeur_max_image'))));
        }
    }

    // get empty article form and add the new article
    public function ajouterAction() {
        // On récupère la requête.
        $request = $this->get('request');
        $article = new Article();
        // On crée le FormBuilder grâce à la méthode du contrôleur.
        $formBuilder = $this->createFormBuilder($article);

        $formBuilder
                ->add('titre', 'text', array('label' => 'LABEL_TITRE_FORM'))
                ->add('texte', 'textarea', array('label' => 'LABEL_TEXTE_FORM'))
                ->add('link', 'url', array('label' => 'LABEL_lINK_FORM'))
                ->add('image', 'file', array('label' => 'LABEL_IMG_FORM'))
                ->add('theme', 'entity', array(
                    'class' => 'synry63BlogBundle:Theme',
                    'property' => 'nom',
                    'label' => 'LABEL_THEME_FORM'));
        /* ->add('categorie', 'entity', array(
          'class' => 'synry63BlogBundle:Categorie',
          'property' => 'nom',
          'label' => 'LABEL_CATEGORIE_FORM')); */
        $form = $formBuilder->getForm();
        $result = 1;
        if ($request->getMethod() == 'POST') {
            // recuparation de l'id color theme
            $idColorTheme = (int) $request->get('color_chose'); // get input hidden color chose
            if ($idColorTheme == 0)
                $idColorTheme = 1;
            // On fait le lien Requête <-> Formulaire.
            $form->bindRequest($request);
            // user 
            $user = $this->container->get('security.context')->getToken()->getUser();
            if (is_object($user))
                $article->setUser($user);

            // get color theme
            $color_theme = $this->get('wmd.color_theme_manager')->getRepository()->find($idColorTheme);
            
            // On vérifie que les valeurs rentrées sont correctes.
            if ($form->isValid()) {
                $fileImage = $article->getImage();
                $image = new Image();
                $image->preUpload($fileImage);
                $image->upload($fileImage);
                
                $result = $image->validationTypeImagePerso();
                if($result==1){
                    $article->setImage($image);
                    $article->setColorTheme($color_theme);
                    $article->setlang($this->get('session')->getLocale());
                    // On l'enregistre notre article
                    $this->get('wmd.article_manager')->saveArticle($article);
                    // On redirige vers la page de visualisation de l'article nouvellement créé
                    return $this->redirect($this->generateUrl('synry63BlogBundle_previewacticle', array('id' => $article->getId())));
                }
                else $image->removeFile();
                
            }
        }
        $nameImageRand = $this->getRandImageName();
        // get themes
        $liste_themes = $this->get('wmd.theme_manager')->getRepository()->findAll();
        //get colorTheme
        $liste_color_themes = $this->get('wmd.color_theme_manager')->getRepository()->findAll();
        
        return $this->render('synry63BlogBundle:Default:formarticle.html.twig', array(
                    'form' => $form->createView(),
                    'erreur'=>$result,
                    'liste_themes' => $liste_themes,
                    'liste_color_themes' => $liste_color_themes,
                    'image_debut' => $nameImageRand,
                    'generalPath' => $this->getGeneralPathImage(),
                    'imagePath' => $this->getImageRandPath($this->container->getParameter('valeur_max_image'))));

        //}
    }

    public function getImageRandPath($name) {
        return $this->getGeneralPathImage() . $name . '.jpg';
    }

    public function getGeneralPathImage() {
        return $this->get('request')->getBasePath() . '/images/images_fond_general/';
    }

    public function getRandImageName() {
        return rand(1, $this->container->getParameter('valeur_max_image'));
    }



}

