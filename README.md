\# Vite \& Gourmand — Déploiement en local



Ce document décrit la démarche à suivre pour installer et exécuter l'application \*\*Vite \& Gourmand\*\* sur votre machine locale.



\---



\## 1. Prérequis



Avant de commencer, il faut avoir installé sur votre machine :



| Outil | Version minimale |

|---|---|

| PHP | 8.3.31 |

| Composer | 2.10.1 |

| MySQL | 8.0 |

| MongoDB | 6.0+ (avec l'extension PHP `mongodb`) |

| Apache | 2.4 (avec `mod\\\_rewrite` activé) |

| Git | — |



Les extensions PHP suivantes doivent être activées dans `php.ini` : `pdo\\\_mysql`, `mongodb`, `intl`, `openssl`, `zip`.



\---



\## 2. Récupérer le projet



```bash

git clone https://github.com/audreygambus-eng/vite\\\_et\\\_gourmand.git

cd vite\\\_et\\\_gourmand

```



\---



\## 3. Installer les dépendances PHP



```bash

composer install

```



\---



\## 4. Configurer les variables d'environnement



Créez un fichier `.env.local` à la racine du projet (non versionné), à partir du modèle `.env`, et renseignez :



```env

\\# Base de données MySQL

DATABASE\\\_URL="mysql://utilisateur:motdepasse@hote.amazonaws.com:3306/nom\\\_base?serverVersion=8.0"



\\# Base de données MongoDB

MONGODB\\\_URI="mongodb+srv://utilisateur:motdepasse@cluster0.xxxxx.mongodb.net/nom\\\_base?retryWrites=true\\\&w=majority"

MONGODB\\\_DB="vite\\\_gourmand\\\_stats"



\\# Cloudinary (stockage des images)

CLOUDINARY\\\_CLOUD\\\_NAME="votre\\\_cloud\\\_name"

CLOUDINARY\\\_API\\\_KEY="votre\\\_api\\\_key"

CLOUDINARY\\\_API\\\_SECRET="votre\\\_api\\\_secret"



\\# Envoi d'emails (exemple avec Mailtrap pour les tests)

MAILER\\\_DSN="smtp://utilisateur:mot\\\_de\\\_passe@sandbox.smtp.mailtrap.io:2525"



\\# Clé secrète de l'application

APP\\\_SECRET="générer\\\_une\\\_valeur\\\_aléatoire"

```



Pour générer une valeur pour `APP\\\_SECRET` :

```bash

php -r "echo bin2hex(random\\\_bytes(16));"

```



\---



\## 5. Créer la base de données et exécuter les migrations



```bash

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

```



Confirmez avec `yes` lorsque la commande le demande.



\---



\## 6. Charger les données de démonstration (fixtures)



```bash

php bin/console doctrine:fixtures:load

```



Confirmez avec `yes` — cette commande purge la base et la remplit avec des données de test (menus, plats, comptes utilisateurs).



\### Comptes de test créés automatiquement



| Rôle | Email | Mot de passe |

|---|---|---|

| Administrateur | admin@vite-et-gourmand.com | Admin@1234! |

| Employé | employe@vite-et-gourmand.com | Employe@1234! |

| Utilisateur | user@example.com | User@1234! |



\---



\## 7. Installer les assets front-end



```bash

php bin/console importmap:install

php bin/console asset-map:compile

```



\---



\## 8. Configurer le serveur web



\### Option A — Symfony CLI (recommandé pour un test rapide)



```bash

symfony server:start

```



L'application est alors accessible sur `https://localhost:8000`.



\### Option B — Apache (configuration en sous-dossier `htdocs`)



Placez le projet dans votre dossier `htdocs`, puis accédez à l'application via :
http://localhost/vite_et_gourmand/vite_et_gourmand/public/index.php


Assurez-vous que le fichier `public/.htaccess` est bien présent (nécessaire pour le routage des URLs).

### Option C — Docker (alternative complète)

Une configuration Docker est également disponible, incluant l'application, MySQL et MongoDB.

**Prérequis** : Docker Desktop installé et démarré.

```bash
docker compose up --build
```

L'application est accessible sur `http://localhost:8080`.

Exécutez ensuite les migrations et fixtures à l'intérieur du conteneur :

```bash
docker compose exec app php bin/console doctrine:migrations:migrate
docker compose exec app php bin/console doctrine:fixtures:load
```

Pour arrêter les conteneurs :

```bash
docker compose down
```

---

## 9. Vérifier l'installation

Rendez-vous sur l'URL de l'application (selon l'option choisie à l'étape 8) et vérifiez que :
- La page d'accueil s'affiche correctement avec le catalogue de menus
- La connexion fonctionne avec l'un des comptes de test ci-dessus
- Le graphique de statistiques s'affiche dans l'espace administrateur (confirmant la connexion MongoDB)

---

## 10. Dépannage courant

| Problème | Solution |
|---|---|
| Erreur de connexion MySQL | Vérifiez `DATABASE_URL` dans `.env.local` et que le service MySQL est démarré |
| Erreur de connexion MongoDB | Vérifiez que le service MongoDB est démarré et que l'extension PHP `mongodb` est activée (`php -m \| findstr mongodb`) |
| Page blanche / erreur 500 | Consultez les logs : `var/log/dev.log` |
| Images non affichées | Vérifiez que les identifiants Cloudinary sont bien configurés dans `.env.local` (`CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`, `CLOUDINARY_API_SECRET`) — ou que le dossier `public/uploads/` existe et est accessible en écriture si vous utilisez le stockage local par défaut |
| Emails non envoyés | Vérifiez la configuration `MAILER_DSN` (un compte Mailtrap gratuit peut être créé pour les tests) |
| Docker Desktop ne démarre pas | Vérifiez que la virtualisation est activée dans le BIOS, et que Docker Desktop est bien lancé avant `docker compose up` |

---

## 👤 Auteure

Audrey Gambus — Projet réalisé dans le cadre de la certification DWWM (Développeur Web et Web Mobile).
