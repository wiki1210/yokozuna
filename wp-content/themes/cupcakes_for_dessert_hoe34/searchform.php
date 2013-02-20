<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">

<div><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" style="width: 95%;" />

<button class="Button" type="submit" name="search" style="
    float: left;
    padding-bottom: 5px;
    padding-left: 4px;
">
 <span class="btn">
  <span class="t"><?php _e('חפש', 'kubrick'); ?></span>
  <span class="r"><span></span></span>
  <span class="l"></span>
 </span>
</button>
</div>
</form>
