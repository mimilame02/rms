<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center pl-3">
        <a class="navbar-brand brand-logo mr-5"><img src="../img/logo.svg" class="mr-2" alt="logo"/>
        <span class="menu-title">Sofiyyah</span>
        </a>
        <a class="navbar-brand brand-logo-mini"><img src="../img/logo.svg" alt="logo"/></a>
      </div>

      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center toggle-sidebar" id="tenant_sidebar-toggle" type="button" data-toggle="minimize">
          <span class="bx bx-menu"></span>
        </button>
        
        <ul class="navbar-nav navbar-nav-right bs-borderbox">

          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" data-toggle="dropdown">
            <i class='bx bx-bell mx-0'></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="ti-info-alt mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Application Error</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Just now
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-warning">
                    <i class="ti-settings mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Theme</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    Private message
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="ti-user mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">New user registration</h6>
                  <p class="font-weight-light small-text mb-0 text-muted">
                    2 days ago
                  </p>
                </div>
              </a>
            </div>
          </li>
          <div class="text-light px-2">
            <li class="nav-item nav-profile dropdown">
              <div>
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" id="profileDropdown">
                  <img src="../img/userprofile/pic.jpg" alt="profile"/>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                  <a class="dropdown-item">
                    <i class="fas fa-user text-success"></i>
                    Profile
                  </a>
                  <a class="dropdown-item">
                  <i class="fas fa-paint-brush text-success"></i>
                    Theme
                  </a>
                  <a class="dropdown-item">
                  <i class="fas fa-language text-success"></i>
                    Language
                  </a>
                </div>
              </div>
              <div class="card-body">
                <h6 class="font-weight-normal mb-0"><?php echo "<div class='text-capitalize'>{$_SESSION['username']}</div>" ?></h6>
              </div>
            </li>
          </div>
        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="bx bx-menu"></span>
        </button>
      </div>
    </nav>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const sidebarToggle = document.getElementById('tenant_sidebar-toggle');

    // Restore the sidebar state from localStorage
    const sidebarState = localStorage.getItem('sidebarState');
    if (sidebarState === 'maximize') {
      body.classList.add('sidebar-mini', 'sidebar-icon-only');
      sidebarToggle.setAttribute('data-toggle', 'maximize');
    }

    document.addEventListener('click', function (event) {
      const target = event.target;
      const isSidebarToggle = target === sidebarToggle || target.closest('#tenant_sidebar-toggle');

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

