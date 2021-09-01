<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
   <?php include('top.php') ?>  
    
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
                           <h4 class="mb-0">All Tickets </h4>
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
                              <div class="row">
                                 <div class="col-xl-3 col-sm-6">
                                    <select class="select2" id="filter_organization" onchange="generateoption(this.value);"  >
                                       <option value="0">Filter by Organization</option>
                                        <?php
                                          foreach ($organization as $o) {                                             
                                             echo '<option value="'.$o->id.'"  >'.ucfirst($o->name).'</option>';
                                          }
                                       ?>
                                    </select>
                                 </div>
                                 <div class="col-xl-3 col-sm-6 mt-sm-0 mt-3"> 
                                    <select class="form-control select2-multiple" id="filter_assignee" multiple="" data-placeholder="Filter by Assignee">
                                       <option></option>
                                       <?php
                                          foreach ($assignee as $a) { 
                                             echo '<option value="'.$a->id.'" class="opt"  data-organiztion="'.$a->organization_id.'" >'.$a->email.'</option>';
                                          }
                                       ?>
                                    </select>
                                 </div>
                                 <div class="col-xl-3 col-sm-6 mt-xl-0 mt-3">
                                    <select class="form-control select2-multiple" id="filter_category" multiple="" data-placeholder="Filter by Category" >
                                       <option></option>
                                       <?php
                                          foreach ($category as $c) { 
                                             echo '<option value="'.$c->id.'" class="opt" data-organiztion="'.$c->organization_id.'" >'.ucfirst($c->category_name).' ('.ucfirst($c->organization_name).')</option>';
                                          }
                                       ?>
                                    </select>
                                 </div>
                                 <div class="col-xl-2 col-sm-6 mt-xl-0 mt-3">
                                    <select class="form-control select2-multiple" id="filter_status" multiple="" data-placeholder="Filter by Status" >
                                       <option></option>
                                       <?php
                                          foreach ($ticket_status as $t){ 
                                             echo '<option value="'.$t['id'].'"  >'.ucfirst($t['text']).'</option>';
                                          }
                                       ?>
                                    </select>
                                 </div>

                                 <div class="col-xl-1 col-sm-6 mt-xl-0 mt-3">
                                    <input type="reset" class="btn btn-light" value="Clear" onclick="resetFilter();">
                                 </div>
                                 <div class="col-12">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                    <hr>
                                 </div>
                                 <div class="col-12">
                                    <div class="table-responsive">
                                       <table class="table table-bordered dt-responsive nowrap" id="ticket_table" style="border-collapse: collapse; border-spacing: 0; width: 100%; cursor: pointer;">
                                          <thead>
                                             <tr>
                                                <th class="all">ID#</th>
                                                <th class='min-100 all twoLastTd'>Summary</th>
                                                <th>Assignee</th>
                                                <th>Creator</th>
                                                <th>Organization</th>
                                                <th>Priority</th>
                                                <th>Category</th>
                                                <th>Due</th>
                                                <th>Status</th>
                                                <th>Last Updated</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                              
                                              
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
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
      <script src="<?= base_url('public/assets/libs/node-waves/waves.min.js') ?>"></script> 
      <script src="<?= base_url('public/assets/libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script> 
      <script src="<?= base_url('public/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') ?>"></script> 
      <script src="<?= base_url('public/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') ?>"></script> 
      <script src="<?= base_url('public/assets/libs/select2/js/select2.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/libs/spectrum-colorpicker2/spectrum.min.js') ?>"></script>
      <script src="<?= base_url('public/assets/js/fSelect.js') ?>"></script>  
      <script src="<?= base_url('public/assets/js/app.js') ?>"></script> 
      <script>
         const opt_organization = $('#filter_organization').html();
         const opt_assignee = $('#filter_assignee').html();
         const opt_category = $('#filter_category').html();
         const opt_status = $('#filter_status').html();
         const custom_data = <?php echo json_encode($temp); ?>;
          

         var opt = null; 
         $(document).ready(function() {
           $('.select2').select2();
           $('.select2-multiple').fSelect({
             placeholder: function() {
               return $(this).parents('.fs-wrap').find('select').attr('data-placeholder');
             },
             showSearch: false
           });
         });

         function generateoption(i) {
           if (i == 0) {
             $('#filter_category').html(opt_category);
             $('#filter_assignee').html(opt_assignee);
           } else {
             
             $.each(custom_data[i]['category'], function(n, m) {
               opt = opt + '<option value="' + m["id"] + '">' + m['category_name'] + ' (' + m['organization_name'] + ')</option>';
             });
             $('#filter_category').html(opt);
             opt = '';
             $.each(custom_data[i]['assignee'], function(n, m) {
               opt = opt + '<option value="' + m["id"] + '">' + m['email'] + '</option>';
             });
             $('#filter_assignee').html(opt);
           }
           multiSelectReload('#filter_assignee');
           multiSelectReload('#filter_category');
           $('#ticket_table').DataTable().ajax.reload();
         }

         function multiSelectReload(element) {
           $(element).fSelect('reload');
           $.each($(element).closest('.fs-wrap').find('.fs-selectAll'), function(t, s) {
             if (t > 0) $(element).closest('.fs-wrap').find('.fs-selectAll')[t].remove();
           });
         }

         function resetFilter() {
           $('#filter_organization').html(opt_organization);
           $('#filter_category').html(opt_category);
           $('#filter_assignee').html(opt_assignee);
           $('#filter_status').html(opt_status);
           $('#filter_organization').select2();
           multiSelectReload('#filter_assignee');
           multiSelectReload('#filter_category');
           multiSelectReload('#filter_status');
           $('#ticket_table').DataTable().ajax.reload();
         } 


     var table =  $('#ticket_table').DataTable({
           "aaSorting": [
             [0, 'desc']
           ],
           "columnDefs": [
                   { "width": "10%", "targets": 0 },
                   { "width": "40%", "targets": 1 },
                   { "width": "20%", "targets": 2 },
                   { "width": "20%", "targets": 3 } ,
            ],
            'fixedColumns': true,
           "pageLength": 10,
           "serverSide": true,
           "processing": true,
            
           ajax: {
              url: '<?=base_url()?>ticket/list',
              type: 'POST',
              data: function ( d ) {
                  d.csrf_token = $("input[name=csrf_token]").val();
                  d.filter_organization = $('#filter_organization').val();
                  d.filter_category = $('#filter_category').val();
                  d.filter_assignee = $('#filter_assignee').val();
                  d.filter_status = $('#filter_status').val();
            }
          },
           "rowCallback": function( row, data, settings ) {  
               if(data[2] == '' || data[2] == 0 || data[2] == null){
                  opt = '<option value="">-Select-</option>';
                   $.each(custom_data[data[10]]['assignee'], function(n, m) {
                     opt = opt + '<option value="' + m["id"] + '">' + m['email'] + '</option>';
                   });
                  $('td:eq(2)', row).html('<select class="select2">'+opt+'</select>');
                 
               }   
               var priority = data[5].toLowerCase();
               $('td:eq(5)', row).html('<span class="'+btnclass[priority]+'">'+data[5]+'</span>'); 
                

           },
            "drawCallback": function( settings ) {
          var response = settings.json;
                 $("input[name=csrf_token]").val(response.token); 
                 console.log(response.token);
    } 
         });

var btnclass = {"low":"badge bg-success","medium":"badge bg-info","high":"badge bg-danger"};

 
$('#filter_category,#filter_assignee,#filter_status').on('change',function(e){
   if($('#filter_category').val().length == 0 && $('#filter_assignee').val().length == 0){
      console.log("not run");
   }else{
      $('#ticket_table').DataTable().ajax.reload();
   } 
});

table.on( 'draw', function (settings) { 
   $('.select2').select2(); 
});
 
      </script>
   </body>
</html>