<?php


add_filter( 'vc_load_default_templates','bdt_bs_homepage' ); // Hook in
 
function bdt_bs_homepage($data) {
    $template               = array(); // Create new array
    $template['name']       = __( 'Home Page 1', 'warp' ); // Assign name for your custom template
    $template['weight']     = 0; // Weight of your template in the template list
    $template['custom_class'] = 'bs-homepage'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row][vc_column width="1/1"][vc_column_text css_animation="bottom-to-top"][heading size="20"]Our Most Popular Services[/heading][/vc_column_text][vc_column_text css_animation="bottom-to-top"]<span style="line-height: 1.5em;">[showbiz most-popular-service-home]</span>[/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_column_text css_animation="bottom-to-top"][heading size="20"]Our Most Popular Staff[/heading][/vc_column_text][vc_column_text el_class="title1" css_animation="bottom-to-top"][showbiz most-popular-staff-home][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_empty_space height="32px"][vc_cta_button call_text="We are waiting for you. Please make an Appointment here " title="APPOINTMENT" target="_self" color="btn-success" icon="none" size="btn-large" position="cta_align_right" href="?page_id=828" el_class="theme-style" css_animation="bottom-to-top"][/vc_column][/vc_row]
CONTENT;
  
    array_unshift( $data, $template );
    return $data;
}


add_filter( 'vc_load_default_templates','bdt_bs_homepage2' ); // Hook in
 
function bdt_bs_homepage2($data) {
    $template               = array(); // Create new array
    $template['name']       = __( 'Home Page 2', 'warp' ); // Assign name for your custom template
    $template['weight']     = 0; // Weight of your template in the template list
    $template['custom_class'] = 'bs-homepage'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row][vc_column][vc_empty_space height="30px"][vc_column_text]
<h1 style="text-align: center;"><span style="text-align: center; text-transform: uppercase; font-size: 36px; line-height: 45px;">Check Our Awesome Features</span></h1>
<p style="text-align: center;"><span style="text-align: center;">We are always serve world best service so don't hesitate to call us we will describe details clearly.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span></p>
[/vc_column_text][vc_empty_space height="15px"][/vc_column][/vc_row][vc_row][vc_column width="1/4"][vc_pie value="96" color="btn-primary" units="%"][vc_column_text]
<h3 style="text-align: center;">Therapy Expertise</h3>
[/vc_column_text][/vc_column][vc_column width="1/4"][vc_pie value="87" color="btn-info" units="%"][vc_column_text]
<h3 style="text-align: center;">Massage Expertise</h3>
[/vc_column_text][/vc_column][vc_column width="1/4"][vc_pie value="81" color="btn-success" units="%"][vc_column_text]
<h3 style="text-align: center;">Manicure Expertise</h3>
[/vc_column_text][/vc_column][vc_column width="1/4"][vc_pie value="75" color="btn-warning" units="%"][vc_column_text]
<h3 style="text-align: center;">Pedicure Expertise</h3>
[/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_empty_space height="30px"][vc_separator color="grey"][vc_empty_space height="0"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_column_text css_animation="appear"][showbiz most-popular-staff][/vc_column_text][/vc_column][vc_column width="1/3"][vc_column_text]
<h3 style="text-align: right;">Our Awesome Beauty Expert</h3>
<p style="text-align: right;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
<p style="text-align: right;"><a id="staff-prev" href="javascript(0);"><img class="alignnone size-full wp-image-1252" src="http://bdthemes.net/demo/wordpress/beautysalon/wp-content/uploads/2013/12/prev-slide.png" alt="prev-slide" width="60" height="60" /></a>  <a id="staff-next" href="javascript(0);"><img class="alignnone size-full wp-image-1250" src="http://bdthemes.net/demo/wordpress/beautysalon/wp-content/uploads/2013/12/next-slide.png" alt="next-slide" width="60" height="60" /></a></p>
[/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_empty_space height="30px"][vc_separator][vc_empty_space height="0"][/vc_column][/vc_row][vc_row][vc_column width="1/3"][vc_column_text]
<h3 style="text-align: left;">Our Most Popular Services</h3>
<p style="text-align: left;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
<p style="text-align: left;"><a id="service-prev" href="javascript(0);"><img class="alignnone size-full wp-image-1252" src="http://bdthemes.net/demo/wordpress/beautysalon/wp-content/uploads/2013/12/prev-slide.png" alt="prev-slide" width="60" height="60" /></a>  <a id="service-next" href="javascript(0);"><img class="alignnone size-full wp-image-1250" src="http://bdthemes.net/demo/wordpress/beautysalon/wp-content/uploads/2013/12/next-slide.png" alt="next-slide" width="60" height="60" /></a></p>
[/vc_column_text][/vc_column][vc_column width="2/3"][vc_column_text css_animation="appear"][showbiz most-popular-service][/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_separator][/vc_column][/vc_row][vc_row][vc_column width="1/1"][vc_column_text]
<h3 style="text-align: center;">More Details Our Services</h3>
<p style="text-align: center;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
[/vc_column_text][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_posts_slider type="flexslider_fade" interval="5" slides_title="1" link="link_post" order="DESC" posttypes="services" thumb_size="450x200"][/vc_column][vc_column width="1/2"][vc_toggle title="How to set up the theme?" open="true" css_animation="left-to-right"][dummy_text what="words" amount="50"][/vc_toggle][vc_toggle title="Can I change Plans or cancel at any time?" open="false" css_animation="right-to-left"][dummy_text what="words" amount="42"][/vc_toggle][vc_toggle title="What about Support?" open="false" css_animation="right-to-left"][dummy_text what="words" amount="42"][/vc_toggle][/vc_column][/vc_row]
CONTENT;
  
    
    array_unshift( $data, $template );
    return $data;
}

?>
