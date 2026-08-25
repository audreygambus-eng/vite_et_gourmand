document.addEventListener('DOMContentLoaded', function() {
    const formulaireFiltres = document.getElementById('filtres-menus');
    const listeMenus = document.getElementById('liste-menus');
    let timeoutId;
    
    const cheminFiltre = formulaireFiltres.dataset.urlFiltre;
    const cheminBaseDetail = formulaireFiltres.dataset.urlDetailBase.replace('/0', '');

    // Debounce
    function actualiserMenusAvecDelai() {
         clearTimeout(timeoutId);
        timeoutId = setTimeout(actualiserMenus, 300);
    }

    // On récupère les valeurs des filtres et on va interroger l'API Symfony
    function actualiserMenus(){
        const prixMax = document.getElementById('prixMax').value;
        const theme = document.getElementById('theme').value;
        const regime = document.getElementById('regime').value;
        const nbPersonnes = document.getElementById('nbPersonnes').value;

        // Seuls les filtres renseignés sont transmis
        const params = new URLSearchParams();
        if (prixMax) params.append('prixMax', prixMax);
        if (theme) params.append('theme', theme);
        if (regime) params.append('regime', regime);
        if (nbPersonnes) params.append('nbPersonnes', nbPersonnes);

        // Message de chargement pendant la requête 
        listeMenus.innerHTML = '<p class="text-center" style="color: var(--text-secondary);">Chargement des menus...</p>';

        // Appel à la route API pour retour Json
        fetch(cheminFiltre + '?' + params.toString())
            .then(response => response.json())
            .then(menus => {
                // Liste vidée avant d'être reconstruite
                listeMenus.innerHTML = '';

                if (menus.length === 0){
                    listeMenus.innerHTML = '<p class="text-center" style="color: var(--text-secondary);">Aucun menu ne correspond à ces critères.</p>';
                    return;
                }

                // Reconstruction manuelle des cartes depuis le JSON reçu
                menus.forEach(menu => {
                    const carte = document.createElement('div');
                    carte.className = 'col-md-4 mb-4';

                    const card = document.createElement('div');
                    card.className = 'carte-menu h-100';

                    const cardBody = document.createElement('div');
                    cardBody.className = 'carte-menu-body';

                    const titre = document.createElement('h3');
                    titre.className = 'card-title-dark';
                    titre.textContent = menu.titre;

                    const description = document.createElement('p');
                    description.className = 'description-menu';
                    description.style.color = 'var(--text-secondary)';
                    description.style.color = 'var(--text-secondary)';
                    description.textContent = menu.description;

                    const nbPersonnesP = document.createElement('p');
                    nbPersonnesP.className = 'caption-meta';
                    nbPersonnesP.textContent = `À partir de ${menu.nbPersonnesMin} personnes`;

                    const prix = document.createElement('p');
                    prix.style.fontWeight = '700';
                    prix.style.fontSize = '19px';

                    const prixMontant = document.createElement('span');
                    prixMontant.style.color = 'var(--primary-brand)';
                    prixMontant.textContent = `${menu.prixBase} €`;

                    const prixLabel = document.createElement('span');
                    prixLabel.style.color = 'var(--text-primary)';
                    prixLabel.textContent = ' / personne';

                    prix.appendChild(prixMontant);
                    prix.appendChild(prixLabel);

                    const bouton = document.createElement('a');
                    bouton.href = `${cheminBaseDetail}/${menu.id}`;
                    bouton.className = 'btn btn-primary';
                    bouton.textContent = 'Voir le détail';

                    cardBody.appendChild(titre);
                    cardBody.appendChild(description);
                    cardBody.appendChild(nbPersonnesP);
                    cardBody.appendChild(prix);
                    cardBody.appendChild(bouton);
                    card.appendChild(cardBody);
                    carte.appendChild(card);

                    listeMenus.appendChild(carte);
                });

            })
            .catch(error => {
                // Utilisateur informé en cas d'échec
                listeMenus.innerHTML = '<p class="text-center" style="color: var(--status-danger);">Une erreur est survenue, veuillez réessayer.</p>'
                console.error('Erreur lors du filtrage :', error);
            });
    }

    // Déclenche le filtre à chaque saisie ou sélection
    formulaireFiltres.addEventListener('input', actualiserMenusAvecDelai);
});