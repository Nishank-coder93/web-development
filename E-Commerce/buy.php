<?php
		session_start(); // Starting the session to maintain the record

		//checks if the Basket price is set or not
		if(!isset($_SESSION["basket_price"])) {
			$_SESSION["basket_price"] = 0;
		}

		// Checks if the request method is GET 
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			// checks if the Requst method is Delete 
			if(!empty($_GET['delete'])){
				$product_id = $_GET['delete'];
				// If Delete request loops through the Shopping Bag array to check for product id and removes it
				// Subtracts the products price from the main price
				for ($j=0; $j < count($_SESSION['bag']); $j++) { 
					if ($product_id == key($_SESSION['bag'][$j])) {
						$delete_refer = $_SESSION["bag"][$j];
						$key = key($delete_refer);
			 			$delete_item = $delete_refer["$key"];
			 			$_SESSION["basket_price"] -= $delete_item["minPrice"];
						array_splice($_SESSION["bag"],$j,$j+1);
					}
				}
				// If the item removed is the last item, then make the Basket price to 0
				if(count($_SESSION["bag"]) == 0){
					$_SESSION["basket_price"] = 0;
				}

			}
			// If clear GET is requested 
			elseif (!empty($_GET['clear'])){
				# If clearing the Basket is executed		
				$_SESSION["basket_price"] = 0;
				$_SESSION["bag"] = array();
			} 
			// If the buy GET request is executed 
			elseif (!empty($_GET['buy'])) {
				// Sets the variable 
				$itemArray = array();
				$_prodid = $_GET['buy'];
				$_itemList = $_SESSION['items'];

				// If a Shopping bag variable is not set in the session then intialize it
				if(!isset($_SESSION['bag'])){
					$_SESSION["bag"] = array();
					$_bagArray["$_prodid"] = $_itemList["$_prodid"];
					$itemArray[0] = $_bagArray;
					$item_ref = $_bagArray["$_prodid"];
					$_SESSION["basket_price"] += $item_ref["minPrice"];
				}
				else {
					// First it checks if the product already exists in the bag to avoid duplicates 
					$exist = false;
					for ($j=0; $j < count($_SESSION['bag']); $j++) { 
						if ($_prodid == key($_SESSION['bag'][$j])) {
							$exist = true;
						}
					}

					// It adds the product in the Shopping bag along with previous products already exsiting
					for ($i = 0; $i < count($_SESSION['bag']); $i++){
							$itemArray[$i] = $_SESSION['bag'][$i];
						}

						// If the product doesnt exist then add to shopping bag and add to the total price
					if( $exist == false){
						$_bagArray["$_prodid"] = $_itemList["$_prodid"];
						$item_ref = $_bagArray["$_prodid"];
						$_SESSION["basket_price"] += $item_ref["minPrice"];
						$itemArray[count($itemArray)] = $_bagArray;
					}

				}

				$_SESSION["bag"] = $itemArray;
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
	<title> Buy </title>
	<meta charset="utf-8"/>
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	 <h1> Shopping Basket </h1> 
	 	<spa> Number of items : <span> <?php echo count($_SESSION["bag"]); ?> </span> </span>
	<p>
	
	</p>

	<table style="width: 100%; border: 1px solid blue; margin-left: auto; margin-right: auto;" border="1" cellpadding="5">
		<tbody>
			<tr class='.table_row'>
				<th> Image </th>
				<th> Name </th>
				<th> Description </th>
				<th> Price </th>
				<th> Delete </th>
			</tr>
			<?php
			// If the shopping bag variable is set then show it 
			 if (isset($_SESSION['bag'])) {
			 		for ($i = 0; $i < count($_SESSION["bag"]); $i++) {
			 			$bag_refer = $_SESSION["bag"][$i];
			 			$key = key($bag_refer);
			 			$bag_item = $bag_refer["$key"];
			 			$delete_url = "buy.php?delete=" . $key;
			 			$site_url = $bag_item['productURL'];
			 			echo "<tr class='table_row'> <td> <a href=$site_url><img src=" . $bag_item["imageURL"] . "></a><td> <td>" . $bag_item["itemName"] . "</td> <td>" . $bag_item["minPrice"] . "</td> <td> <a href=$delete_url class='prod_button'> DELETE </a> </td> </tr>";
			 		}
				} 
		 	?>
		</tbody>
	</table>

	<p>
	<h2>Total <span> <?php echo $_SESSION["basket_price"] . "$"; ?> </span></h2>
	<p>  </p>
	<a href="buy.php?clear=1" class='prod_button'> Empty Basket </a>
	<p></p>
	<form action="buy.php" method="GET" id="search_product">
		<fieldset><legend>Find products:</legend>
			<label>Category: 
				<select name="category" id="category_value">
				</select>
			</label>
			<label>Search keywords: <input type="text" name="search">
				<label>
					<input type="submit" value="Search">
				</label>
			</label>
		</fieldset>
	</form>
	<table style="width: 100%; border-color: blue; margin-left: auto; margin-right: auto;" border="solid" cellpadding="5">
		<tbody>
			<tr>
				<th>Image</th>
				<th>Item Name</th>
				<th>Min Price</th>
				<th>Full Description</th>
				<th>Buy Product</th>
			</tr>
<?php 
// Request method is GET check to seee if there is request for search in certain category
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if (!empty($_GET['search']) && !empty($_GET['category'])) {
			# to retreive the information about the products
			$_category = $_GET['category'];
			$_search = $_GET['search'];
			$_search = str_replace(' ', '_', $_search);

			#retrieves the info from the url
			$_URL = "http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&categoryId=$_category&keyword=$_search&numItems=20";

			// gets the XML file
			$xmlstr = file_get_contents($_URL);
			$product = new SimpleXMLElement($xmlstr);

			// Puts the item into an array
			$items_array = $product->categories->category->items;
			$product_array = array();

			/// Outputs the item in the array as a table of items
			if(!empty($items_array)){
				// Loops through the products and outputs it in a table format
				for( $i = 0; $i < count($items_array->product); $i++) {
					$PRODUCT_ATTR = $items_array->product[$i];
					$PRODUCT_ID = $PRODUCT_ATTR['id'];
					$ITEM_NAME = $PRODUCT_ATTR->name;
					$MIN_PRICE = $PRODUCT_ATTR->minPrice;
					$PRODUCT_OFFER_URL = $PRODUCT_ATTR->productOffersURL;
					$IMAGE_URL = $PRODUCT_ATTR->images->image[0]->sourceURL;
					
					if ($PRODUCT_ATTR->fullDescription) {
						$FULL_DESCRIPTION = $PRODUCT_ATTR->fullDescription;
					}
					else {
						$FULL_DESCRIPTION = "";
					}

					// On clicking the Buy button
					$buy_url = "buy.php?buy=" . $PRODUCT_ID;
					$product_array["$PRODUCT_ID"] = ['itemName' => "$ITEM_NAME", 'minPrice' => "$MIN_PRICE", 'productURL' => "$PRODUCT_OFFER_URL", 'imageURL' => "$IMAGE_URL", 'fullDescription' => "$FULL_DESCRIPTION"]; 

					echo "<tr class='table_row'> <td><img src=$IMAGE_URL></td> <td>$ITEM_NAME</td> <td>$MIN_PRICE</td> <td>$FULL_DESCRIPTION</td> <td> <a href=$buy_url class='prod_button' > Buy </a></td> </tr>";
				}

			}
			$_SESSION['items'] = $product_array;
		}
	}

?>
		</tbody>
	</table>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
 // On document loading get the categories 
	$(document).ready(function (){
		
		$.ajax({
                url: 'http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/json/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&showAllDescendants=true',
                success: function (response) {
                	var choices = "<option value='" + response.category.id + "'>" + response.category.name + "</option>";
                    json = response.category.categories.category;
                    for (var i = 0; i < json.length; i++) {
                    	choices = choices + "<option value='" + json[i].id + "'>" + json[i].name + "</option>";
                    	choices = choices + "<optgroup label='" + json[i].name + "'>";
                    	if (json[i].categories){
                    		for (var j = 0; j < json[i].categories.category.length; j++) {
                    			choices = choices + "<option value='" + json[i].categories.category[j].id + "'>" + json[i].categories.category[j].name + "</option>";
                    		};
                    	}
                    	choices = choices + "</optgroup>"
                    	$("#category_value").append(choices);
                    };
                },
                error: function () {
                	alert("Unable to process the URL \n1) Try switching off your ad block \n2) check if you have connection");
                },
            });
		
	});

</script>
</body>
</html>