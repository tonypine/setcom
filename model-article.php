
            <article>
                <h3><a class="titleLink" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                <hr class="z-in2" />
                <?php 
                	$c = get_the_category();
                	$cat = $c[0]->cat_name;
                	$slug = $c[0]->slug;
                ?>
                <span class="date"><?php echo get_the_date() ?></span><span class="cat"><a href="<?php echo $slug; ?>"><?php echo $cat; ?></a></span>
                
                <?php 
                    //custom_excerpt_length(50);
                    the_excerpt(); ?>
            </article>