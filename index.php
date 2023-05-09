<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
<?php include 'includes/navbar.php'; ?>
<div class="wrapper"> 
	  <div class="content-wrapper">
	    <div>

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-12">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='alert alert-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}
	        		?>
	        		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		                <ol class="carousel-indicators">
		                  <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
		                  <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
		                  <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
						  <li data-target="#carousel-example-generic" data-slide-to="3" class=""></li>
		                </ol>
		                <div class="carousel-inner">
		                  <div class="item active">
		                    <img src="images/bgimg-3.jpg" alt="First slide">
		                  </div>
		                  <div class="item">
		                    <img src="images/bgimg-3.jpg" alt="Second slide">
		                  </div>
		                  <div class="item">
		                    <img src="images/bgimg-3.jpg" alt="Third slide">
		                  </div>
						   <div class="item">
		                    <img src="images/bgimg-3.jpg" alt="Fourth slide">
		                  </div>
		                </div>
		                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
		                  <span class="fa fa-angle-left"></span>
		                </a>
		                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
		                  <span class="fa fa-angle-right"></span>
		                </a>
		            </div>
	        	</div>
	        </div>
	      </section>

		  
 <!-- fashion section start -->
 <div class="container">
         <div id="main_slider" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
               <div class="carousel-item active">
                  <div class="container">
                     <h1 class="fashion_taital">Our Products</h1>
                     <div class="fashion_section_2">
                        <div class="row">
                          <?php
 
								// Username is root
								$user = 'root';
								$password = '';
								
								// Database name is geeksforgeeks
								$database = 'buyonthefly';
								
								// Server is localhost with
								$servername='localhost';
								$mysqli = new mysqli($servername, $user,
												$password, $database);
								
								// Checking for connections
								if ($mysqli->connect_error) {
									die('Connect Error (' .
									$mysqli->connect_errno . ') '.
									$mysqli->connect_error);
								}
								
								// SQL query to select data from database
								$sql = " SELECT * FROM products ORDER BY 	id DESC ";
								$result= $mysqli->query($sql);
								$mysqli->close();
                                 foreach($result as $ap)
                                    { 
                                       $name = $ap['name'];
                                       $description = $ap['description'];
                                       $price = $ap['price'];
                                       $slug = $ap['slug'];
                                       $Photo = (!empty($ap['photo'])) ? 'images/'.$ap['photo'] : 'images/noimage.jpg';
                            ?>
                           <div class="col-lg-4 col-sm-4">     
                               <div class="box_main">
                                       <h4 class="shirt_text"><?php echo $name; ?></h4>
                                       <p class="price_text">Price: <span style="color: #000000;font-size:16px;font-weight:800;">$ <?php echo number_format($price,2); ?></span></p>
                                       <div class="tshirt_img"><img src="<?php echo (!empty($ap['photo'])) ? 'images/'.$ap['photo'] : 'images/noimage.jpg'; ?>"width="100%" height="300px"></div>
                                       <div class="btn_main">
                                          <div class="buy_bt"><a href="product.php?product=<?php echo $slug; ?>"> Buy Now </a></div>
                                          <div class="seemore_bt"><a href="product.php?product=<?php echo $slug; ?>">See More</a></div>

                                     </div>
                               </div>     
                           </div>
                           
                           <?php
                                   } ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- fashion section end -->
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>