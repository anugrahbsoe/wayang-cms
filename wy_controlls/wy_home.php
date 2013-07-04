<?php
$query="SELECT `wy_post`.`post_id`, `wy_post`.`post_title`, `wy_post`.`post_category`, `wy_post`.`post_content`, `wy_post`.`post_tag`, `wy_post`.`post_user`, DATE_FORMAT(`wy_post`.`post_date`,'%d %M %Y') as date, `wy_category`.`category_id` FROM `wy_post`, `wy_category`
WHERE  `wy_post`.`post_category`=`wy_category`.`category_title` ORDER BY `post_id` DESC LIMIT 0,10";
$row=$conn->query($query);
$data=$row->num_rows;
if(!$row=$conn->query($query)){
	echo "
	<div class='".$css."_imagetext'>
		<p>Could not retrieve data!</p>
	</div><!--close content_imagetext-->
	";
}
else{
if($data==0){
			echo "
			<div class='".$css."_imagetext'>

				<p>No data found in database!</p>
	
			</div><!--close content_imagetext-->
			";
		}else{
			while($data=$row->fetch_array()){
				$content=explode("</p>",$data['post_content']);
				echo "
				<div class='".$css."_imagetext'>
					<div class='content_title'>
						<a href='?post_id=".$data['post_id']."'>".$data['post_title']."</a><br>
						<h4>Posted by <a href='?author=".$data['post_user']."'>".$data['post_user']." </a>, ".$data['date']."</h4>
						<h4>Category <a href='?catedory_id=".$data['category_id']."'>".$data['post_category']."</a>, Tags : ".$data['post_tag']."</h4>
					</div>
					".$content[0]."
					<div class='button_small'>
						<a href='?post_id=".$data['post_id']."'>Read more</a>
					</div><!--close button_small-->";
					$querycomment="SELECT COUNT(`comment_id`) as comment FROM `wy_comment` WHERE `post_id`='".$data['post_id']."'";
					$rowc=$conn->query($querycomment);
					$datac=$rowc->num_rows;
					if(!$rowc=$conn->query($querycomment)){
						echo "
						<div class='comment'>
							<p>Could not retrieve data!</p>
						</div><!--close comment-->
						";
					}
					else{
						$datac=$rowc->fetch_assoc();
						echo "
						<div class='comment'>
							<p>".$datac['comment']." Comments</p>
						</div><!--close comment-->
						";
					}
				echo "</div><!--close content_imagetext-->";
			}
		}
}
?>