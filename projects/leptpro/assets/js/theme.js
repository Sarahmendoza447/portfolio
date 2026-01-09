document.addEventListener('DOMContentLoaded', function() {
    // Apply saved theme on load
    const savedTheme = localStorage.getItem('colorTheme') || 'default';
    document.body.className = `theme-${savedTheme}`;
});
