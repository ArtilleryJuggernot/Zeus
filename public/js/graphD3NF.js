// Import de la bibliothèque D3.js

// Fonction pour créer le graphique D3 Force
export function createGraph(data) {
    const width = 1600;
    const height = 900;

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
    nodes.push({ id: 'Root', name: 'Root', color: 'green', link: `/folder_overview` });

    // Fonction pour récursivement parcourir les données et les transformer en nœuds et liens
    function parseData(data, parentId) {
        for (const [key, value] of Object.entries(data)) {

            if (Array.isArray(value)) {
                if (value.length === 1 && value[0].hasOwnProperty('file') && value[0].hasOwnProperty('id')) {
                    // Si c'est une note, ajouter le lien vers le parent
                    const fileId = value[0].id;
                    const fileName = value[0].file;
                    nodes.push({ id: fileId, name: fileName, color: "steelblue", link: `/note_view/${fileId}` });
                    links.push({ source: parentId, target: fileId });
                } else {
                    // Si c'est un dossier, continuer à parcourir récursivement
                    parseData(value, parentId);
                }
            } else if (typeof value === 'object' && value !== null) {
                // Si c'est un sous-dossier, créer un nouveau nœud pour lui et parcourir récursivement
                let nodeId = `${parentId}-${key}`;

                // Fichier seul
                if (value.hasOwnProperty("file") && value.hasOwnProperty("id")) nodes.push({ id: nodeId, name: value.file, color: "steelblue", link: `/note_view/${value.id}` });
                else {
                    if (key === "content") nodeId = `${parentId}`;
                    else nodes.push({ id: nodeId, name: key, color: "orange", link: `/view_folder/${value.id}` });
                }

                links.push({ source: parentId, target: nodeId });
                parseData(value, nodeId);
            }
        }
    }

    // Appeler la fonction pour analyser les données
    parseData(data, 'Root');

    // Calculer le degré de chaque nœud
    const nodeDegrees = {};
    links.forEach(link => {
        nodeDegrees[link.source] = (nodeDegrees[link.source] || 0) + 1;
        nodeDegrees[link.target] = (nodeDegrees[link.target] || 0) + 1;
    });

    // Définir l'échelle logarithmique pour la taille du cercle en fonction du degré
    const degreeScale = d3.scaleLog()
        .domain([1, d3.max(Object.values(nodeDegrees))])
        .range([5, 15]); // Plage de tailles de nœud souhaitée

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

    // Ajouter les nœuds avec la taille de cercle basée sur le degré
    const node = svg.append("g")
        .selectAll("circle")
        .data(nodes)
        .enter().append("circle")
        .attr("r", d => Math.max(8, Math.min(20, degreeScale(nodeDegrees[d.id])))) // Assurez-vous que la taille est comprise entre 5 et 15
        .attr("fill", d => d.color) // Utilisez la propriété color pour définir la couleur de remplissage
        .call(drag(simulation))
        .on("click", (event, d) => {
            // Gérer l'événement de clic ici
            console.log("Node clicked:", d);
            // Rediriger vers l'URL spécifique au nœud, par exemple
            window.location.href = d.link;
        });

    // Ajouter les noms des nœuds
    const labels = svg.append("g")
        .selectAll("text")
        .data(nodes)
        .enter().append("text")
        .text(d => d.name)
        .attr("fill","white")
        .attr("class", "nodes-inst")
        .attr("text-anchor", "middle")
        .attr("dy", 25); // Ajuster la position verticale des étiquettes

    // Mettre à jour les positions des éléments à chaque tick de la simulation
    simulation.on("tick", () => {
        link
            .attr("x1", d => d.source.x)
            .attr("y1", d => d.source.y)
            .attr("x2", d => d.target.x)
            .attr("y2", d => d.target.y);

        node.attr("transform", d => `translate(${d.x},${d.y})`); // Utiliser "transform" pour positionner les nœuds

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
