
            <article class="excerpt-article">
                <h3><a class="titleLink" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php 
                    $c = get_the_category();
                    $cat = $c[0]->cat_name;
                    $slug = $c[0]->slug;
                ?>
                <div class="excerpt-info">
                    <span class="date"><?php echo get_the_date() ?></span><span class="cat">Categoria: <a href="<?php echo $slug; ?>"><?php echo $cat; ?></a></span>
                </div>
                
                <?php 
                    echo "<div class='excerpt-text'>";
                    echo "<p>".get_the_excerpt()."</p>";
                    echo "</div>"; 
                    if ( has_post_thumbnail() ):
                        echo "<a class='excerpt-thumb' href='".get_permalink()."' title='".get_the_title()."'>".get_the_post_thumbnail( get_the_ID(), 'excerpt-thumb' )."</a>";
                    endif; 
                    echo '<a class="leiaMais" href="'.get_permalink().'" class="read-more">'.'continuar lendo →'.'</a>';
                        ?>
            </article>
            <hr>