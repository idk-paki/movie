<?php include("dataconnection.php"); 
session_start();

			
?>

<!DOCTYPE html>
<html>

    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="assets/css/shikeongcart-bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="assets/css/shikeongcart-font-awesome.min.css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700">
    <!-- owl carousel-->
    <link rel="stylesheet" href="assets/css/shikeongcart-owl.carousel.css">
    <link rel="stylesheet" href="assets/css/shikeongcart-owl.theme.default.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="assets/css/shikeongcart-style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="assets/css/shikeongcart-custom.css">
    <!-- Favicon-->
    
	<!-- socail icon-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <script type="text/javascript">

function confirmation()
{
	var option;
	option=confirm("Leave check out mean you discard all item! do you want?");
	return option;
}
function confirmation2()
{
	var option;
	option=confirm("Do you want to discard all item?");
	return option;
	
	
	
}

</script>
  <style>
.fa {
  padding: 20px;
  font-size: 30px;
  width:30px;
  text-align: center;
  text-decoration: none;
  margin: 5px 3px;
  border-radius: 50%;
}

.fa:hover {
    opacity: 0.7;
}

.fa-facebook {
  background: #3B5998;
  color: white;
}

.fa-twitter {
  background: #55ACEE;
  color: white;
}
.fa-linkedin {
  background: #007bb5;
  color: white;
}

.fa-youtube {
  background: #bb0000;
  color: white;
}

.fa-instagram {
  background: #125688;
  color: white;
}
  </style>
  <body>
    <!-- navbar-->
    <header class="header mb-5">
     
      
      
      
    </header>
    <div id="all"  style="position:absolute;width:100%;">
      <div id="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <!-- breadcrumb-->
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="checkout.php?del&cusid=<?php echo $user_id; ?>" onclick="return confirmation()">Home</a></li>
				 
                  <li aria-current="page" class="breadcrumb-item active">CheckOut</li>
                </ol>
              </nav>
            </div>
            <div id="basket" class="col-lg-9">
              <div class="box">
                <form method="post" action="">
                  <h1>CheckOut cart</h1>
				  <?php 
				  
					$result = mysqli_query($connect, "SELECT * FROM order_item WHERE user_id='$user_id'");
				  $count = mysqli_num_rows($result); ?>
                  <p class="text-muted">You currently have <?php echo $count; ?> item(s) in your cart.</p>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th colspan="2">Product</th>
						  <th>Stock left</th>
                          <th>Quantity</th>
                          <th>Unit price</th>
                          
                          <th colspan="3">Total</th>
                        </tr>
                      </thead>
                      <tbody>
					  <?php
			
						
				
			
			
			$result = mysqli_query($connect, "SELECT * FROM order_item WHERE Customer_Id='$cusId'");
	          $subtotal=0;
			 while($row = mysqli_fetch_assoc($result))
				{
					$subtotal+= $row["all_total"];
					
					 $procheck=$row["Product_code"];
						
						 $Prostock = mysqli_query($connect, "SELECT * from product WHERE Product_code='$procheck'");	
						$prostk = mysqli_fetch_assoc($Prostock);
						

				?>		
                        <tr>
                          <td><img src="../FYP_Admin/pages/image/<?php echo $row["Product_img"]; ?>" ></td>
                          <td><?php echo $row["Name"]; ?></a></td>
						  <td><?php echo $prostk["Product_Quantity"]; ?></td>
                          <td>
                            <input type="text" min=0 value="<?php echo $row["Quantity"]; ?>" class="form-control" disabled>
                          </td>
                          <td>RM<?php echo $row["Price"]; ?></td>
                          <td>RM<?php echo $row["all_total"]; ?></td>
                          </tr>
                      <?php	
				}
			
			?>
                      </tbody>
                      <tfoot>
					  <script>

</script>
                        <tr>
                          <th colspan="4">Order subtotal</th>
						  
                          <th colspan="2"><span id="total" name="Total_Price"  >RM<?php echo $subtotal;?></span></th>
						  
                        </tr>
						
                      </tfoot>
					  
                    </table>
					<a style="border-style: outset;padding:3px;" href="checkout.php?del&cusid=<?php echo $cusId; ?>"
					onclick="return confirmation2();">Discard</a>
					
                  </div>
				  
				  <?php 
				  //if user input
				  if (isset($_GET["ordernow"])) 
				{
					$cusId=$_SESSION['Customer_Id'];
					
					
					
						//get user input by checkout info
						
						
						$Payment_Amount=$subtotal+10;
						$Payment_Method=$_GET["Type"];
						$cardnum=$_GET["card"];
						$shipping_price=10;
						
						$cus_name=$_GET["fullname"];
						$phone=$_GET["phone_number"];
						$email=$_GET["email"];
						$card_expdate=$_GET["expcard"];
						$cvv=$_GET["cvv"];
						$Address=$_GET["Address"];
						
						
						
							
						
						//if read form temporary cart(no paid yet)
							$order_item = mysqli_query($connect, "SELECT * from order_item where Customer_Id='$cusId'");
							
							$kirarecord=mysqli_num_rows($order_item);
							if($kirarecord==0)
							{
								?>
								<script>
								alert("Sorry! your order product item Empty!");
								
								</script>
								<?php
							}
							else
							{	
								
								//show all item in temporary cart
								while($cart = mysqli_fetch_assoc($order_item))
									{
										$Name=$cart["Name"];
										$Quantity=$cart["Quantity"];
										$Product_code=$cart["Product_code"];
										
										
										//read which product in product database 		
										$prodstock = mysqli_query($connect, "SELECT * from product where Product_code='$Product_code'");					
										$prostk = mysqli_fetch_assoc($prodstock);
										
										//count product_stock-cus_want_qty
										$prodcheck=$prostk["Product_Quantity"]-$Quantity;
									
										//check selection is more than product stock?
										//if more than
										if($prodcheck<0)					
										{
											?>
											<script>
											alert("Sorry! your order product item <?php echo $Name ?> is more than our stock have.Please Discard!");
											</script>
											<?php
											
											$false=1;
											//false
										}
										
											//true
											//no more than
										
									
									}
									//end check
						
							
							
						
							
						if($false>0)
						{
							?>
							<script>
							location.assign("checkout.php?addorder&id=<?php echo $custId ?>");
							</script>
							<?php
						}
						else
						{
							
								
							
										
								//(start make new order progress)
								//when already paid and  decide make order	
								//insert into order history					
								mysqli_query($connect,"INSERT INTO order_process(Customer_Id,subtotal,progress)VALUES('$cusId','$subtotal','Pending')");
							
										//read form order_item database where this user(already paid)
										$order_item = mysqli_query($connect, "SELECT * from order_item where Customer_Id='$cusId'");	
										
										
										
										//show all item in order_item database(in order move to order item history)
										while($cart = mysqli_fetch_assoc($order_item))
										{
										$Name=$cart["Name"];
										$Quantity=$cart["Quantity"];
										$Product_price=$cart["Price"];
										$Total_Price=$cart["all_total"];
										$Product_img=$cart["Product_img"];
										$Customer_Id=$cart["Customer_Id"];
										$Product_code=$cart["Product_code"];
										
										$pricetotal=$Total_Price;	
										
												
															//read form  prodstock database inorder to reduce qty 
															$prdsk = mysqli_query($connect, "SELECT * from product where Product_code='$Product_code'");					
															$prosk = mysqli_fetch_assoc($prdsk);
															
															//get category id 
															$Category_Id=$prosk["Category_Id"];
															
										//fomula to reduce prodstock
										$current=$prosk["Product_Quantity"]-$cart["Quantity"];
										mysqli_query($connect,"UPDATE product SET Product_Quantity='$current' WHERE Product_code='$Product_code'");
										
															//read form  order history database where this user and find latest order
															$prosess=mysqli_query($connect, "SELECT * FROM order_process WHERE Customer_Id='$cusId' ORDER BY Order_date DESC");
															$pro = mysqli_fetch_assoc($prosess);
															
															$Order_Id=$pro["Order_Id"];
											
											//insert to order item history
											mysqli_query($connect,"INSERT INTO order_details(Name,Quantity,Price,all_total,shipingPrice,Product_img,Product_code,Customer_Id,Order_Id,Category_Id)
											VALUES('$Name','$Quantity','$Product_price','$Total_Price','10','$Product_img','$Product_code','$Customer_Id','$Order_Id','$Category_Id')");
											
											
											
										}
										//here create one record only
										
										
										
										
															//read form  order history database where this user and find latest order
															$prosess=mysqli_query($connect, "SELECT * FROM order_process WHERE Customer_Id='$cusId' ORDER BY Order_date DESC");
															$pro = mysqli_fetch_assoc($prosess);
															
															$Order_Id=$pro["Order_Id"];
					
								//insert to shipping history							
								mysqli_query($connect,"INSERT INTO shipping(Shipping_Address,Order_Id,Customer_Id,progress)
								VALUES('$Address','$Order_Id','$cusId','Pending')");
								
								//find this user form shiping history
								$shipping=mysqli_query($connect, "SELECT * FROM shipping WHERE Customer_Id='$cusId' ORDER BY Date DESC");
															$ship = mysqli_fetch_assoc($shipping);
															$Shipping_Id=$ship["Shipping_Id"];
								//insert to payment history	
								mysqli_query($connect,"INSERT INTO payment(Payment_Amount,Payment_Method,cardnum,shipping_price,Subtotal,cus_name,phone,email,card_expdate,cvv,Address,Order_Id,Shipping_Id,Customer_Id)
								VALUES('$Payment_Amount','$Payment_Method','$cardnum','$shipping_price','$subtotal','$cus_name','$phone','$email','$card_expdate','$cvv','$Address','$Order_Id','$Shipping_Id','$cusId')");
								
									
								

								//clear temporary cart
								mysqli_query($connect,"DELETE from order_item WHERE Customer_Id='$cusId'");
								////////////////////////////////////////////////////
								///////////////////////////////////////////////////
								//mail receipt function 
								$result = mysqli_query($connect, "SELECT * from customer where Customer_Id='$cusId'");	
								$count = mysqli_num_rows($result);
								
								//the subject
								$sub = "Receipt $Order_Id Transaction ";
								//the message
								$msg = "Thank you Trade with our platform, Press the link to Download your Receipt!
								http://localhost/fyp/user/converttopdf.php?pdf&id=$cusId&orderid=$Order_Id";
								
								//recipient email here
								$rec = $email;
								//send email
								mail($rec,$sub,$msg);
								
								
								//mail receipt function end
								?>
								<script>
								alert("We already send Receipt link to your email");
								location.assign("downloadpdf.php?download&id=<?php echo $cusId?>&orderid=<?php echo $Order_Id?>");
								</script>	
								<?php
						}
					}
				
				
				}
				
				  
				  ?>
				  
                  <!-- /.table-responsive-->
                  
                </form>
              </div>
              <!-- /.box-->
              
            </div>
            <!-- /.col-lg-9-->
            <div class="col-lg-3">
              <div id="order-summary" class="box">
                <div class="box-header">
                  <h3 class="mb-0">Order summary</h3>
                </div>
                <p class="text-muted">Shipping and additional costs are calculated based on the values you have entered.</p>
				<p class="text-muted">The Shipping will out in 12hours,will arrive in 1-2day.</p>
                <div class="table-responsive">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>Order subtotal</td>
                        <th>RM<?php echo $subtotal;?></th>
                      </tr>
                      <tr>
                        <td>Shipping and handling</td>
                        <th>RM10.00</th>
                      </tr>
                      
                      <tr class="total">
                        <td>Total</td>
                        <th>RM<?php echo $subtotal+10;?></th>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              
            </div>
            <!-- /.col-md-3-->
			
			<!DOCTYPE HTML>

<style>

input[type=text],[type=tel],[type=email] {
  width: 90%;
  margin-bottom: 20px;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

.btn {
  background-color: #04AA6D;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}

.btn:hover {
  background-color: #45a049;
}

span.price {
  float: right;
  color: grey;
}

option{
  
  margin-bottom: 20px;
  padding: 12px;
  
}

</style>
<script>


function cardnumber(inputnumber)
{
	var checknum=0;
	var checknum=document.user_form.card.value;
	
	if(checknum=="")
	{
		alert("Please Input card number!");
	}
	else
	{	
	
		if(document.getElementById('AmericanExpress').checked) 
		{ 
	  var cardno = /^(?:3[47][0-9]{13})$/;
	  if(inputnumber.value.match(cardno))
			{
		  return true;
			}
		  else
			{
			
			alert("Not a valid Amercican Express credit card number!");
			
			return false;
			}
		}
		else if(document.getElementById('Visacard').checked)	
		{
		var cardno = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
			if(inputnumber.value.match(cardno))
			{
		  return true;
			}
		  else
			{
			
			alert("Not a valid visa credit card number!");
			
			return false;
			}
		}
		else if(document.getElementById('MasterCard').checked)	
		{
		var cardno = /^(?:5[1-5][0-9]{14})$/;
			if(inputnumber.value.match(cardno))
			{
		  return true;
			}
		  else
			{
			
			alert("Not a valid Master credit card number!");
			
			return false;
			}
		}
	}
}

</script>
<div id="basket" class="col-lg-9">
 <div class="box">
<body onload='document.user_form.card.focus()'>
<form name ="user_form" id="form" method="get" action="">

<fieldset style="width:50%">

<legend><b style="font-size:30px;">Customer Details</b></legend>



<?php 
$custem = mysqli_query($connect, "SELECT * from customer where Customer_Id='$cusId' AND cus_isDelete=0");
$cus = mysqli_fetch_assoc($custem);

?>


<div class="cart-checkout">
<div style="display: flex;">
	<div style="padding-right: 10px; width: 50%;">
		<label>User Name <sup style="color:red;">*</sup></label> 
		<input type="text" required="required" name="fullname" id="first_name" value="<?php echo $cus["cust_name"]; ?>" placeholder="Enter Full Name" class="form-control" autocomplete="off" required>
	</div> 
	
</div> 
<div style="display: flex;">
	<div style="width: 100%;">
		<label>Phone Number <sup style="color:red;">*</sup></label> 
		<input type="tel" required="required" name="phone_number" id="phone_number" maxlength="10" value="0<?php echo $cus["cust_phone"]; ?>" pattern="[0-9]{10}" placeholder="0121234567" title="only ten numbers the lengths of phone number can be accepted" autocomplete="off" class="form-control">
	</div>
</div> 
<div style="display: flex;">
	<div style="width: 100%;">
		<label>Email <sup style="color:red;">*</sup></label> 
		<input type="email" required="required" name="email" title="e.g exmaple@gmail.com" id="email" value="<?php echo $cus["cust_email"]; ?>"  placeholder="Enter E-Mail" autocomplete="off" class="form-control" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" readonly>
	</div>
</div> 
<div style="display: flex;">
	<div style="width: 100%;">
	
	
		<label>Delivery Address <sup style="color:red;">*</sup></label> <br><br>
		<textarea  required autocomplete="on"  name="Address" id="address" placeholder="Enter your address"  cols="40" spellcheck="false" style="height: 112px;" 
		style="width:100%;font-family:Manrope,sans-serif;font-weight: 400;border: 1px;solid #ccc;box-sizing: border-box;padding: 10px overflow:auto;resize:vertical;"><?php echo $cus["cust_address"]; ?></textarea>
	</div>
</div> 
<div style="display: flex;">
	<div style="width: 100%;">
		<label>State <sup style="color:red;">*</sup></label> 
		
<select name="state" id="state" autocomplete="on"required>		
<option value="<?php echo $cus["City"]; ?>"><?php echo $cus["City"]; ?></option> 
<option value="Johor">Johor</option>
<option value="Kuala Lumpur">Kuala Lumpur</option> 
<option value="Kedah">Kedah</option> 
<option value="Kelantan">Kelantan</option> 
<option value="Malacca">Malacca</option> 
<option value="Negeri Sembilan">Negeri Sembilan</option> 
<option value="Pahang">Pahang</option> 
<option value="Penang">Penang</option> 
<option value="Perak">Perak</option> 
<option value="Perlis">Perlis</option> 
<option value="Sabah">Sabah</option> 
<option value="Sarawak">Sarawak</option> 
<option value="Selangor">Selangor</option> 
<option value="Terengganu">Terengganu</option>
</select>
		
		
	</div>
</div>
 <label for="fname">Accepted Cards</label>
            <div class="icon-container">
              <i class="fa fa-cc-visa" style="color:navy;"></i>
              <i class="fa fa-cc-amex" style="color:blue;"></i>
              <i class="fa fa-cc-mastercard" style="color:red;"></i>
              
            </div>
			<br>
			<label for="ccnum"><b>Credit card number</b><sup style="color:red;">*</sup></label>
			<br>
			<b>Please select your Card Type:</b>
			<br>
			  <input type="radio" id="AmericanExpress" name="Type" value="AmericanExpress" required="required">
			  <label >AmericanExpress</label>
			
			
			  <input type="radio" id="Visacard" name="Type" value="Visacard">
			  <label >Visacard</label>
			
			
			  <input type="radio" id="MasterCard" name="Type" value="MasterCard">
			  <label >MasterCard</label>
			
            <input type="text" id="ccnum" name="card" placeholder="1111222233334444" pattern="[0-9]{16}" title="please fill in, atleast 16number" autocomplete="off" required />
			<span id="error1" style="color:red"></span>
			<span id="error2" style="color:red"></span>
			<span id="error3" style="color:red"></span>
            <br>
			<label style=""for="expmonth" ><b>Card Exp Date</b><sup style="color:red;">*</sup></label>
			 <br>
            <input type="date" id="expmonth" name="expcard" required placeholder="2022" pattern="[0-9]{2}/[0-9]{4}" min='<?php echo date("Y-m-d"); ?>'  />
           
			<div class="row">
              <br>
              <div class="col-50">
			   <br>
                <label for="cvv"><b>CVV</b><sup style="color:red;">*</sup></label>
				
                <input type="text" id="cvv" maxlength="3" name="cvv"  pattern="[0-9]{3}" title="atleast 3 number"placeholder="352" autocomplete="off" required>
              </div>
            </div>			
			
			



<input type="submit" name="ordernow" class="btn" style="width: 100%;" value="Continue" onclick="return cardnumber(document.user_form.card)">




</fieldset>
</div>
</div>
</form>
</body>
</html>


          </div>
        </div>
      </div>
    
    <!--
   
    
    
    <!--
    *** COPYRIGHT ***
    _________________________________________________________
    -->
    <div id="copyright" style="margin-bottom:-100px">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mb-2 mb-lg-0">
            <p class="text-center text-lg-left">©2022 Online Grocery Store.</p>
          </div>
          <div class="col-lg-6">
            
			
			<p class="text-center text-lg-right">design by <a href="https://www.mmu.edu.my">Multimedia University</a>
              <!-- If you want to remove this backlink, pls purchase an Attribution-free License @ https://bootstrapious.com/p/obaju-e-commerce-template. Big thanks!-->
            </p>
			
          </div>
        </div>
      </div>
    </div>
	</div>
    <!-- *** COPYRIGHT END ***-->
    <!-- JavaScript files-->
    <script src="assets/js/shikeongcart-jquery.min.js"></script>
    <script src="assets/js/shikeongcart-bootstrap.bundle.min.js"></script>
    <script src="assets/js/shikeongcart-jquery.cookie.js"> </script>
    <script src="assets/js/shikeongcart-owl.carousel.min.js"></script>
    <script src="assets/js/shikeongcart-owl.carousel2.thumbs.js"></script>
    <script src="assets/js/shikeongcart-front.js"></script>
  </body>
</html>
<?php

?>