<div id="keywords" style="margin-top:20px;">
  <?php if ( $tags ) : foreach ( $tags as $tag ) : ?>
  <span class="" ><?php echo $tag->tag; ?></span>&nbsp;
  <?php endforeach; endif; ?>
</div>
