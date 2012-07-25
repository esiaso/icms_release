<!--Get default user information
-->
<?php
require_once ("classes/class_user_profile.php");
$user = new UserProfile();
$user->getUserProfile($_SESSION['user_id']);

?>

<div id="main_container">
		<div id="main_content2">
			<h2 class="title5">Make Payments</h2>
 <br />
 <div style="padding-left:10px;">

<form action="index.php?k=<?php echo base64_encode('payments'.get_separator().'pesapal'.get_separator().''.get_separator());?>" method="post" id="myform" >
<fieldset>
    <legend>Confirm Order Information</legend>
   		<div class="optional">
            <label>Amount:</label>
                  <input type="text" name="amount" value="5000" class="inputText"/>   
              <small>(in Kshs)</small>              
        </div> 
		<div class="optional">
            <label>Type:</label>
                  <input type="text"  name="type" value="MERCHANT" readonly="readonly"  class="inputText"/> 
                   <small>(leave as default - MERCHANT)</small>              
        </div> 
        <div class="optional">
            <label>Description:</label>
                  <input type="text" name="description" value="Order Description" class="inputText"/>               
        </div> 
    	<div class="optional">
            <label>Reference:</label>
                  <input type="text"  name="reference" value="001" class="inputText"/>   
                  <small>(the Order ID)</small>            
        </div> 
    	<div class="optional">
            <label>Shopper's First Name:</label>
                  <input type="text" type="text" name="first_name" value="<?php echo $user->firstname;?>" class="inputText"/>               
        </div> 
    	<div class="optional">
            <label>Shopper's Last Name:</label>
                  <input type="text" name="last_name" value="<?php echo $user->lastname;?>" class="inputText"/>               
        </div> 
    	<div class="optional">
            <label>Shopper's Email Address:</label>
                  <input type="text" name="email" value="<?php echo $user->usermail;?>" class="inputText"/>               
        </div> 
    <fieldset>
             	<a href="index.php?k=<?php echo base64_encode('users'.get_separator().'profile'.get_separator().''.get_separator());?>">							<button class="btnCancel">Cancel</button></a> 
                <button type="submit"  class="btnSaveChanges">Make Payment</button>
       </fieldset>
    
    </fieldset>
</form>
</div>
</div>
</div>