$(document).ready(function() {
    // Gestion du bouton rétracter
    $('#sidebarCollapse').on('click', function() {
        $('.sidebar').toggleClass('collapsed');
    });
});