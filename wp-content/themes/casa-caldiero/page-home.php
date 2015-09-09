<?php
/*
Template Name: Home Page Template
*/
?>

<?php get_header(); ?>


<div class="container">
  <div class="row text-center">
    
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
<?php

global $post;
//get image attachment posts
$args = array(
  'post_type' => 'attachment',
  'post_parent' => $post->ID,
  'numberposts' => -1,
  'post_status' => NULL
);
$attachs = get_posts($args);
//if not empty...
if (!empty($attachs)) {
  $i=0;
?>

  <!-- Indicators -->
  <ol class="carousel-indicators">

<?php
  //loop through the attachs array
  foreach ($attachs as $att) {
?>
    <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i; ?>"<?php if ($i==0) : ?> class="active"<?php endif; ?>></li>
<?php
  }
?>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">

<?php
  //loop through the attachs array
  foreach ($attachs as $att) {
    // get attachment array with the ID from the returned posts
    $img_data = wp_get_attachment_image_src($att->ID, 'rig');
    //store the first value in the $src variable
    $src = $img_data[0];
    //Use the src variable to render all attachment images ?>
    <div class="item<?php if ($i==0) : ?> active<?php endif; ?>">
      <img src="<?php echo $src ?>" alt="<?php echo $att->post_title; ?>">
      <div class="carousel-caption">
        <?php echo $att->post_title; ?>
      </div>
    </div>
<?php
    $i++;                    
  }
}
?>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="fa fa-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="fa fa-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>    
  </div>
</div>

<?php get_footer(); ?>
