document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('#leaderboard tr');
    rows.forEach(function(row) {
        row.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            window.location.href = url;
        });
    });
});
