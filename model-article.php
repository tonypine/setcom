
            <article>
                <h3><a class="titleLink" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                <hr class="z-in2" />
                <span class="date"><?php echo get_the_date() ?></span>
                
                <?php 
                    //custom_excerpt_length(50);
                    the_excerpt(); ?>
            </article>