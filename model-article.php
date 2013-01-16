
            <article>
                <h3><?php the_title(); ?></h3>
                <hr />
                <span class="date"><?php echo get_the_date() ?></span>
                
                <?php 
                    //custom_excerpt_length(50);
                    the_excerpt(); ?>
            </article>