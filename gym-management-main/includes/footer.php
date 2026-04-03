<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if(sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            if(window.innerWidth > 768) {
                mainContent.classList.toggle('expanded');
            }
        });
    }

    // Mobile check
    function checkWidth() {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('active');
            mainContent.classList.remove('expanded');
        } else {
            sidebar.classList.add('active');
            mainContent.classList.add('expanded');
        }
    }
    
    // Initial check
    // checkWidth();
    // window.addEventListener('resize', checkWidth);
</script>
</body>
</html>
