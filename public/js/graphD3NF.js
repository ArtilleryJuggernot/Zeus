// Import de la bibliothèque D3.js

// Fonction pour créer le graphique D3 Force
export function createGraph(data, options = {}) {
    const width = 1600;
    const height = 900;

    // Création du conteneur SVG
    const svg = d3.create("svg")
        .attr("width", "100%")
        .attr("height", "100%")
        .attr("viewBox", [-width / 2, -height / 2, width, height])
        .style("background", "none")
        .style("cursor", "grab");

    // Groupe pour appliquer le zoom/pan
    const container = svg.append("g");

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
        .range([12, 28]); // Plage de tailles de nœud souhaitée

    // Création du graphique D3 Force
    const simulation = d3.forceSimulation(nodes)
        .force("link", d3.forceLink(links).id(d => d.id).distance(200))
        .force("charge", d3.forceManyBody().strength(-300))
        .force("center", d3.forceCenter(0, 0))
        .force("collision", d3.forceCollide().radius(d => Math.max(32, degreeScale(nodeDegrees[d.id]) * 2)).iterations(2))
        .alphaDecay(0.03);

    // Pour une disposition initiale en cercle autour du parent (optionnel, pour l'effet "Obsidian")
    // On place les enfants d'un même parent sur un cercle
    function arrangeNodesInCircle() {
        const parentToChildren = {};
        links.forEach(link => {
            if (!parentToChildren[link.source]) parentToChildren[link.source] = [];
            parentToChildren[link.source].push(link.target);
        });
        Object.keys(parentToChildren).forEach(parentId => {
            const children = parentToChildren[parentId];
            const angleStep = (2 * Math.PI) / children.length;
            children.forEach((childId, i) => {
                const child = nodes.find(n => n.id === childId);
                const parent = nodes.find(n => n.id === parentId);
                if (child && parent) {
                    const angle = i * angleStep;
                    const radius = 300; // Rayon du cercle
                    child.x = parent.x + Math.cos(angle) * radius;
                    child.y = parent.y + Math.sin(angle) * radius;
                }
            });
        });
    }
    arrangeNodesInCircle();
    simulation.alpha(1).restart();

    // Ajouter les liens
    const link = container.append("g")
        .selectAll("line")
        .data(links)
        .enter().append("line")
        .attr("stroke", "#999")
        .attr("stroke-opacity", 0.6)
        .attr("stroke-width", 1.5);

    // Ajouter les nœuds avec la taille de cercle basée sur le degré
    const node = container.append("g")
        .selectAll("circle")
        .data(nodes)
        .enter().append("circle")
        .attr("r", d => Math.max(12, Math.min(28, degreeScale(nodeDegrees[d.id])))) // Assurez-vous que la taille est comprise entre 12 et 28
        .attr("fill", d => d.color) // Utilisez la propriété color pour définir la couleur de remplissage
        .attr("class", "node-glow")
        .style("cursor", "pointer")
        .call(drag(simulation))
        .on("mouseover", function() { d3.select(this).classed("node-selected", true); })
        .on("mouseout", function() { d3.select(this).classed("node-selected", false); })
        .on("click", (event, d) => {
            if (options.onNodeSelect) options.onNodeSelect(d.name);
            window.location.href = d.link;
        });

    // Ajouter les noms des nœuds
    const labels = container.append("g")
        .selectAll("text")
        .data(nodes)
        .enter().append("text")
        .text(d => d.name)
        .attr("fill", "#111")
        .attr("font-size", 22)
        .attr("font-weight", "bold")
        .attr("class", "nodes-inst")
        .attr("text-anchor", "middle")
        .attr("dy", 38)
        .style("pointer-events", "none")
        .style("text-shadow", "0 2px 8px #fff, 0 1px 0 #fff");

    // Mettre à jour les positions des éléments à chaque tick de la simulation
    simulation.on("tick", () => {
        link
            .attr("x1", d => d.source.x)
            .attr("y1", d => d.source.y)
            .attr("x2", d => d.target.x)
            .attr("y2", d => d.target.y);

        node.attr("cx", d => d.x).attr("cy", d => d.y);
        labels.attr("x", d => d.x).attr("y", d => d.y);
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

    // Pan/Zoom fluide
    const zoom = d3.zoom()
        .scaleExtent([0.1, 3])
        .on("zoom", (event) => {
            container.attr("transform", event.transform);
            // Affichage conditionnel des labels selon le zoom
            const showLabels = event.transform.k > 0.5;
            labels.style("display", showLabels ? "block" : "none");
        });
    svg.call(zoom);

    // Contrôles externes
    if (options.controls) {
        options.controls.zoomIn.onclick = () => svg.transition().call(zoom.scaleBy, 1.2);
        options.controls.zoomOut.onclick = () => svg.transition().call(zoom.scaleBy, 0.8);
        options.controls.center.onclick = () => svg.transition().call(zoom.transform, d3.zoomIdentity);
    }

    // Pan à la souris (drag sur le fond)
    svg.on("mousedown", function(event) {
        if (event.target.tagName === 'svg') {
            svg.style("cursor", "grabbing");
        }
    }).on("mouseup", function() {
        svg.style("cursor", "grab");
    });

    return svg.node();
}
