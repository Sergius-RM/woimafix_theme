<?php
/**
 * The template for displaying archive pages
 *
 */

get_header();
?>

<!-- 404 Section Start -->
<section class="lost_page" >
    <div class="container">
        <div class="row align-items-center">
            <div class=" col-sm-10 col-md-8 col-lg-6 mx-auto center-area">
                <h1>
                    404
                </h1>
                <h2>
                Valitettavasti sellaisia<br>
                sivua ei ole olemassa...
                </h2>
                <a class="cta_btn mx-auto" href="/">Etusivulle</a>
            </div>
        </div>
    </div>

    <div class="container-fluid lost_search">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-4 mx-auto text-center">
                    <h3>tai käytä sivustohakua</h3>
                    <div class="header_search">
                        <?php get_search_form();?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- 404 Section End -->

<?php
get_footer();
