
        <aside id="leftCol">
            <h4>Departamentos</h4>
            <hr />
            <nav>
                <ul class="navUl">
                    <!-- <li><a href="#">Marketing</a></li>
                    <li><a href="#">TI</a></li>
                    <li><a href="#">Administração</a></li>
                    <li><a href="#">Recursos Humanos</a></li> -->
                    <?php 
                        $catArgs = array(
                            'type'          =>  'post',
                            'orderby'       =>  'name',
                            'order'         =>  'ASC',
                            'hide_empty'    =>  '0',
                            'taxonomy'      =>  'category'
                            );
                        $navCat = get_categories( $catArgs ); 
                        foreach ($navCat as $cat) {
                            echo "<li><a href=\"".get_category_link( $cat->term_id )."\">$cat->cat_name</a></li>";
                        }
                    ?> 
                </ul>
            </nav>
        </aside>