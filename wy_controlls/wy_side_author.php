<?php
$query="SELECT distinct(`post_user`) FROM `wy_post`";
$row=$conn->query($query);
$data=$row->num_rows;
if(!$row=$conn->query($query)){
	echo "Could not retrieve data!";
}
else{
	echo "
	<div id='site_sidebar'>
		<div class='side_head'>
			<p>Author</p>
		</div>
		<div class='side_content'>";
		if($data==0){
			echo "No data found in database!";
		}else{
			while($data=$row->fetch_array()){
				echo "<a href='?author=".$data['post_user']."'>".$data['post_user']."</a><br>";
			}
		}
	echo"</div>
	</div>";
}
?>