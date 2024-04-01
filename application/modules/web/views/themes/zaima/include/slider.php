<?php if (!empty($slider_list)) { ?>
<link href="<?php echo MOD_URL.'web/views/themes/zaima/assets/css/custome.css'; ?>" rel="stylesheet">
<!--Hero slider-->
<div class="container">
<!-- <div class="hero-slider position-relative"> -->
<script>
$(document).ready(function() {
    var owl = $('.owl-carousel');

    owl.owlCarousel({
        items: 1,
        loop: true,
        autoplay: false, // Start with autoplay off
        autoplayTimeout: 60000, // Set to 60 seconds
        autoplayHoverPause: true
    });

    owl.on('changed.owl.carousel', function(event) {
        var currentItem = event.item.index;
        var currentSlide = $(event.target).find(".owl-item").eq(currentItem).find("video");

        if (currentSlide.length) { // If the current slide has a video
            setTimeout(function() {
                owl.trigger('stop.owl.autoplay'); // Stop the autoplay
                currentSlide[0].play(); // Play the video

                // Wait for 1 minute before moving to the next slide
                setTimeout(function() {
                    owl.trigger('next.owl.carousel'); // Move to next slide
                    owl.trigger('play.owl.autoplay'); // Resume autoplay
                }, 60000); // 60000ms = 1 minute

            }, 500); // Delay to ensure the slide is fully loaded
        }
    });
});
</script>

    <div class="header-slider header-slider-preloader">
        <div class="animation-slide owl-carousel owl-theme ">
            <video width="1308" height="409" class="item slider-item bg-img-hero d-flex align-items-center justify-content-center" id="responsiveVideo" autoplay muted>
              <source src="<?php echo base_url() ?>perfumes.mp4" type="video/mp4">
            </video>
            <?php
            foreach ($slider_list as $slider) {
            ?>
            <div class="item slider-item bg-img-hero d-flex align-items-center justify-content-center">
                <a href="<?php echo $slider['slider_link']; ?>">
                    <img src="<?php echo base_url() . $slider['slider_image'] ?>">
                </a>
            </div>
            <?php }  ?>
        </div>
    </div>

    <!--Slider Preloader-->
    <div class="slider_preloader">
        <div class="slider_preloader_status">&nbsp;</div>
    </div>
</div>
<style>
  #responsiveVideo {
    width: 100%;
    height: auto;
  }

  @media screen and (max-width: 767px) {
    #responsiveVideo {
      height: 106px; /* You might need to adjust this based on your design */
      height: auto;
    }
  }
</style>


<?php } ?>