// Import de la bibliothèque D3.js

// Fonction pour créer le graphique D3 Force
export function createGraph(data) {
    const width = 1600;
    const height = 680;

    // Création du conteneur SVG
    const svg = d3.create("svg")
        .attr("width", width)
        .attr("height", height)
        .attr("viewBox", [-width / 2, -height / 2, width, height])
        .attr("style", "max-width: 100%; height: auto;");

    // Convertir les données en un format utilisable pour D3 Force
    const nodes = [];
    const links = [];

    // Ajout du nœud Root
    nodes.push({ id: 'Root', name: 'Root', color : 'green' });

    // Fonction pour récursivement parcourir les données et les transformer en nœuds et liens
    function parseData(data, parentId) {
        for (const [key, value] of Object.entries(data)) {

            if (Array.isArray(value)) {
                if (value.length === 1 && value[0].hasOwnProperty('file') && value[0].hasOwnProperty('id')) {
                    // Si c'est une note, ajouter le lien vers le parent
                    //console.log("OUIIIIII")
                    //console.log(value[0].id)
                    //console.log(value[0].file)
                    const fileId = value[0].id;
                    const fileName = value[0].file;
                    nodes.push({ id: fileId, name: fileName , color : "steelblue"});
                    links.push({ source: parentId, target: fileId });
                } else {
                    // Si c'est un dossier, continuer à parcourir récursivement
                    parseData(value, parentId);
                }
            } else if (typeof value === 'object' && value !== null) {
                // Si c'est un sous-dossier, créer un nouveau nœud pour lui et parcourir récursivement
                let nodeId = `${parentId}-${key}`;

                // Fichier seul
                if(value.hasOwnProperty("file") && value.hasOwnProperty("id")) nodes.push({ id: nodeId, name: value.file, color : "steelblue" });
                else {
                    console.log(key)
                    console.log(nodeId)

                    if(key === "content") nodeId = `${parentId}`;
                    else nodes.push({ id: nodeId, name:  key , color : "orange"});
                }

                links.push({ source: parentId, target: nodeId });
                parseData(value, nodeId);
            }
        }
    }

    // Appeler la fonction pour analyser les données
    parseData(data, 'Root');

    // Création du graphique D3 Force
    const simulation = d3.forceSimulation(nodes)
        .force("link", d3.forceLink(links).id(d => d.id).distance(100))
        .force("charge", d3.forceManyBody().strength(-50))
        .force("center", d3.forceCenter(0, 0));

    // Ajouter les liens
    const link = svg.append("g")
        .selectAll("line")
        .data(links)
        .enter().append("line")
        .attr("stroke", "#999")
        .attr("stroke-opacity", 0.6)
        .attr("stroke-width", 1);

    // Ajouter les nœuds
    const node = svg.append("g")
        .selectAll("circle")
        .data(nodes)
        .enter().append("circle")
        .attr("r", 5)
        .attr("fill", d => d.color) // Utilisez la propriété color pour définir la couleur de remplissage
        .call(drag(simulation));


    // Ajouter les noms des nœuds
    const labels = svg.append("g")
        .selectAll("text")
        .data(nodes)
        .enter().append("text")
        .text(d => d.name)
        .attr("text-anchor", "middle")
        .attr("dy", 15); // Ajuster la position verticale des étiquettes

    // Mettre à jour les positions des éléments à chaque tick de la simulation
    simulation.on("tick", () => {
        link
            .attr("x1", d => d.source.x)
            .attr("y1", d => d.source.y)
            .attr("x2", d => d.target.x)
            .attr("y2", d => d.target.y);

        node
            .attr("cx", d => d.x)
            .attr("cy", d => d.y);

        // Mettre à jour les positions des étiquettes
        labels
            .attr("x", d => d.x)
            .attr("y", d => d.y);
    });

    // Fonction pour permettre le déplacement des nœuds
    function drag(simulation) {
        function dragstarted(event) {
            if (!event.active) simulation.alphaTarget(0.3).restart();
            event.subject.fx = event.subject.x;
            event.subject.fy = event.subject.y;
        }

        function dragged(event) {
            event.subject.fx = event.x;
            event.subject.fy = event.y;
        }

        function dragended(event) {
            if (!event.active) simulation.alphaTarget(0);
            event.subject.fx = null;
            event.subject.fy = null;
        }

        return d3.drag()
            .on("start", dragstarted)
            .on("drag", dragged)
            .on("end", dragended);
    }

    const zoom = d3.zoom()
        .scaleExtent([0.1, 10]) // Définir les limites de zoom
        .on("zoom", zoomed);

    // Appliquer le zoom au conteneur SVG
    svg.call(zoom)
        .on("wheel", zoomed);

// Fonction de zoom en fonction de l'événement de la souris
    function zoomed(event) {
        // Récupérer la transformation du zoom actuel
        const transform = event.transform;

        // Appliquer la transformation au conteneur SVG
        svg.attr("transform", transform);
    }

    return svg.node();
}
