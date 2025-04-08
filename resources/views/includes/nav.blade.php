<input type="checkbox" id="sidebar-toggle" class="sidebar-toggle">
            <div class="sidebar">
                <label for="sidebar-toggle" class="sidebar-toggle-label">
                    <i class="fas fa-bars"></i>
                </label>
                <div class="sidebar-content">
                    <div class="sidebar-header">
                    </div>
                    <ul class="sidebar-menu">
                        <li><a href="principal.html"><i class="fas fa-home"></i> Principal</a></li>
                        <li><a href="mensajes.html"><i class="fas fa-envelope"></i> Mensajes</a></li>
                        <li><a href="#"><i class="fas fa-comments"></i> Foros/Capacitaciones</a></li>
                        <li><a href="#"><i class="fas fa-cog"></i> Configuraciones</a></li>
                    </ul>
                    <div class="sidebar-footer">
                        <div class="app-card">
                            <h3>App Móvil</h3>
                            <p>Descarga nuestra app para iOS y Android</p>
                        </div>
                        <div class="app-card">
                            <h3>Thoughts Time</h3>
                            <p>Inspírate con frases motivadoras cada día</p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="configModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Configuraciones</h2>
                        <span class="close">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="config-section">
                            <h3>Apariencia</h3>
                            <div class="config-option">
                                <label>Modo Oscuro</label>
                                <label class="switch">
                                    <input type="checkbox" id="darkModeToggle">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="config-option">
                                <label>Tamaño de Fuente</label>
                                <select id="fontSize">
                                    <option value="small">Pequeño</option>
                                    <option value="medium" selected>Mediano</option>
                                    <option value="large">Grande</option>
                                </select>
                            </div>
                        </div>
                        <div class="config-section">
                            <h3>Notificaciones</h3>
                            <div class="config-option">
                                <label>Notificaciones Push</label>
                                <label class="switch">
                                    <input type="checkbox" id="pushNotifications">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="config-option">
                                <label>Notificaciones por Email</label>
                                <label class="switch">
                                    <input type="checkbox" id="emailNotifications">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="config-section">
                            <h3>Idioma</h3>
                            <div class="config-option">
                                <select id="language">
                                    <option value="es">Español</option>
                                    <option value="en">English</option>
                                    <option value="pt">Português</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="saveConfig">Guardar Cambios</button>
                    </div>
                </div>
            </div>