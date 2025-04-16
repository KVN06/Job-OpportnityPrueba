document.addEventListener('DOMContentLoaded', () => {
    // Elementos de la UI
    const elements = {
        sidebar: {
            container: document.querySelector('.sidebar'),
            toggle: document.querySelector('.sidebar-toggle'),
            toggleIcon: document.querySelector('.sidebar-toggle-icon'),
        },
        modal: {
            container: document.getElementById('configModal'),
            closeBtn: document.querySelector('.close-modal'),
            saveBtn: document.querySelector('.save-config')
        },
        jobs: {
            container: document.getElementById('jobsContainer'),
            addBtn: document.getElementById('addJobBtn')
        },
        stats: {
            availableJobs: document.querySelector('[data-stat="available-jobs"]'),
            registeredCompanies: document.querySelector('[data-stat="registered-companies"]'),
            placedCandidates: document.querySelector('[data-stat="placed-candidates"]')
        },
        featuredJobs: document.getElementById('featuredJobs')
    };

    // Función para actualizar el estado del sidebar
    const updateSidebarState = () => {
        const isChecked = elements.sidebar.toggle?.checked;
        if (elements.sidebar.container && isChecked !== undefined) {
            elements.sidebar.container.style.transform = isChecked ? 'translateX(-87%)' : 'translateX(0)';
            elements.mainContent.style.marginLeft = isChecked ? '0' : (window.innerWidth <= 768 ? '0' : '250px');
            if (elements.sidebar.toggleIcon) {
                elements.sidebar.toggleIcon.className = isChecked ? 'fas fa-bars' : 'fas fa-times';
            }
        }
    };

    // Manejador de trabajos destacados
    const featuredJobsHandler = {
        loadFeaturedJobs() {
            // Simulación de carga de trabajos destacados
            const featuredJobs = [
                {
                    id: 1,
                    title: 'Desarrollador Frontend',
                    company: 'Tech Solutions',
                    location: 'Remoto',
                    type: 'Tiempo completo'
                },
                {
                    id: 2,
                    title: 'Diseñador UX/UI',
                    company: 'Creative Agency',
                    location: 'Híbrido',
                    type: 'Tiempo completo'
                },
                {
                    id: 3,
                    title: 'Project Manager',
                    company: 'Global Systems',
                    location: 'Presencial',
                    type: 'Tiempo completo'
                }
            ];

            if (elements.featuredJobs) {
                elements.featuredJobs.innerHTML = featuredJobs.map(job => `
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-lg mb-1">
                                    <a href="clasificados.html?job=${job.id}" class="text-blue-600 hover:text-blue-800">
                                        ${job.title}
                                    </a>
                                </h3>
                                <p class="text-gray-600">${job.company}</p>
                            </div>
                            <button class="favorite-btn text-gray-400 hover:text-yellow-500" data-id="${job.id}">
                                <i class="fas fa-star"></i>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="inline-flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-1"></i> ${job.location}
                            </span>
                            <span class="inline-flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock mr-1"></i> ${job.type}
                            </span>
                        </div>
                        <a href="clasificados.html?job=${job.id}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver detalles →
                        </a>
                    </div>
                `).join('');
            }
        }
    };

    // Actualizar estadísticas
    const statsHandler = {
        updateStats() {
            const stats = {
                availableJobs: '5,000+',
                registeredCompanies: '2,500+',
                placedCandidates: '10,000+'
            };

            Object.entries(stats).forEach(([key, value]) => {
                if (elements.stats[key]) {
                    elements.stats[key].textContent = value;
                }
            });
        }
    };

    // Event Listeners
    elements.sidebar.toggle?.addEventListener('change', updateSidebarState);
    window.addEventListener('resize', updateSidebarState);

    // Inicialización
    const init = () => {
        // Cargar trabajos destacados
        featuredJobsHandler.loadFeaturedJobs();
        
        // Actualizar estadísticas
        statsHandler.updateStats();
    };

    init();
});

document.addEventListener('DOMContentLoaded', function() {
    // Probar el sistema de notificaciones después de 2 segundos
    setTimeout(() => {
        if (window.notifications) {
            // Crear algunas notificaciones de prueba
            window.notifications.create(
                'Bienvenido a Job Opportunity',
                'Gracias por usar nuestra plataforma de búsqueda de empleo',
                'alert'
            );

            setTimeout(() => {
                window.notifications.create(
                    'Nueva oferta de trabajo',
                    'Se ha publicado una oferta que coincide con tu perfil',
                    'job',
                    {
                        location: 'Popayán',
                        category: 'Desarrollo Web',
                        type: 'Tiempo_completo'
                    }
                );
            }, 2000);
        }
    }, 2000);
});
