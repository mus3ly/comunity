

<input type="hidden" value="<?php echo $category; ?>" id="blog_cat" />
<!-- PAGE WITH SIDEBAR -->
<section class="page-section with-sidebar">
    <div class="container">
        <div class="row">
            <!-- SIDEBAR -->
            <?php 
                include 'sidebar.php';
            ?>
            <!-- /SIDEBAR -->
            <!-- CONTENT -->
            <div class="col-md-9 content" id="content">
                <!--<div id="blog-content">-->
                <!--</div>-->
                <?php
                	foreach($blogs as $row){
                ?>
                <div class="thumbnail blog_box orange_border_shadow"> 
                	<div class="row">
                		<div class="col-md-4 col-sm-4 col-xs-12 space_reduce">
                
                			<div class="media">
                				<a class="media-link" href="<?php echo $this->crud_model->blog_link($row['blog_id']); ?>">
                					<img src="<?php echo $this->crud_model->file_view('blog',$row['blog_id'],'','','thumb','src','',''); ?>"  alt=""/>
                				</a>
                			</div>
                		</div>
                		<div class="col-md-8 col-sm-8 col-xs-12">
                			<div class="caption">
                				<h4 class="caption-title">
                                    <a href="<?php echo $this->crud_model->blog_link($row['blog_id']); ?>">
                                    	<?php echo $row['title']; ?>
                                    </a>
                                </h4>
                				<div class="overflowed">
                					<!--<div class="availability"><?php echo translate('by'); ?>: <span><?php echo $row['author']; ?></span></div>-->
                					<!--<div class="price"><P><?php echo formate_date($row['date']); ?></P></div>-->
                					<P class="low_me">
							<?php echo translate('by'); ?> 
							<span class="name"><?php echo $row['author']; ?></span> | 
							<span class="date"><?= $newDate = formate_date($row['date']);?></span>
							</p>
                				</div>
                				<div class="caption-text">
                				    <p><?= strWordCut($row['summery'], 320); ?></p>
                				</div>
                			</div>
                		</div>
                	</div>
                </div>
                <?php
                	}
                ?>
       
            </div>
            
            <!-- /CONTENT -->
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                 <ul class="pagination">
  
                  <?php
                  if($cpage > 1)
                  {
                      $pre = $cpage - 1;
                      ?>
                      <li><a href="<?= $link.'?page=1' ?>"><<</a></li>
                      <li><a href="<?= $link.'?page='. $pre ?>"><</a></a></li>
                      <?php
                  }
                  $st = $cpage - 2;
                  if(!$st)
                  {
                      $st = 1;
                  }
                  $en = $cpage + 2;
                  if($en > $tpage)
                  {
                      $en = $tpage;
                  }
                  for($i = 1; $i <=$tpage;$i++)
                  {
                      if($i >= $st && $i <= $en)
                      {
                      
                      ?>
                      <li class="<?= ($i == $cpage)?"active":" "; ?>"><a href="<?= $link.'?page='. $i ?>"><?= $i ?></a></li>
                      <?php
                      }
                  }
                  
                  if($tpage < $cpage)
                  {
                      $nxt = $cpage + 1;
                      ?>
                      <li><a href="<?= $link.'?page='. $nxt ?>">></a></li>
                      <li><a href="<?= $link.'?page='.$tpage ?>">>></a></li>
                      
                      <?php
                  }
                  ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- /PAGE WITH SIDEBAR -->

<script>
// 	function get_blogs_by_cat(category){	
// 		$("#blog-content").load("<?php echo base_url()?>home/blog_by_cat/"+category);
// 	}
// 	$(document).ready(function(){
// 		var category=$('#blog_cat').val();
// 		get_blogs_by_cat(category);
//     });
</script>