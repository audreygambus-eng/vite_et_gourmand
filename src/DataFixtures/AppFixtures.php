<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Utilisateur;
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
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        foreach ($jours as $jour) {
            $horaire = new Horaire();
            $horaire->setJour($jour);
            $horaire->setHeureOuverture('09:00');
            $horaire->setHeureFermeture('19:00');
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

        $manager->flush();
    }
}