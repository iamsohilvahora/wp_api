<?php 
if(have_rows('solutions_post_flexible_content', $post_id)):
      while(have_rows('solutions_post_flexible_content', $post_id)): the_row();
          if( get_row_layout() == 'creditentials_logo' && $_POST['ajaxIndex'] == get_row_index()):
	        $solutions_post_creditentials_logo_title = get_sub_field('solutions_post_creditentials_logo_title');
            $solutions_post_creditentials_logo_button = get_sub_field('solutions_post_creditentials_logo_button');
            $solutions_post_credentials_logo = get_sub_field('solutions_post_credentials_logo');
?>
<div class="container">
    <div class="row">
        <div class="col our-creditential-left">
            <?php if(!empty($solutions_post_creditentials_logo_title)){ ?>
            <h2><?php echo $solutions_post_creditentials_logo_title; ?></h2>
            <?php } 
            if(!empty($solutions_post_creditentials_logo_button)){
                echo button_group($solutions_post_creditentials_logo_button,'btn btn-primary');   
            }
            ?>
        </div>
        <?php if(!empty($solutions_post_credentials_logo)){ ?>
        <div class="col our-creditential-right">
            <div class="logo-carsoul our-creditential-block">
                <?php foreach($solutions_post_credentials_logo as $logo){
                        $post_logo = get_field('post_logo',$logo->ID);
                        if(!empty($post_logo)){
                     ?>
                    <div class="item">
                        <div class="creditentials-item-inner">
                            <img src="<?php echo $post_logo['url']?>">
                        </div>
                    </div>
                <?php }} ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php
  endif;
      endwhile;
endif;
?>