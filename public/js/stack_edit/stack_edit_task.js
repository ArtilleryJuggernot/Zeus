const styleContent = `

/* StackEdit CSS */

.stackedit-container {
  position: relative;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 0;
}

.stackedit-hidden-container {
  display: none; /* Ne pas afficher la zone cachée de StackEdit */
}

.stackedit-iframe-container {
  position: relative; /* Changer la position à relative */
  border-radius: 5px; /* Ajouter un bord arrondi */
    height : 700px;

}

.stackedit-iframe {
  width: 100%; /* Ajuster la largeur */
  height: 100%; /* Ajuster la hauteur */
  border: none; /* Supprimer la bordure */
  border-radius: 5px; /* Ajouter un bord arrondi */
}

.stackedit-close-button {
  display: none; /* Ne pas afficher le bouton de fermeture */
}

`;

let createStyle = () => {
    const styleEl = document.createElement('style');
    styleEl.type = 'text/css';
    styleEl.innerHTML = styleContent;
    document.head.appendChild(styleEl);
    createStyle = () => {}; // Create style only once
};

const containerHtml = `
<div class="stackedit-iframe-container">
  <iframe class="stackedit-iframe"></iframe>
  <a href="javascript:void(0)" class="stackedit-close-button" title="Close">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="100%" height="100%">
      <path fill="#777" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
    </svg>
  </a>
</div>
`;

const origin = `${window.location.protocol}//${window.location.host}`;
const urlParser = document.createElement('a');

class Stackedit {
    $options = {
        url: 'https://stackedit.io/app',
    };

    constructor(opts = {}) {
        // Override options
        Object.keys(opts).forEach((key) => {
            this.$options[key] = opts[key];
        });
    }

    // For emitting events
    $listeners = {};
    $trigger(type, payload) {
        const listeners = this.$listeners[type] || [];
        // Use setTimeout as a way to ignore errors
        listeners.forEach(listener => setTimeout(() => listener(payload), 1));
    }

    on(type, listener) {
        const listeners = this.$listeners[type] || [];
        listeners.push(listener);
        this.$listeners[type] = listeners;
    }

    off(type, listener) {
        const listeners = this.$listeners[type] || [];
        const idx = listeners.indexOf(listener);
        if (idx >= 0) {
            listeners.splice(idx, 1);
            if (listeners.length) {
                this.$listeners[type] = listeners;
            } else {
                delete this.$listeners[type];
            }
        }
    }

    openFile(file = {}, silent = false) {
        // Close before opening a new iframe
        this.close();

        // Make StackEdit URL
        urlParser.href = this.$options.url;
        this.$origin = `${urlParser.protocol}//${urlParser.host}`; // Save StackEdit origin
        const content = file.content || {};
        const params = {
            origin,
            fileName: file.name,
            contentText: content.text,
            contentProperties: !content.yamlProperties && content.properties
                ? JSON.stringify(content.properties) // Use JSON serialized properties as YAML properties
                : content.yamlProperties,
            silent,
        };
        const serializedParams = Object.keys(params)
            .map(key => `${key}=${encodeURIComponent(params[key] || '')}`)
            .join('&');
        urlParser.hash = `#${serializedParams}`;

        // Make the iframe
        createStyle();
        this.$containerEl = document.createElement('div');
        this.$containerEl.className = silent
            ? 'stackedit-hidden-container'
            : 'stackedit-container';
        this.$containerEl.innerHTML = containerHtml;
        document.getElementById("editor_md").appendChild(this.$containerEl);
        //document.body.appendChild(this.$containerEl);

        // Load StackEdit in the iframe
        const iframeEl = this.$containerEl.querySelector('iframe');
        iframeEl.src = urlParser.href;

        // Add close button handler
        const closeButton = this.$containerEl.querySelector('a');
        closeButton.addEventListener('click', () => this.close());

        // Add message handler
        this.$messageHandler = (event) => {
            console.log("Nouveau trigger")
                switch (event.data.type) {
                    case 'fileChange':
                        // Trigger fileChange event
                        this.$trigger('fileChange', event.data.payload);

                        break;

                }

        };
        window.addEventListener('message', this.$messageHandler);

        if (!silent) {
            // Remove body scrollbars
            document.body.className += ' stackedit-no-overflow';
        }
    }

    close() {
        if (this.$messageHandler) {
            // Clean everything
            window.removeEventListener('message', this.$messageHandler);
            document.body.removeChild(this.$containerEl);

            // Release memory
            this.$messageHandler = null;
            this.$containerEl = null;

            // Restore body scrollbars
            document.body.className = document.body.className.replace(/\sstackedit-no-overflow\b/, '');

            // Trigger close event
            this.$trigger('close');
        }
    }
}



export function initStackEdit(){
    console.log("Initialisation")
    const el = document.querySelector('textarea');
    const stackedit = new Stackedit();
// Open the iframe
    stackedit.openFile({
        name: 'Filename',
        content: {
            text: content // Contenu passé depuis la note / tâche en Laravel
        },
    });

// Listen to StackEdit events and apply the changes to the textarea.
    stackedit.on('fileChange', (file) => {
        fetch('/save-task', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf // Si vous utilisez le jeton CSRF
            },
            body: JSON.stringify({
                content: file.content.text,
                task_id: parseInt(task_id),
                user_id: parseInt(user_id),
                perm: perm
            })

        })
            .then(response => {

                if (response.ok) {
                    // Afficher un message de succès ou exécuter d'autres actions si nécessaire
                    console.log('Contenu de la tâche sauvegardé avec succès!');
                } else {
                    console.error('Erreur lors de la sauvegarde du contenu.');
                }
            })
            .catch(error => {
                console.error('Erreur de connexion:', error);
            });
    });

}
initStackEdit();








//document.getElementById("editor_md").innerHTML += stack.innerHTML;

