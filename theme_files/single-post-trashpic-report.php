<?php 
$query = new WP_Query( 'post_type=trashpic-report' );
?>

<?php get_header(); ?>

<?php if (get_option('ebusiness_categories') == 'on') : ?>
    <div id="categories"> 
        <?php $menuClass = 'nav superfish';
        $menuID = 'nav2';
        $secondaryNav = '';
        if (function_exists('wp_nav_menu')) {
            $secondaryNav = wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => $menuID, 'echo' => false ) );
        };
        if ($secondaryNav == '') { ?>
            <ul id="<?php echo esc_attr( $menuID ); ?>" class="<?php echo esc_attr( $menuClass ); ?>">
                <?php show_categories_menu($menuClass,false); ?>
            </ul> <!-- end ul#nav -->
        <?php }
        else echo($secondaryNav); ?>
    </div>
<?php endif; ?>

<div id="container">
    <div id="left-div-full">
    <?php if (get_option('ebusiness_integration_single_top') <> '' && get_option('ebusiness_integrate_singletop_enable') == 'on') echo(get_option('ebusiness_integration_single_top')); ?>
        <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        <!--Begin Post-->
        <div class="home-post-wrap-2">
            <h1 class="titles"><a href="<?php the_permalink() ?>" title="<?php printf(esc_attr__('Permanent Link to %s','eBusiness'), get_the_title()) ?>">
                <?php the_title() ?>
                </a></h1>
            <?php if (get_option('ebusiness_postinfo1') ) : ?>
                <div class="post-info-wrap"> <span class="post-info"><?php esc_html_e('Posted','eBusiness') ?> <?php if (in_array('author', get_option('ebusiness_postinfo1'))) { ?> <?php esc_html_e('by','eBusiness') ?> <?php the_author_posts_link(); ?><?php }; ?><?php if (in_array('date', get_option('ebusiness_postinfo1'))) { ?> <?php esc_html_e('on','eBusiness') ?> <?php the_time(get_option('ebusiness_date_format')) ?><?php }; ?><?php if (in_array('categories', get_option('ebusiness_postinfo1'))) { ?> <?php esc_html_e('in','eBusiness') ?> <?php the_category(', ') ?><?php }; ?><?php if (in_array('comments', get_option('ebusiness_postinfo1'))) { ?> | <?php comments_popup_link(esc_html__('0 comments','eBusiness'), esc_html__('1 comment','eBusiness'), '% '.esc_html__('comments','eBusiness')); ?><?php }; ?></span> </div>
            <?php endif; ?>
            <div style="clear: both;"></div>

            <?php if (get_option('ebusiness_thumbnails') == 'on') get_template_part('includes/thumbnail'); ?>

            <?php the_content(); ?>
            <?php if (get_option('ebusiness_integration_single_bottom') <> '' && get_option('ebusiness_integrate_singlebottom_enable') == 'on') echo(get_option('ebusiness_integration_single_bottom')); ?>
            <?php if (get_option('ebusiness_foursixeight') == 'on') { ?>
            <?php get_template_part('includes/468x60'); ?>
            <?php } else { echo ''; } ?>
            <div style="clear: both;"></div>
            <?php if (get_option('ebusiness_show_postcomments') == 'on') { ?>
                <?php comments_template('',true); ?>
            <?php }; ?>
            <?php endwhile; ?>
        </div>
        <?php else : ?>
        <!--If no results are found-->
        <div class="home-post-wrap-2">
            <h1><?php esc_html_e('No Results Found','eBusiness') ?></h1>
            <p><?php esc_html_e('The page you requested could not be found. Try refining your search, or use the navigation above to locate the post.','eBusiness') ?></p>
        </div>
        <!--End if no results are found-->
        <?php endif; ?>
    </div>
    <!--Begin Sidebar-->
    <?php get_sidebar(); ?>
    <!--End Sidebar-->
</div>
<!--Begin Footer-->
<?php get_footer(); ?>
<!--End Footer-->
</body></html>
