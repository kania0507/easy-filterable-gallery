<?php
/*
Plugin Name: Easy Filterable Gallery
Plugin URI: https://seowp.pl/plugins/easy-filterable-gallery
Description: Plugin allow to create a cool gallery filtered by category.  It's free, easy and fully responsive.
Author: AKC 
Email: anna@seowp.pl
Author URI: https://seowp.pl
Tags:  filterable gallery, post type gallery, free, portfolio, responsive 
Version: 1.0
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EFGallery_class {
	public function __construct(){

		add_action('wp_enqueue_scripts', array($this,'EFGallery_scripts'));
	    add_action('init', array($this, 'EFGallery_register'));
	    add_shortcode('efgallery', array($this, 'EFGallery_show'));
	}

	public function EFGallery_scripts(){
		wp_enqueue_style('gallery',PLUGINS_URL('css/style.css',__FILE__));
		wp_enqueue_script('gallery',PLUGINS_URL('js/gscript.js',__FILE__), array('jquery'), '1.0.0', true);
	}

	public function EFGallery_register(){

		register_post_type('gallery',array(
			'labels'=>array(
				'name'=>'Gallery',
				'add_new_item'=>'Add New Gallery',
				'add_new'=>'Add New'
			),
			'public'=>true,
			'supports'=>array('title', 'editor', 'thumbnail'),
			'menu_icon'=>'dashicons-format-gallery',
            'has_archive'   => true
          
		));

		
	register_taxonomy('gallery_taxonomy','gallery',array(
			'labels'=>array(
				'name'=>'Gallery Category'
			),
			'public'=>true,
			'hierarchical'=>true
		));

	}

	public function EFGallery_show(){
		ob_start();
		?>
        <section class="gallery">
            <div class="btns">
                <button class="btn filter-cat" data-filter="all" data-rel="all">Show all</button>

                    <?php 
                        $gallery_types= get_terms('gallery_taxonomy'); 
                        foreach($gallery_types as $gallery) : 
                            if (strlen($gallery->slug)>0):
                                ?>
                               <button class="btn filter-cat" data-rel="<?php echo  $gallery->slug;  ?>"><?php echo $gallery->name;  ?></button>
                        <?php endif; endforeach; ?>					  
            </div>

            <div id="gallery_container" class="gallery_container">
                <?php $all_cpt= new wp_Query(array(
                    'post_type'=>'gallery',                                
                    'posts_per_page'=>-1
                ));

                while( $all_cpt->have_posts() ) : $all_cpt->the_post();
                $allterms = get_the_terms(get_the_id(),'gallery_taxonomy');
                $terms   = array();

                if ($allterms>0)
                     foreach($allterms as $term) :
                         $terms[] = $term->slug;
                     endforeach; 
                ?>

                <div class="tile scaleit <?php echo implode(' ',$terms); ?> all">
                    <a class="tile_link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?>
                         <div class="details">
                             <span class="title"><?php the_title(); ?></span>                               
                        </div>                    
                        <span><?php the_title(); ?></span>
                    </a>                         
                </div>

            <?php endwhile;  ?>
            </div>
        </section> 
	
		<?php return ob_get_clean();
	}
}
new EFGallery_class();





