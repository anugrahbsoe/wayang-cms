<?php
$query="SELECT `category_id`, `category_title` FROM `wy_category` WHERE `category_visible`='True'";
$row=$conn->query($query);
$data=$row->num_rows;
if(!$row=$conn->query($query)){
	echo "Could not retrieve data!";
}
else{
	echo "
	<div id='site_sidebar'>
		<div class='side_head'>
			<p>Category</p>
		</div>
		<div class='side_content'>";
		if($data==0){
			echo "No data found in database!";
		}else{
			while($data=$row->fetch_array()){
				echo "<a href='?category_id=".$data['category_id']."'>".$data['category_title']."</a><br>";
			}
		}
	echo"</div>
	</div>";
}
?>