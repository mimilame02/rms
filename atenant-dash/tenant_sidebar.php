<nav class="sidebar sidebar-offcanvas" id="tenant_sidebar">
        <ul class="nav fw-bolder">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
            <i class='bx bx-grid-alt menu-icon' title="Dashboard"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="tenant_ticket.php">
            <i class='bx bxs-rename menu-icon' title="Tickets"></i>
              <span class="menu-title">My Tickets</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="tenant_lease.php">
            <i class='bx bxs-key menu-icon' title="Leases"></i>
              <span class="menu-title">My Lease</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="tenant_invoice.php" aria-expanded="false" aria-controls="auth">
            <i class='bx bxs-book-content menu-icon' title="Invoice"></i>
              <span class="menu-title">My Invoice</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="tenant_tc.php">
            <i class='bx bxs-book-bookmark menu-icon' title="Terms and Condition"></i>
              <span class="menu-title">Terms and Condition</span>
            </a>
          </li>
          <li class="nav-item logout">
            <a class="nav-link" href="../login/logout.php">
            <i class='bx bx-log-out menu-icon' title="Logout"></i>
              <span class="menu-title">Logout</span>
            </a>
          </li>

        </ul>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const sidebarToggle = document.getElementById('sidebar-toggle');

    // Restore the sidebar state from localStorage
    const sidebarState = localStorage.getItem('sidebarState');
    if (sidebarState === 'maximize') {
      body.classList.add('sidebar-mini', 'sidebar-icon-only');
      sidebarToggle.setAttribute('data-toggle', 'maximize');
    }

    document.addEventListener('click', function (event) {
      const target = event.target;
      const isSidebarToggle = target === sidebarToggle || target.closest('#sidebar-toggle');

      if (isSidebarToggle) {
        const dataToggle = sidebarToggle.getAttribute('data-toggle');

        if (dataToggle === 'minimize') {
          body.classList.add('sidebar-mini', 'sidebar-icon-only');
          sidebarToggle.setAttribute('data-toggle', 'maximize');
          localStorage.setItem('sidebarState', 'maximize'); // Save the state to localStorage
        } else {
          body.classList.remove('sidebar-mini', 'sidebar-icon-only');
          sidebarToggle.setAttribute('data-toggle', 'minimize');
          localStorage.removeItem('sidebarState'); // Remove the state from localStorage
        }
      }
    });
  });
</script>

