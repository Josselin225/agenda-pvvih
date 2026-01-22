</div> <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('collapsed');
            });
        });
    </script>
    <script>
            document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('sidebarCollapse');
            const sidebar = document.querySelector('.sidebar');
            
            if(btn && sidebar) {
                btn.addEventListener('click', function() {
                    // Bascule la classe .collapsed sur la sidebar
                    sidebar.classList.toggle('collapsed');
                    
                    // Stocke le choix de l'utilisateur dans le navigateur
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarState', isCollapsed ? 'collapsed' : 'expanded');
                });

                // Optionnel : Restaurer l'état au chargement de la page
                if(localStorage.getItem('sidebarState') === 'collapsed') {
                    sidebar.classList.add('collapsed');
                }
            }
        });
    </script>
</body>
</html>