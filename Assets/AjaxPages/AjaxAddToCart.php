<?php
include("../Connection/Connection.php");
session_start();
$selqry="select * from tbl_booking where user_id='".$_SESSION["uid"]."' and booking_status='0'";

$result=$conn->query($selqry);
if($result->num_rows>0)
{
	
	if($row=$result->fetch_assoc())
	{
		$bid = $row["booking_id"];
		
		
		
		$selqry="select * from tbl_cart where booking_id='".$bid."' and product_id='".$_GET["product_id"]."'";
		//echo $selqry;
		$result=$conn->query($selqry);
		if($result->num_rows>0)
		{
			 echo "Already Added to Cart";
			
		}
		else
		{
		
		 $insQry1="insert into tbl_cart(product_id,booking_id)values('".$_GET["product_id"]."','".$bid."')";
         if($conn->query($insQry1))
          { 
             echo "Added to Cart";
                        }
                        else
                        {
	                       echo"Failed";
                        }
		}
		
	}
	
}
else
{
	

$insQry=" insert into tbl_booking(user_id,booking_date)values('".$_SESSION["uid"]."',curdate())";
			if($conn->query($insQry))
			{
				$selqry1="select MAX(booking_id) as id from tbl_booking";
                $result=$conn->query($selqry1);
				if($row=$result->fetch_assoc())
				{
					$bid=$row["id"];
					
					
					$selqry="select * from tbl_cart where booking_id='".$bid."'and product_id='".$_GET["product_id"]."'";
		$result=$conn->query($selqry);
		if($result->num_rows>0)
		{
			 echo "Already Added to Cart";
			
		}
		else
		{
					
					
	                   $insQry1="insert into tbl_cart(product_id,booking_id)values('".$_GET["product_id"]."','".$bid."')";
                        if($conn->query($insQry1))
                        { 
                          echo "Added to Cart";
                        }
                        else
                        {
	                       echo"Failed";
                        }
					  
		}

                }
			}
}
?>