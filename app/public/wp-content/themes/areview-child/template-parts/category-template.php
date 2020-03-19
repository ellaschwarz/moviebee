<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<div class="card-deck">
  <div class="card">
    <!-- <img src="..." class="card-img-top" alt="..."> -->
    <a href="<?php the_permalink(); ?>" class="card-img-top"><?php the_post_thumbnail(array(512, 340)); ?></a>
    <div class="card-body">
      <h5 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
      <div class="imdb_icon">
<?php if (!empty(get_post_meta(get_the_ID(), '_imdb_field', true))) { ?>
    <i class="fab fa-imdb"></i>
 <?php } ?>
</div>
 <h3> <?php echo get_post_meta(get_the_ID(), 'release_year', true) ?> </h3>

 <?php echo get_post_meta(get_the_ID(), 'realease_year', true); ?>
 <div class="rating_result"> <?php echo do_shortcode('[ratemypost-result]'); ?> </div>  
      <p class="card-text"></p>
    </div>
  </div>
</div>