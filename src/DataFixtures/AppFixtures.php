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
        $menu1->setDelaiMinimumJours(5);
        $menu1->setNbPersonnesMin(6);
        $menu1->setPrixBase('30.00');
        $menu1->setStockDisponible(10);
        $menu1->setActif(true);
        $menu1->setTheme($themeNoel);
        $menu1->setRegime($regimeClassique);
        $manager->persist($menu1);

        $menu2 = new Menu();
        $menu2->setTitre('Menu Pâques Agneau');
        $menu2->setDescription('Menu printanier autour de l\'agneau, plat traditionnel de Pâques.');
        $menu2->setConditions('Commande à effectuer 3 jours avant la prestation.');
        $menu2->setDelaiMinimumJours(3);
        $menu2->setNbPersonnesMin(4);
        $menu2->setPrixBase('38.00');
        $menu2->setStockDisponible(8);
        $menu2->setActif(true);
        $menu2->setTheme($themePaques);
        $menu2->setRegime($regimeClassique);
        $manager->persist($menu2);

        $menu3 = new Menu();
        $menu3->setTitre('Menu Table élégante');
        $menu3->setDescription('Un menu raffiné pour un repas assis, disponible toute l\'année.');
        $menu3->setConditions('Commande à effectuer 2 jours avant la prestation.');
        $menu3->setDelaiMinimumJours(2);
        $menu3->setNbPersonnesMin(2);
        $menu3->setPrixBase('32.00');
        $menu3->setStockDisponible(15);
        $menu3->setActif(true);
        $menu3->setTheme($themeClassique);
        $menu3->setRegime($regimeClassique);
        $manager->persist($menu3);

        $menu4 = new Menu();
        $menu4->setTitre('Menu Vegan');
        $menu4->setDescription('Un menu Un menu que tout le monde pourra manger.');
        $menu4->setConditions('Commande à effectuer 3 jours avant la prestation.');
        $menu4->setDelaiMinimumJours(3);
        $menu4->setNbPersonnesMin(6);
        $menu4->setPrixBase('36.00');
        $menu4->setStockDisponible(10);
        $menu4->setActif(true);
        $menu4->setTheme($themeClassique);
        $menu4->setRegime($regimeVegan);
        $manager->persist($menu4);

        $menu5 = new Menu();
        $menu5->setTitre('Menu Cocktail Business');
        $menu5->setDescription('Formule cocktail dînatoire idéale pour vos réceptions d\'entreprise.');
        $menu5->setConditions('Commande à effectuer 7 jours avant la prestation.');
        $menu5->setDelaiMinimumJours(7);
        $menu5->setNbPersonnesMin(10);
        $menu5->setPrixBase('45.00');
        $menu5->setStockDisponible(8);
        $menu5->setActif(true);
        $menu5->setTheme($themeEvenement);
        $menu5->setRegime($regimeClassique);
        $manager->persist($menu5);

        $menu6 = new Menu();
        $menu6->setTitre('Menu Végétarien');
        $menu6->setDescription('Pour les végés qui veulent se régaler.');
        $menu6->setConditions('Commande à effectuer 2 jours avant la prestation.');
        $menu6->setDelaiMinimumJours(2);
        $menu6->setNbPersonnesMin(4);
        $menu6->setPrixBase('30.00');
        $menu6->setStockDisponible(10);
        $menu6->setActif(true);
        $menu6->setTheme($themeClassique);
        $menu6->setRegime($regimeVege);
        $manager->persist($menu6);



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

        $plat4 = new Plat();
        $plat4->setTitre('Terrine de campagne');
        $plat4->setType('entrée');
        $plat4->setDescription('Belle terrine accompagnée de pickles et de pain toasté.');
        $plat4->addMenu($menu2);
        $manager->persist($plat4);

        $plat5 = new Plat();
        $plat5->setTitre('Gigot d\'agneau confit aux herbes et gratin dauphinois');
        $plat5->setType('plat');
        $plat5->setDescription('Pièce de viande fondante, avec herbes de Provence, et gratin onctueux.');
        $plat5->addMenu($menu2);
        $manager->persist($plat5);

        $plat6 = new Plat();
        $plat6->setTitre('Fraisier');
        $plat6->setType('dessert');
        $plat6->setDescription('Fraisier traditionnel.');
        $plat6->addMenu($menu2);
        $manager->persist($plat6);

        $plat7 = new Plat();
        $plat7->setTitre('Foie gras maison');
        $plat7->setType('entrée');
        $plat7->setDescription('Foie gras mi-cuit accompagné de chutney de figues.');
        $plat7->addMenu($menu3);
        $manager->persist($plat7);

        $plat8 = new Plat();
        $plat8->setTitre('Suprême de pintade');
        $plat8->setType('plat');
        $plat8->setDescription('Volaille fermière et sa sauce aux morilles.');
        $plat8->addMenu($menu3);
        $manager->persist($plat8);

        $plat9 = new Plat();
        $plat9->setTitre('Tiramisu');
        $plat9->setType('dessert');
        $plat9->setDescription('Tiramisu au café et poudre de cacao.');
        $plat9->addMenu($menu3);
        $manager->persist($plat9);

        $plat10 = new Plat();
        $plat10->setTitre('Terrine de légumes');
        $plat10->setType('entrée');
        $plat10->setDescription('Terrine de légumes grillés et tofu mariné.');
        $plat10->addMenu($menu4);
        $manager->persist($plat10);

        $plat11 = new Plat();
        $plat11->setTitre('Ragoût de lentilles');
        $plat11->setType('plat');
        $plat11->setDescription('Lentilles vertes et légumes racines.');
        $plat11->addMenu($menu4);
        $manager->persist($plat11);

        $plat12 = new Plat();
        $plat12->setTitre('Panna cotta');
        $plat12->setType('dessert');
        $plat12->setDescription('Panna cotta de tradition avec coulis de fruits rouges.');
        $plat12->addMenu($menu4);
        $manager->persist($plat12);

        $plat13 = new Plat();
        $plat13->setTitre('Feuilletés de la mer');
        $plat13->setType('entrée');
        $plat13->setDescription('Feuilletés croustillants garnis de crevettes.');
        $plat13->addMenu($menu5);
        $manager->persist($plat13);

        $plat14 = new Plat();
        $plat14->setTitre('Délicieux saumon');
        $plat14->setType('plat');
        $plat14->setDescription('Pavé de saumon frais, beurre blanc.');
        $plat14->addMenu($menu5);
        $manager->persist($plat14);

        $plat15 = new Plat();
        $plat15->setTitre('Entremet gourmand');
        $plat15->setType('dessert');
        $plat15->setDescription('Entremet trois chocolats.');
        $plat15->addMenu($menu5);
        $manager->persist($plat15);

        $plat16 = new Plat();
        $plat16->setTitre('Curry de légume');
        $plat16->setType('entrée');
        $plat16->setDescription('Curry de légumes et lait de coco.');
        $plat16->addMenu($menu6);
        $manager->persist($plat16);

        $plat17 = new Plat();
        $plat17->setTitre('Risotto');
        $plat17->setType('plat');
        $plat17->setDescription('Risotto aux champignons et parmesan.');
        $plat17->addMenu($menu6);
        $manager->persist($plat17);

        $plat18 = new Plat();
        $plat18->setTitre('Salade de fruits');
        $plat18->setType('dessert');
        $plat18->setDescription('Salade fraîcheur et ses fruits du marché.');
        $plat18->addMenu($menu6);
        $manager->persist($plat18);

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