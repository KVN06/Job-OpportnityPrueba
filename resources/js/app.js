import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // Objeto que contiene todas las referencias a elementos del DOM
    const elements = {
        sidebar: {
            toggle: document.getElementById('sidebar-toggle'),
            container: document.querySelector('.sidebar'),
            toggleIcon: document.querySelector('.sidebar-toggle-label i'),
            configLink: document.querySelector('.sidebar-menu li:nth-child(4) a')
        },
        mainContent: document.querySelector('.main-content'),
        modal: {
            container: document.getElementById('configModal'),
            closeBtn: document.querySelector('.close'),
            saveBtn: document.getElementById('saveConfig')
        },
        config: {
            darkMode: document.getElementById('darkModeToggle'),
            fontSize: document.getElementById('fontSize'),
            language: document.getElementById('language')
        },
        jobs: {
            addBtn: document.getElementById('addJobBtn'),
            container: document.getElementById('jobCards')
        }
    }

    // Función para actualizar el estado del sidebar (abierto/cerrado)
    const updateSidebarState = () => {
        const isChecked = elements.sidebar.toggle.checked
        elements.sidebar.container.style.transform = isChecked ? 'translateX(-87%)' : 'translateX(0)'
        elements.mainContent.style.marginLeft = isChecked ? '0' : (window.innerWidth <= 768 ? '0' : '250px')
        elements.sidebar.toggleIcon.className = isChecked ? 'fas fa-bars' : 'fas fa-times'
    }

    // Objeto que maneja la lógica de configuración
    const configHandlers = {
        // Abre el modal de configuración
        openModal: (e) => {
            if (e) e.preventDefault()
            elements.modal.container.style.display = "block"
            configHandlers.loadSettings()
        },

        // Cierra el modal de configuración
        closeModal: () => {
            elements.modal.container.style.display = "none"
        },

        // Carga las configuraciones guardadas en localStorage
        loadSettings: () => {
            const config = elements.config
            config.darkMode.checked = localStorage.getItem('darkMode') === 'true'
            config.fontSize.value = localStorage.getItem('fontSize') || 'medium'
            config.language.value = localStorage.getItem('language') || 'es'
            configHandlers.applySettings()
        },

        // Guarda las configuraciones en localStorage
        saveSettings: () => {
            const config = elements.config
            const settings = {
                darkMode: config.darkMode.checked,
                fontSize: config.fontSize.value,
                language: config.language.value
            }
            
            Object.entries(settings).forEach(([key, value]) => localStorage.setItem(key, value))
            configHandlers.applySettings()
            configHandlers.closeModal()
            alert('¡Configuraciones guardadas!')
        },

        // Aplica las configuraciones al documento
        applySettings: () => {
            const config = elements.config
            document.body.classList.toggle('dark-mode', config.darkMode.checked)
            const fontSizes = {
                'small': '14px',
                'medium': '16px',
                'large': '18px'
            }
            document.body.style.fontSize = fontSizes[config.fontSize.value] || '16px'
            document.documentElement.lang = config.language.value
        }
    }

    ////////////////////////////////////////////////////////
    // Objeto que maneja la lógica de los trabajos
    const jobHandlers = {
        // Agrega una nueva tarjeta de trabajo
        agregarTrabajo: () => {
            const jobCard = document.createElement("div")
            jobCard.classList.add("job-card")
            jobCard.innerHTML = `
                <div class="card-content">
                    <h3><a href="#">Nuevo Trabajo</a></h3>
                    <p>Descripción breve del trabajo.</p>
                    <div class="job-actions">
                        <button class="edit-btn"><i class="fas fa-edit"></i> Editar</button>
                        <button class="delete-btn"><i class="fas fa-trash"></i> Eliminar</button>
                    </div>
                </div>`

            // Configuración de los botones de acción
            const editBtn = jobCard.querySelector('.edit-btn')
            const deleteBtn = jobCard.querySelector('.delete-btn')
            const titulo = jobCard.querySelector('a')
            const descripcion = jobCard.querySelector('p')

            // Manejador para editar el trabajo
            editBtn.onclick = () => {
                const nuevoTitulo = prompt("Nuevo título:", titulo.textContent)
                const nuevaDescripcion = prompt("Nueva descripción:", descripcion.textContent)
                if (nuevoTitulo) titulo.textContent = nuevoTitulo
                if (nuevaDescripcion) descripcion.textContent = nuevaDescripcion
            }

            // Manejador para eliminar el trabajo
            deleteBtn.onclick = () => confirm("¿Eliminar trabajo?") && jobCard.remove()

            elements.jobs.container.appendChild(jobCard)
        }
    }

    // Configuración de event listeners
    elements.sidebar.toggle.addEventListener('change', updateSidebarState)
    window.addEventListener('resize', updateSidebarState)
    elements.jobs.addBtn?.addEventListener('click', jobHandlers.agregarTrabajo)
    elements.sidebar.configLink?.addEventListener('click', configHandlers.openModal)
    elements.modal.closeBtn?.addEventListener('click', configHandlers.closeModal)
    elements.modal.saveBtn?.addEventListener('click', configHandlers.saveSettings)

    // Event listeners para cambios en la configuración
    elements.config.darkMode?.addEventListener('change', () => {
        document.body.classList.toggle('dark-mode', elements.config.darkMode.checked)
    })

    elements.config.fontSize?.addEventListener('change', function() {
        const fontSizes = {
            'small': '14px',
            'medium': '16px',
            'large': '18px'
        }
        document.body.style.fontSize = fontSizes[this.value] || '16px'
    })

    // Cerrar modal al hacer clic fuera de él
    window.addEventListener('click', (e) => {
        if (e.target === elements.modal.container) {
            configHandlers.closeModal()
        }
    })

    // Inicialización del estado inicial
    updateSidebarState()
    configHandlers.loadSettings()
})
