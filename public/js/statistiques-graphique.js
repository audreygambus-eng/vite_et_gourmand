document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('graphiqueCommandes');
    if (!canvas){
        return;
    }

    const labels = JSON.parse(canvas.dataset.labels);
    const valeurs = JSON.parse(canvas.dataset.valeurs);

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nombre de commandes',
                data: valeurs,
                backgroundColor: [
                    '#C94E1A',
                    '#E8784A',
                    '#7A5A40',
                    '#FCBF1C',
                    '#3A6A2A',
                ],
            }]
        },
        options: {
            responsive: true,
        }
    });
});