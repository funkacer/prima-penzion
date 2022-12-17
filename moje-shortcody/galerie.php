<?php
    $poleSouboruASlozek = scandir("./upload/source/fotoalbum");
?>

<div class="obrazky" id = "my-gallery">
    <?php
        foreach($poleSouboruASlozek AS $jmeno){
            //echo $jmeno;

            if ($jmeno != "." && $jmeno != "..") {

                $poleDimenzi = getimagesize("./upload/source/fotoalbum/$jmeno");
                $sirka = $poleDimenzi[0];
                $vyska = $poleDimenzi[1];

                echo "<a href='./upload/source/fotoalbum/$jmeno' data-pswp-width = '$sirka' data-pswp-height = '$vyska'>
                <img src='./upload/source/fotoalbum/$jmeno' alt='fotka'>
                </a>";
            }
        }
    ?>
</div>

<!-- nejprve připojíme css knihovny photoswipe -->
<link rel="stylesheet" href="./vendor/PhotoSwipe-master/dist/photoswipe.css">

<script type="module">
    //modul esm
    import PhotoSwipeLightbox from './vendor/PhotoSwipe-master/dist/photoswipe-lightbox.esm.js';
    const lightbox = new PhotoSwipeLightbox({
    gallery: '#my-gallery',
    children: 'a',
    pswpModule: () => import('./vendor/PhotoSwipe-master/dist/photoswipe.esm.js')
    });
    lightbox.init();
</script>