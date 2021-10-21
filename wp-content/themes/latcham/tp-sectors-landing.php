<?php

/**
Template Name: Sectors Page
**/

get_header();
$sectors_page_top_title = get_field('sectors_page_top_title');
$sectors_page_top_description = get_field('sectors_page_top_description');
$sectors_page_top_enquire_button = get_field('sectors_page_top_enquire_button');
$sectors_page_top_video = get_field('sectors_page_top_video');
?> 
<section class="content-page-top section-has-dots">
    <div class="content-page-top-inner">
        <div class="container">
            <div class="row">
                <div class="col col-sm-6 slide-content">
                    <?php if(!empty($sectors_page_top_title)){ ?>
                    <h1 class="title"><?php echo $sectors_page_top_title;?></h2>
                    <?php } if(!empty($sectors_page_top_description)){ ?>
                    <p><?php echo $sectors_page_top_description; ?></p>
                    <?php }
                        if(!empty($sectors_page_top_enquire_button)){
                            echo button_group($sectors_page_top_enquire_button,'btn btn-primary');   
                        }
                     ?>
                </div>
            </div>
        </div>
    </div>
    <?php if(checkImageNull($sectors_page_top_video)){ ?>
        <div class="dot-circle-bg">
        <img class="svg" src="<?php echo get_template_directory_uri()?>/images/icons/dots-image.svg" alt="">
    </div>    
<?php }else{ ?>
<div class="dot-circles"></div>
<?php }?>
</section>
<?php if(checkImageNull($sectors_page_top_video)){ ?>    
<section class="section section-content-video after--bg">
    <div class="container">
        
        <div class="video-content mp4-video"> 
            <?php if($sectors_page_top_video['select_type'] == 'Image'){ ?>
            <div class="image-container">
                 
                <div class="bg-cover" style="background-image: url('<?php echo $sectors_page_top_video['top_slider_image']['sizes']['content_solutions']?>');">
                      
                      <img src="<?php echo get_template_directory_uri()?>/images/placeholder/placeholder-50x28.png">
                    </div>
            </div>

        <?php }else { 
                if($sectors_page_top_video['top_slider_video_select'] == 'internal'){ ?>
                <div class="banner-img bg-cover" style="background:url('<?php echo get_template_directory_uri()?>/images/media/video-poster.png')"></div>
                <div class="banner-video-wrap">
                     <div class='player'>
                        <video class="video" width="100%" controls> 
                          <source src="<?php echo $sectors_page_top_video['top_slider_video']['url']; ?>" type="video/mp4">
                        </video>
                        <a href="javascript:void(0);" class="play-btn"></a>
                    </div>
                </div>                
             <?php }else{ 
                    $video_url = video_url($sectors_page_top_video['video_url']);
                ?>
                <div class="banner-img bg-cover" style="background:url('<?php echo get_template_directory_uri()?>/images/media/video-poster.png')"></div>  
                <div class="banner-video-wrap">
                    <div class="banner-video-wrap" id="youtube-wrap">
                        <iframe width="100%" height="315" src="<?php echo $video_url; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
                <a href="javascript:void(0);" class="play-btn play-icon"></a>
        <?php } } ?>
        </div>
    </div>
</section>
<?php
} 
$posts_per_page = 9;
 $args = array(
           'post_type' => 'industry'
           );
        $the_query = new WP_Query( $args );
        $totalpost = $the_query->found_posts;

$the_query = new WP_Query( array('posts_per_page'=> $posts_per_page,'post_type' => 'industry', 'post_status' => 'publish' ,  'order' => 'DESC') );
if( $the_query->have_posts() ){
?>
<section class="section section-utilities services-section bg-skyblue">
    <div class="container">
        <h2 class="title title-2">Meeting sector <strong>needs</strong></h2>
        <div class="row" id="appenddata">
            <?php  while( $the_query->have_posts() ){ 
                $the_query->the_post();                               
                    $params['post_id'] = get_the_ID();                
                    bb_get_template_part( 'template-parts/content', 'service-post',$params); 

             } 
              wp_reset_postdata(); ?>
        </div>
        <?php if($totalpost >= $posts_per_page){ ?>
        <div class="loadmore text-center">
            <div id="loader"></div>
            <a href="javascript:;" class="btn btn-primary sectorloadmore">Load more</a>
            <input type="hidden" id="offset" name="offset" value="<?php echo $posts_per_page; ?>">
        </div>
        <?php } ?>
    </div>
</section>
<?php } 
    if( have_rows('page_flexible_content') ){
    while ( have_rows('page_flexible_content') ) { the_row(); 
        if( get_row_layout() == 'top_video_page' ){
            $top_video = get_sub_field('content_page_youtubevimeo_url');
            $ImageNull = checkImageNull($top_video); 
            if($ImageNull){ 
            ?>
<section class="section section-content-video after--bg ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="content" sectionName="video-section"><div class="loader"></div></section>
<?php } } else if( get_row_layout() == 'usps_section_page' ){  ?>
<section class="usps-section ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="usps-section"><div class="loader"></div></section>
<?php } else if( get_row_layout() == 'sub_heading_white_section_page' ){ 
        $display_section = get_sub_field('content_page_display_section');
        $imageclone = get_sub_field('content_page_right_image');
        $ImageNull = checkImageNull($imageclone);
        if($ImageNull){$imgDispaly = ' image--'.$display_section;}else{$imgDispaly = '';}
    ?>
<section class="section section-content section-image-conetnt <?php echo $imgDispaly; ?> ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="subheading-section"><div class="loader"></div></section>
<?php }else if( get_row_layout() == 'sub_heading_second_section_page' ){ ?>
<section class="section section-content column-content bg-skyblue ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="subheading-skyblue-section"><div class="loader"></div></section>

<?php }else if( get_row_layout() == 'sub_heading_third_section_page' ){ 
        $display_section = get_sub_field('content_page_display_section');
        $imageclone = get_sub_field('content_page_left_image');
        $ImageNull = checkImageNull($imageclone);
        if($ImageNull){$imgDispaly = ' image--'.$display_section;}else{$imgDispaly = '';}
    ?>
<section class="section section-content section-image-conetnt <?php echo $imgDispaly; ?> ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="subheading-third-section"><div class="loader"></div></section>
<?php }else if( get_row_layout() == 'solution_section_page' ){ ?>
<section class="section section-solution solution-with-video ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="section-solution"><div class="loader"></div></section>
<?php }else if( get_row_layout() == 'industry_section_page' ){ ?>
<section class="section section-industry bg-skyblue ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="industry-section"><div class="loader"></div></section>
<?php }else if( get_row_layout() == 'case_studies_section_page' ){ ?>
<section class="section section-image-conetnt section-post-box section-case-study ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="casestudies-section"><div class="loader"></div></section>

<?php }else if( get_row_layout() == 'services_section_page' ){ ?>
<section class="section section-utilities hover-dots-effect bg-skyblue ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="services-section"><div class="loader"></div></section>

<?php }else if( get_row_layout() == 'faq_section_page' ){ ?>
<section class="section section-faq ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="faq-section"><div class="loader"></div></section>
<?php }else if( get_row_layout() == 'related_post_section_page' ){ ?>
<section class="section section-post-box ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="related-post-section"><div class="loader"></div></section>
<?php } else if( get_row_layout() == 'logo_post_section_page' ){?>
<section class="section section-client-logo client-logo-less ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="logo-post-section"><div class="loader"></div></section>
<?php } else if( get_row_layout() == 'customer_logos_section_page' ){?>
<section class="section section-client-logo client-logo-less ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="customer-logo-post-section"><div class="loader"></div></section>
<?php }else if( get_row_layout() == 'green_dream_section_page' ){ 
    $display_section = get_sub_field('content_page_display_section');
    $imageclone = get_sub_field('content_page_image');
    $ImageNull = checkImageNull($imageclone);
    if($ImageNull){$imgDispaly = ' image--'.$display_section;}else{$imgDispaly = '';} 
    ?>
<section class="section section-content section-image-conetnt <?php echo $imgDispaly; ?> top-less-space  ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="green-dream"><div class="loader"></div></section>
<?php }else if( get_row_layout() == 'credential_and_logo_section_page' ){?>
<section class="section section-client-logo our-creditential-section bg-skyblue ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="our-creditential-section"><div class="loader"></div></section>
<?php }else if( get_row_layout() == 'team_section_page' ){?>
<section class="section section-image-conetnt section-post-box section-case-study ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="team-section"><div class="loader"></div></section>
<?php }
else if( get_row_layout() == 'testimonial_section_page' ){?>
<section class="section section-testimonials ajaxContent" ajaxIndex="<?php echo get_row_index(); ?>" sectionTemplate="page-content" sectionName="testimonials-section"><div class="loader"></div></section>
<?php }
else if( get_row_layout() == 'contact_form_section_page' ){ 
        $title = get_sub_field('content_page_title');
        $description = get_sub_field('content_page_description');
        $shortcode = get_sub_field('content_page_shortcode');
    ?>
<section class="section section-contact" id="contactForm">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-6 content">
                <div class="content-inner">
                    <?php if(!empty($title)) { ?>
                        <h2 class="title title-2"><?php echo $title; ?></h2>
                    <?php } if(!empty($description)) { ?>
                         <p><?php echo $description; ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="col-sm-12 col-lg-6 contact-form">
                <?php echo do_shortcode($shortcode) ?>
            </div>
        </div>
    </div>
</section>
<?php } } } 
 get_footer(); ?>