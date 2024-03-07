export function graphSet(canvasId,dataset) {
    // Récupération des données provenant de Blade

    // Palette de couleurs par type de ressource
    const colorPalette = {
        notes: '#FF5733',
        folders: '#3498DB',
        tasks: '#2ECC71',
        projects: '#F1C40F',
        categories: '#8E44AD'
    };

    // Extraction des dates et types de ressources
    const dates = Object.keys(dataset);


    const resourceTypes = ["notes","folders","tasks","projects","categories"];



    // Création des datasets pour Chart.js
    const datasets = resourceTypes.map(type => {
        return {
            label: type,
            backgroundColor: 'transparent',
            borderColor: colorPalette[type],
            data: dates.map(date => dataset[date][type] || 0), // Si aucune donnée, mettre 0
            fill: false
        };
    });

    // Création du graphique avec Chart.js
    var ctx = document.getElementById(canvasId).getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: datasets
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

