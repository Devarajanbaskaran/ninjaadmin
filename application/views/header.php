<header id="page-topbar">
   <div class="navbar-header">
      <div class="d-flex w-100 justify-content-between">
         <!-- LOGO -->
         <div class="navbar-brand-box">
            <a href="#" class="logo logo-dark">
            <span class="logo-sm">
            <img src="<?= base_url('public/img/ninja-logo.png') ?>" alt="" height="32">
            </span>
            <span class="logo-lg">
            <img src="<?= base_url('public/img/ninja-logo.png') ?>" alt="" height="20">
            </span>
            </a>
            <a href="#" class="logo logo-light">
            <span class="logo-sm">
            <img src="<?= base_url('public/img/ninja-logo.png') ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
            <img src="<?= base_url('public/img/ninja-logo.png') ?>" alt="" height="20">
            </span>
            </a>
         </div>
         <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn toggleVerIcon">
         <i class="fa fa-fw fa-bars"></i>
         </button>
      </div>
      <div class="d-md-flex d-none w-100 justify-content-end">
         <div class="dropdown d-inline-block d-lg-none ms-2">
            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="uil-search"></i>
            </button>
         </div>
         <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="uil-bell"></i>
            <span class="badge bg-danger rounded-pill">3</span>
            </button>
         </div>
         <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="rounded-circle header-profile-user" src="<?= base_url('public/img/avatars/10.jpg') ?>"
               alt="Header Avatar">
            <span class="d-none d-xl-inline-block ms-1 fw-medium font-size-15">Super Admin</span>
            <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
               <!-- item-->
               <a class="dropdown-item" href="#"><i class="uil uil-user-circle font-size-18 align-middle text-muted me-1"></i> <span class="align-middle">View Profile</span></a>
               <a class="dropdown-item" href="<?= base_url('login/logout') ?>"><i class="uil uil-sign-out-alt font-size-18 align-middle me-1 text-muted"></i> <span class="align-middle">Sign out</span></a>
            </div>
         </div>
      </div>
   </div>
</header>
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
   <!-- LOGO -->
   <div class="navbar-brand-box">
      <a href="#" class="logo logo-dark">
      <span class="logo-sm">
      <img src="<?= base_url('public/img/ninja-logo-sm.png') ?>" alt="" width='28'>
      </span>
      <span class="logo-lg">
      <img src="<?= base_url('public/img/ninja-logo.png') ?>" alt="" width='150px'>
      </span>
      </a>
      <a href="#" class="logo logo-light">
      <span class="logo-sm">
      <img src="<?= base_url('public/img/ninja-logo-sm.png') ?>" alt="" width='28'>
      </span>
      <span class="logo-lg">
      <img src="<?= base_url('public/img/ninja-logo.png') ?>" alt="" width='150px'>
      </span>
      </a>
   </div>
   <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn toggleVerIcon">
   <i class="fa fa-fw fa-bars"></i>
   </button>
   <div data-simplebar class="sidebar-menu-scroll">
      <!--- Sidemenu -->
      <div id="sidebar-menu">
         <!-- Left Menu Start -->
         <ul class="metismenu list-unstyled" id="side-menu">
            <li>
               <a href="#">
               <i class="uil-tachometer-fast"></i>
               <span>Dashboard</span>
               </a>
            </li>
            <li class="menu-title">Super Admin</li>
            <li class="<?php ($page_id == 'organization') ? 'mm-active' : '' ?>">
               <a href="<?= base_url('organization/') ?>" class="waves-effect  <?php ($page_id == 'ticket') ? 'mm-active' : '' ?>">
               <i class="uil-calculator-alt"></i>
               <span>Manage Organization</span>
               </a>
            </li>
            <li class="<?php ($page_id == 'tickets') ? 'mm-active' : '' ?>">
               <a href="<?= base_url('ticket/') ?>" class="waves-effect <?php ($page_id == 'ticket') ? 'mm-active' : '' ?>">
               <i class="uil-chart-pie-alt"></i>
               <span>Assign Tickets</span>
               </a>
            </li>
         </ul>
      </div>
      <!-- Sidebar -->
   </div>
</div>
<!-- Left Sidebar End -->