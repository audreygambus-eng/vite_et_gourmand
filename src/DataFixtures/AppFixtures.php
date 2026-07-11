<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Image;
use App\Entity\Avis;
use App\Entity\Commande;
use App\Entity\Theme;
use App\Entity\Regime;
use App\Entity\Allergene;
use App\Entity\Horaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Squelette vide
        // $product = new Product();
        // $manager->persist($product);

        // Création des rôles 
        $roleAdmin = new Role();
        // Avec propriété + valeur
        $roleAdmin->setLibelle('ROLE_ADMIN');
        // Préparation
        $manager->persist($roleAdmin);

        $roleEmploye = new Role();
        $roleEmploye->setLibelle('ROLE_EMPLOYE');
        $manager->persist($roleEmploye);

        $roleUser = new Role();
        $roleUser->setLibelle('ROLE_USER');
        $manager->persist($roleUser);
        
        // Création des thèmes
        $themeNoel = new Theme();
        $themeNoel->setLibelle('Noël');
        $manager->persist($themeNoel);

        $themePaques = new Theme();
        $themePaques->setLibelle('Pâques');
        $manager->persist($themePaques);

        $themeClassique = new Theme();
        $themeClassique->setLibelle('Classique');
        $manager->persist($themeClassique);

        $themeEvenement = new Theme();
        $themeEvenement->setLibelle('Événement');
        $manager->persist($themeEvenement);

        // Création des régimes
        $regimeClassique = new Regime();
        $regimeClassique->setLibelle('Classique');
        $manager->persist($regimeClassique);

        $regimeVege = new Regime();
        $regimeVege->setLibelle('Végétarien');
        $manager->persist($regimeVege);

        $regimeVegan = new Regime();
        $regimeVegan->setLibelle('Vegan');
        $manager->persist($regimeVegan);

        // Création des allergènes
        $allergenes = ['Gluten', 'Oeufs', 'Lait', 'Fruits à coque', 'Poisson'];
        foreach ($allergenes as $libelle) {
            $allergene = new Allergene();
            $allergene->setLibelle($libelle);
            $manager->persist($allergene);
        }

        // Création des horaires
        $horairesData = [
            'Lundi' => ['09:00', '19:00'],
            'Mardi' => ['09:00', '19:00'],
            'Mercredi' => ['09:00', '19:00'],
            'Jeudi' => ['09:00', '19:00'],
            'Vendredi' => ['09:00', '20:00'],
            'Samedi' => ['10:00', '20:00'],
            'Dimanche' => ['10:00', '14:00'],
        ];

        foreach ($horairesData as $jour => $heures) {
            $horaire = new Horaire();
            $horaire->setJour($jour);
            $horaire->setHeureOuverture($heures[0]);
            $horaire->setHeureFermeture($heures[1]);
            $manager->persist($horaire);
        }

        // Création des utilisateurs
        $admin = new Utilisateur();
        $admin->setNom('Dupont');
        $admin->setPrenom('José');
        $admin->setEmail('admin@vite-et-gourmand.com');
        $admin->setPassword(
            $this->hasher->hashPassword($admin, 'Admin@1234!')
        );

        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setActif(true);
        $admin->setRole($roleAdmin);
        $manager->persist($admin);

        $employe = new Utilisateur();
        $employe->setNom('Durand');
        $employe->setPrenom('Julie');
        $employe->setEmail('employe@vite-et-gourmand.com');
        $employe->setPassword(
            $this->hasher->hashPassword($employe, 'Employe@1234!')
        );

        $employe->setRoles(['ROLE_EMPLOYE']);
        $employe->setActif(true);
        $employe->setRole($roleEmploye);
        $manager->persist($employe);

        $user = new Utilisateur();
        $user->setNom('Snow');
        $user->setPrenom('John');
        $user->setEmail('user@example.com');
        $user->setPassword(
            $this->hasher->hashPassword($user, 'User@1234!')
        );

        $user->setRoles(['ROLE_USER']);
        $user->setActif(true);
        $user->setRole($roleUser);
        $manager->persist($user);

        // Création de menus de test
        $menu1 = new Menu();
        $menu1->setTitre('Menu Noël Tradition');
        $menu1->setDescription('Un menu chaleureux et familial pour célébrer Noël autour de plats traditionnels français.');
        $menu1->setConditions('Commande à effectuer 5 jours avant la prestation.');
        $menu1->setNbPersonnesMin(6);
        $menu1->setPrixBase('30.00');
        $menu1->setStockDisponible(10);
        $menu1->setActif(true);
        $menu1->setTheme($themeNoel);
        $menu1->setRegime($regimeClassique);
        $manager->persist($menu1);

        $menu2 = new Menu();
        $menu2->setTitre('Menu Végétarien de Pâques');
        $menu2->setDescription('Pour les végés qui veulent se régaler.');
        $menu2->setConditions('Commande à effectuer 3 jours avant la prestation.');
        $menu2->setNbPersonnesMin(4);
        $menu2->setPrixBase('38.00');
        $menu2->setStockDisponible(8);
        $menu2->setActif(true);
        $menu2->setTheme($themePaques);
        $menu2->setRegime($regimeVege);
        $manager->persist($menu2);

        $menu3 = new Menu();
        $menu3->setTitre('Menu Table élégante');
        $menu3->setDescription('Un menu raffiné pour un repas assis, disponible toute l\'année.');
        $menu3->setConditions('Commande à effectuer 2 jours avant la prestation.');
        $menu3->setNbPersonnesMin(2);
        $menu3->setPrixBase('32.00');
        $menu3->setStockDisponible(15);
        $menu3->setActif(true);
        $menu3->setTheme($themeClassique);
        $menu3->setRegime($regimeClassique);
        $manager->persist($menu3);

        // Création des plats de test
        $plat1 = new Plat();
        $plat1->setTitre('Délice de foie gras');
        $plat1->setType('entrée');
        $plat1->setDescription('Foie gras mi-cuit accompagné de chutney de figues.');
        $plat1->addMenu($menu1);
        $manager->persist($plat1);

        $plat2 = new Plat();
        $plat2->setTitre('Chapon farci');
        $plat2->setType('plat');
        $plat2->setDescription('Volaille fermière rôtie, farce aux marrons et champignons.');
        $plat2->addMenu($menu1);
        $manager->persist($plat2);

        $plat3 = new Plat();
        $plat3->setTitre('Bûche de Noël');
        $plat3->setType('dessert');
        $plat3->setDescription('Bûche pâtissière chocolat noisette.');
        $plat3->addMenu($menu1);
        $manager->persist($plat3);

        // Ajout d'une image test
        $image1 = new Image();
        $image1->setUrl('/images/menu-noel.jpg');
        $image1->setOrdre(1);
        $image1->setMenu($menu1);
        $manager->persist($image1);

        $manager->flush(); // ID

        // Ajout commande de test
        $commandeTest = new Commande();
        $commandeTest->setNumeroCommande('CMD-TEST-001');
        $commandeTest->setDateCommande(new \DateTime('-10 days'));
        $commandeTest->setDatePrestation(new \DateTime('-3 days'));
        $commandeTest->setHeureLivraison('19:00');
        $commandeTest->setAdresseLivraison('49 rue des Festivités');
        $commandeTest->setVilleLivraison('Bordeaux');
        $commandeTest->setNbPersonnes(6);
        $commandeTest->setPrixMenu('270.00');
        $commandeTest->setPrixLivraison('0.00');
        $commandeTest->setPrixTotal('270.00');
        $commandeTest->setPretMateriel(false);
        $commandeTest->setUtilisateur($user);
        $commandeTest->setMenu($menu1);
        $manager->persist($commandeTest);

        // Ajout avis
        $avis1 = new Avis();
        $avis1->setNote(5);
        $avis1->setCommentaire('Un traiteur de qualité supérieure. Nous nous sommes régalés !');
        $avis1->setValide(true);
        $avis1->setDateCreation(new \DateTime('-2 days'));
        $avis1->setCommande($commandeTest);
        $manager->persist($avis1);

        $manager->flush();
    }
}