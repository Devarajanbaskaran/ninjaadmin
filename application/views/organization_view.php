<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
   <?php include('top.php') ?>  
   <link rel="stylesheet" type="text/css" href="<?= base_url('public/css/page/organization.css') ?>"  /> 
   <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
      <div id="layout-wrapper">
         <!-- ============================================================== -->
         <!-- Start Header Content here -->
         <!-- ============================================================== -->
         <?php include('header.php') ?> 
         <!-- ============================================================== -->
         <!-- End Header Content here -->
         <!-- ============================================================== -->
         <!-- ============================================================== -->
         <!-- Start right Content here -->
         <!-- ============================================================== -->
         <div class="main-content">
            <div class="page-content">
               <div class="container-fluid">
                  <!-- start page title -->
                  <div class="row">
                     <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                           <h4 class="mb-0">Manage Organizations</h4>
                           <div class="page-title-right">
                              <ol class="breadcrumb m-0">
                                 <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                 <li class="breadcrumb-item"><a href="javascript: void(0);">Organization</a></li>
                                 <li class="breadcrumb-item active">Tickets</li>
                              </ol>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- end page title -->
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="card">
                           <div class="card-body">
                              <form action='#' class="needs-validation" novalidate>
                                 <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"> 
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <div class="mb-0">
                                          <label class="form-label">Select Organizations</label>
                                          <select class="select2 form-control select2-multiple selectOrg" id="organization" multiple="" data-placeholder="Please Select Your Organization" required>
                                             <?php
                                              foreach ($organization as $organization_data) {
                                                $selected = (in_array($organization_data->id, $favourite_organization)) ? 'selected' : '';
                                                echo '<option value="'.$organization_data->id.'" '.$selected.'>'.ucfirst($organization_data->name).'</option>';
                                              }
                                            ?>
                                             <!-- <option value="select_all">Select All</option> --> 
                                          </select>
                                          <div class="invalid-feedback">
                                             Please Select Your Organization
                                          </div>
                                       </div>
                                    </div>
                                    <div class='mt-4'>
                                       <div>
                                          <button type="submit" class="btn btn-primary waves-effect waves-light me-1 smBtn">
                                          Submit
                                          </button>
                                          <button type="reset" class="btn btn-secondary waves-effect smBtn">
                                          Reset
                                          </button>
                                       </div>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                        <!-- end select2 -->
                     </div>
                  </div>
               </div>
               <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <!-- ============================================================== -->
            <!-- Start Footer Content here -->
            <!-- ============================================================== -->
            <?php include('footer.php'); ?>
            <!-- ============================================================== -->
            <!-- Start Footer Content here -->
            <!-- ============================================================== -->
         </div>
         <!-- end main content-->
      </div>
      <!-- JAVASCRIPT -->
      <script src="<?= base_url('public/assets/libs/jquery/jquery.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script> 
      <script src="<?= base_url('public/assets/libs/metismenu/metisMenu.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/libs/simplebar/simplebar.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/libs/node-waves/waves.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/libs/waypoints/lib/jquery.waypoints.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/libs/jquery.counterup/jquery.counterup.min.js') ?>"></script> 
      <script src="<?= base_url('public/assets/libs/select2/js/select2.full.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/libs/spectrum-colorpicker2/spectrum.min.js') ?>"></script> 
      <script src="<?= base_url('public/assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script> 
      <script src="<?= base_url('public/assets/js/app.js') ?>"></script> 
      <script>
         
               
         (function() {
             'use strict';
             window.addEventListener('load', function() {
                 // Fetch all the forms we want to apply custom Bootstrap validation styles to
                 var forms = document.getElementsByClassName('needs-validation');
                 // Loop over them and prevent submission
                 var validation = Array.prototype.filter.call(forms, function(form) {
                 form.addEventListener('submit', function(event) {
                     if (form.checkValidity() === false) {
                     event.preventDefault();
                     event.stopPropagation();
                     }else{
                         event.preventDefault();
                         saveorganization()
                     }
                     form.classList.add('was-validated');
                 }, false);
                 });
             }, false);
         })();
         
         $('.selectOrg').select2({
             closeOnSelect:false,
             dropdownCssClass: "selectOrgDropdown",
         });  
         
         $(document).on('click',"#selAllCheck",function(){
             if($("#selAllCheck").is(':checked') ){
                 $(".selectOrg > option").prop("selected","selected");
                 $(".selectOrg").trigger("change");
                 $(".selectOrgDropdown li").attr('aria-selected','true')
             }else{
                 $(".selectOrg > option").prop("selected",false);
                  $(".selectOrg").trigger("change");
                  $(".selectOrgDropdown li").attr('aria-selected','false')
              }
         });
         
         $('.selectOrg').on('select2:open', function (e) { 
             var optLen = $('.selectOrg option').length;
             $('.selAllLi').remove()
             var temp = `<li class="select2-results__option selAllLi" role="option" aria-selected="false" data-select2-id="select2-p22d-result-9ipn-select_all">
                             <input type="checkbox" id='selAllCheck'>
                             <label for='selAllCheck' class='mb-0'>Select All</label>
                         </li>`
             $('.selectOrgDropdown').prepend(temp)
             addSelAttr()
         });
         
         $('.selectOrg').on('change', function(){
             addSelAttr()
         })
         
         function addSelAttr(){
             var len = $(".selectOrg").val().length;
             var optLen = $('.selectOrg option').length;
             if( len < optLen){
                 $(".selAllLi").attr('aria-selected','false')
                 document.getElementById("selAllCheck").checked = false;
             }else if(len == optLen){
                 $(".selAllLi").attr('aria-selected','true')
                 document.getElementById("selAllCheck").checked = true;
             }
         }
         
         function saveorganization(){
            $.ajax({
           url:'<?=base_url()?>organization/save',
           method: 'post',
           data: {
            csrf_token :$("input[name=csrf_token]").val(),
            organization_list :  $('#organization').val(), 
            },
           dataType: 'json',
           success: function(response){
               $("input[name=csrf_token]").val(response.token);
               if(response.error){
                     showalert('error','Oops...',response.messages);
                  }else{
                     showalert('success','Success',response.messages);
                     setTimeout(function(){ document.location.href = response.url; }, 3000);

                     
                  }
               }
         });
         }  

         function showalert(icon,title,text){
             swal.fire({
                     icon: icon,
                     title: title,
                     html: text, 

                 });   
         }  
      </script>
   </body>
</html>