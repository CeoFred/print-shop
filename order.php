<?php
  $product_id =   isset($_GET['id']) ? $_GET['id'] : null;

  if(!$product_id){
      header('Location: index.php');
  }
  $db = mysqli_connect("localhost", "root", "", "dezzy") or die(mysqli_error($db));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dezzyworld Multiservices | View Inventory</title>
</head>
<body>


<?php
    include_once "pageHeader.php";
    ?>
    
    
    <div class="wrapper">
         <div class="container">
    <?php


         $sql = "SELECT * FROM product_table WHERE id = $product_id";
         $data = mysqli_query($db, $sql);
         $rows =  mysqli_num_rows($data);

        
             ?>
      
        
            

            <?php
					
					?>
                
            <?php 
            if($rows<=0){
                echo'<div class="text-center"><h2>No results Found!</h2></div>';
            }
            $row = mysqli_fetch_assoc($data);
            	$price = $row['price'];
                        $formatted_price = number_format($price);
                        ?>
            <div class="container-fluid" style="display:-moz-inline-grid;background-image:url(<?='dashboard/'.$row['thumbnail']?>);background-size:cover;background-repeat:no-repeat">

            <div class="row" style="">
                    
                    <div class="col-lg-4 col-sm-12 pt-4 pb-4 pl-4">
                    <div class="card" style="">
  <div class="card-body">
    <h3 class="card-title text-left  text-capitalize text-info"><?=$row['pname']?></h3>
    <div class="text-left">  
    <b class="card-text">Select Quantity</b>
<br>
  <select class="custom-select" id="quantity">
    <option selected value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
    <option value="3above">More than three</option>
  </select>
  
    </div>
    <br>
  <div class="bg-light" style="width:100%">
<p class="text text-dark text-left">
<b class="text-capitalize">from</b>
<br>
<p class="text-left">₦ <b id="price"><?=$row['price']?></b></p>
</p>
<button class="btn btn-md btn-secondary"  type="button" data-toggle="modal" data-target="#optionmodal">
    PROCEED
</button>
</div>
  </div>
</div>
                    </div>
            </div>

             </div>
            
        

    
            </div>
    </div>
    <div class="container mt-4">
      
			<div class="row">
                <div class="col-lg-5 col-sm-12 bounceInRight">
                <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
        <img src="/dashboard/<?=$row['thumbnail']?>" class="d-block w-100" alt="...">
    </div>
  </div>
</div>
                </div>
                <div class="col-lg-7 col-sm-12 ">
                <div class="text-left">
                    <b class="text-info font-weight-bold text-capitalize"><?=$row['pname']?></b>
                    <hr>
                    <p class="text-mute">
                      <?=$row['details']?>  
                    </p>
                    
<button class="btn btn-md btn-success" type="button" data-toggle="modal" data-target="#optionmodal">
    ORDER NOW
</button>
                </div>
                </div>
            </div>
</div>
    <!-- FOOTER AREA -->
<!-- Modal -->
<div class="modal fade" id="optionmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Order Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <p>To proceed ,please input your email and we would communitcate with you as soon as possible.</p>
       <form>
           <div id="errorContainer" class="bg-warning p-2 text-white text-capitalize d-none">

           </div>
  <div class="form-group">
    <label for="buyerEmail">Email address</label>
    <input type="email" class="form-control" id="buyerEmail" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="buyerFullname">Full name</label>
    <br>
    <input type="text" class="form-control" id="buyerFullname" placeholder="John Doe">
  </div>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" id="proceed" class="btn btn-primary">Proceed</button>
      </div>
    </div>
  </div>
</div>
    <div>
        <?php
        include_once "pageFooter.php";

        ?>

    </div>
            </div>
<script>
const proceedBtn = document.querySelector('#proceed');
const email = document.querySelector('#buyerEmail')
const fullname = document.querySelector('#buyerFullname')
const productQuantity = document.querySelector('#quantity')
let Price =  document.querySelector('#price')
let totalPrice = "<?=$row['price']?>"
const productPrice = "<?=$row['price']?>";
let errorContainer =  document.querySelector('#errorContainer')

productQuantity.addEventListener('change',function(e){
    const quantity =  Number(e.target.value)
    if(e.target.value === '3above' ){
    Price.innerText = 'Negotiatable'
        return;
    }else{
    const mul = quantity * Number(productPrice);
    Price.innerText  = mul
    totalPrice = mul
    }
    
})

proceedBtn.addEventListener('click',function(e){
    e.preventDefault();
    errorContainer.classList.add('d-none');
const data =  {
    email:email.value,
    fullname:fullname.value,
    product:"<?=$row['pname']?>",
    Quantity:productQuantity.value,
    totalPrice,
    productId:"<?=$row['id']?>"
}

for (const key in data) {
    if (data.hasOwnProperty(key)) {
        const element = data[key];
        if(element === ''){
    errorContainer.classList.remove('d-none');

            errorContainer.innerHTML = `${key} cannot be empty`
            return false;
        }
    }
}
$.ajax({
  method: "POST",
  url: "/api/requests/order.php",
  data,
  beforeSend: function(xhrObj){
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader("Accept","application/json");
}, dataType: "json"  ,
contentType:'application/json',
})
  .done(function( msg ) {
    alert( "Data Saved: " + msg );
  }).fail(function( err ){
      console.error(err)
  })
// postData('/api/requests/order.php', data)
//   .then(data => console.log(JSON.parse(data))) // JSON-string from `response.json()` call
//   .catch(error => console.error(error));

// function postData(url = '', data) {
//   // Default options are marked with *
//     return fetch(url, {
//         method: 'POST', // *GET, POST, PUT, DELETE, etc.
//         mode: 'cors', // no-cors, cors, *same-origin
//         cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
//         credentials: 'same-origin', // include, *same-origin, omit
//         headers: {
//             'Content-Type': 'application/json',
//             // 'Content-Type': 'application/x-www-form-urlencoded',
//         },
//         redirect: 'follow', // manual, *follow, error
//         referrer: 'no-referrer', // no-referrer, *client
//         body: JSON.stringify(data), // body data type must match "Content-Type" header
//     })
//     .then(response => response.json()); // parses JSON response into native JavaScript objects 
// }

})

</script>
</body>
</html>