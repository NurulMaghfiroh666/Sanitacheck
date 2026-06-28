<header class="header header-sticky p-0 mb-4 border-0 shadow-sm">
  <div class="container-fluid px-4 py-2 d-flex align-items-center">
    <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -14px; color: var(--cui-body-color);">
      <svg class="icon icon-lg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
        <path fill="currentColor" d="M80 96h352v32H80zm0 144h352v32H80zm0 144h352v32H80z" />
      </svg>
    </button>

    <div class="header-nav ms-auto d-flex align-items-center">
      <div class="d-flex align-items-center me-4 text-body-secondary">
        <svg class="icon me-2 text-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
          <path fill="currentColor" d="M400 464H48V104h192V72H16v424h416V272h-32z" />
          <path fill="currentColor" d="M304 16v32h137.373L188.687 300.687l22.626 22.626L464 70.627V208h32V16z" />
        </svg>
        <span class="small fw-semibold" id="currentDate"></span>
      </div>


    </div>
  </div>
</header>
