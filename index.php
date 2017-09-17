<?php
if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	// Database connect
  try{
    $pdo = new PDO('mysql:host=localhost;dbname=multiple_rows','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
  }catch(PDOException $e){
    echo 'Connexion impossible';
  }
	
	// Add items to database
	if( isset($_POST['items']) && !empty($_POST['items']) ) {

		foreach ($_POST['items'] as $key => $item) {
			$query = $pdo->prepare("INSERT INTO items (name, price, quantity) VALUES (:name, :price, :quantity)");
	    $query->execute(array(
	      "name" => $item['name'], 
	      "price" => $item['price'],
	      "quantity" => $item['quantity'],
	    ));
		} // End insert loop

	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>PHP &amp; jQuery Multiple Items</title>
</head>
<body>

	<form method="post" action="">
		<button type="button" id="addLine">Add new Item</button><hr>
		<table class="table" id="itemsTable">
			<thead>
				<tr>
					<th>Item name</th>
					<th>Price</th>
					<th>Quantity</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<input type="text" name="items[0][name]" value="" required>
					</td>
					<td>
						<input type="number" step="0.1" min="0" name="items[0][price]" value="0" required>
					</td>
					<td>
						<input type="number" min="1" name="items[0][quantity]" value="1" required>
					</td>
					<td>
						<button type="button" class="deleteLine" disabled>Delete</button>
					</td>
				</tr>
			</tbody>
		</table>
		<hr>
		<button type="submit">Submit</button>
	</form>


	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script>
		jQuery(document).ready(function($){
			// Add new line
	   	$("#addLine").click(function(){
	   		var itemsLength = $("#itemsTable tbody tr").length
	   		var copy = $("#itemsTable tbody tr:first").clone()
	   		copy.find('.deleteLine').removeAttr('disabled')
	   		copy.find('input').each(function(){
	   			$(this).val('')
	   			var old_ame = $(this).attr('name')
	   			$(this).attr('name', old_ame.replace('[0]', '['+itemsLength+']'))
	   		})
	   		$('#itemsTable').append(copy)
	   	})

	   	// Delete line
	   	$("#itemsTable").on('click', '.deleteLine', function(){
	   		$(this).closest('tr').remove();
	   	});
		})
	</script>
</body>
</html>