$( document ).ready(function() {
   reset()
});
			function reset(){  
				
					var name = $('#name').val('');
					var mail = $('#email').val('');
					var password = $('#password').val('');
					var conpassword = $('#conpassword').val('');
					var phone = $("#phone").val();
										
				}
			   
				  $('#register').on("click",function(){
					//alert("Gm---");
					var name = $('#name').val();
					var email = $('#email').val();
					var password = $('#password').val();
					var conpassword = $('#conpassword').val();
					var phone = $("#phone").val();
					
					var reg = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
					var regex = /^[a-zA-Z ]*$/; 
					var regex2 = /^[0-9]+\.?[0-9]*$/;
							
							if(name == "")
								{
								$("#name").css('border-color','red');
								$('.fn_error_msg').empty().append('firstname is mandatory');
								$("#name").focus();
								setTimeout(function(){
								$("#name").css('border-color','');
								$('.fn_error_msg').empty();
								},5000);
								}else if(regex.test(name) == false)
								{
								$("#name").css('border-color','red');
								$('.fn_error_msg').empty().append('Enter only characters');
								setTimeout(function(){
								$("#name").css('border-color','');
								$('.fn_error_msg').empty();
								},5000);
								}else if(email == "")
								{
								$(".email").css('border-color','red');
								$('.e_error_msg').empty().append('email is mandatory');
								$(".email").focus();
								setTimeout(function(){
								$(".email").css('border-color','');
								$('.e_error_msg').empty();
								},5000);
								}else if(reg.test(email) == false)
								{
								$(".email").css('border-color','red');
								$('.e_error_msg').empty().append('Enter correct email');
								setTimeout(function(){
								$(".email").css('border-color','');
								$('.e_error_msg').empty();
								},5000);

								}
								else if(phone == "")
										{
											
										$("#phone").css('border-color','red');
										$('#ph_error_msg').empty().append('Mobile number is mandatory');
										setTimeout(function(){
										$("#phone").css('border-color','');
										$('#ph_error_msg').empty();
										},3000);
										}
										else if(regex2.test(phone) == false)
										{	
											
										$("#phone").css('border-color','red');
										$('#ph_error_msg').empty().append('Enter only numbers');
										setTimeout(function(){
										$("#phone").css('border-color','');
										$('#ph_error_msg').empty();
										},3000);
										}else if(phone.length !=10)
										{	
											
										$("#phone").css('border-color','red');
										$('#ph_error_msg').empty().append('Please put 10  digit mobile number');
										setTimeout(function(){
										$("#phone").css('border-color','');
										$('#ph_error_msg').empty();
										},3000);
										}else if(password == "")
								{
								$("#password").css('border-color','red');
								$('.p_error_msg').empty().append('password is mandatory');
								$("#password").focus();
								setTimeout(function(){
								$("#password").css('border-color','');
								$('.p_error_msg').empty();
								},5000);
								}else if(conpassword == "")
								{
								$("#conpassword").css('border-color','red');
								$('.cp_error_msg').empty().append('enter confirm password');
								$("#conpassword").focus();
								setTimeout(function(){
								$("#conpassword").css('border-color','');
								$('.cp_error_msg').empty();
								},5000);
								}
								else if(password != conpassword)
								{
								$("#password").css('border-color','red');
								$('.p_error_msg').empty().append('password and confirm password not match');
								setTimeout(function(){
								$("#password").css('border-color','');
								$('.p_error_msg').empty();
								},5000);
								}else {
									
									 $.ajax({
										type: "post",
										url: "add_companydb.php",
										data: {name:name,address:address,contactperson:contactperson,email:email,password:password,phone:phone},
										success: function(data){
											if(data==1)
											   {
												reset();
												alert('registration successfuly done');
												window.open('index.php');
												}
											}
										});
								}
					});

