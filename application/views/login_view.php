<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php include('top.php') ?>  
<style type="text/css">
   .bg{height: 100vh; background-image:url("<?= base_url('public/img/bg.jpeg') ?>");background-size:100% 100%;background-repeat:no-repeat !important;}
</style>
<body class="app flex-row align-items-center d-flex bg" >
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-xl-5 col-lg-7 col-md-9">
            <div class="card-group mb-0">
               <div class="card p-md-4 p-2">
                  <div class="card-body pt-0">
                     <div class='text-center mb-4 mt-md-0 mt-2'>
                        <img src="<?= base_url('public/img/ninja-logo.png') ?>" alt="">
                     </div>
                     <div class='text-center mb-4 mt-md-0 mt-2'>
                        <h2>Sign In</h2>
                        <p class="text-muted">Sign In to your account</p>
                     </div>
                     <div class="alert alert-danger" role="alert" id="err-block-loginform"></div>
                     <form method="post" action="test" class="needs-validation" id="loginForm" novalidate>
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">   

                        <div class="input-group mb-3">
                           <span class="input-group-text"><i class="uil-user"></i></span> 
                           <input type="email" class="form-control" placeholder="Email Id" id="form_username" name="form_username" required>
                           <div class="invalid-feedback">
                              Please enter your email id
                           </div>
                        </div>
                        <div class="input-group mb-4">
                           <span class="input-group-text"><i class="uil-lock-alt"></i></span>
                           <input type="password" class="form-control" id="form_password" name="form_password" placeholder="Password" required>
                           <div class="invalid-feedback">
                              Please enter your password
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-6">
                              <button type="submit" class="btn btn-primary px-4">Login</button>
                           </div>
                           <div class="col-6 text-right d-flex justify-content-end">
                              <button type="button" class="btn btn-link px-0">Forgot password?</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
                
            </div>
         </div>
      </div>
   </div>
   
   <script src="<?= base_url('public/assets/libs/jquery/jquery.min.js') ?>"></script>
   <script src="<?= base_url('public/assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
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
                      login();
                  }
                  form.classList.add('was-validated');
              }, false);
              });
          }, false);
      })();
      $('#err-block-loginform').hide();
      function login(){
         $.ajax({
           url:'login/validate',
           method: 'post',
           data: {
            csrf_token :$("input[name=csrf_token]").val(),
            form_username : $("input[name=form_username]").val().trim(),
            form_password : $("input[name=form_password]").val().trim(),
            },
           dataType: 'json',
           success: function(response){
               $("input[name=csrf_token]").val(response.token);
               if(response.error){
                    $('#err-block-loginform').html(response.messages);
                    $('#err-block-loginform').show();
                     $('#loginForm')[0].reset();
                     $('#loginForm').attr('class','needs-validation');
                     $('#err-block-loginform').fadeOut(8000);
                  }else{
                     document.location.href = response.url;
                  }
               }
         });
      }
      
            
   </script>
</body>
</html>