

<div class="card">
<div class="thumbnail">
<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(460, 306)); ?></a>
</div>
<div class="info">
<?php if (!empty(get_post_meta(get_the_ID(), '_imdb_field', true))) { ?>
   <i class="fab fa-imdb"></i><br>
<?php } ?>
<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h2>
<h4><?php echo get_post_meta(get_the_ID(), 'realease_year', true); ?></h4>
<h4 class="star"><i class="fas fa-star"></i><?php echo get_post_meta(get_the_ID(), 'rmp_avg_rating', true); ?></h4>
<?php $categories = get_the_category();

foreach( $categories as $category ) {
   echo  $category->name . ' | ';
}

?>



</div>


</div>