<?php 
/*
 * Template Name: Testimonial Search
*/
get_header(); ?>

<main class="main">
    <section id="contact" class="contact section">
    	<div class="container" data-aos="fade-up" data-aos-delay="100">
	    	  	<div class="row gy-4">
		    	  	<?php if (is_user_logged_in()): ?>
			    	    <div class="col-lg-6">
			    	      <form class="php-email-form" id="testimonial-search-form">
			    	          	<input type="text" class="form-control" id="testimonial-title" placeholder="Search by testimonial title" />
			    	          	<div class="col text-center mt-1">
			    	          		<button type="submit" class="contact-form-button">Search</button>
								</div>    	      			
			    	      </form>
			    	    </div>
		    		<?php endif;?>
	    	  	</div>
	    		<div id="search-results"></div>
		    	<?php if (!is_user_logged_in()): ?>
					<p class="mt-5">Only logged in user allowed to serach testimonials.</p>
				<?php endif; ?>
    	</div>
	</section>
</main>

<?php
get_footer();
