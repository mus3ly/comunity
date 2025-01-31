
<?php
$url = base_url('html/');
include "header.php";
?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <?php
    // die($page_name);
    ?>
    <div class="content-area" page_name="<?= $page_name?>">
        <?php
        include $page_name.'/index.php';
        ?>
    </div>
    <!-- /CONTENT AREA -->

    <!-- FOOTER -->
    <?php
    $footer = '1';
    include 'footer/footer_'.$footer.'.php';
    ?>
    <!-- /FOOTER -->

    <div id="to-top" class="to-top"><i class="fa fa-angle-up"></i></div>

</div>
<!-- /WRAPPER -->


<?php
include 'script_texts.php';
?>
<?php
include 'includes/bottom/index.php';
?>


<!-- for demo only -->

<?php if(demo()) { ?>
<div class="home-switch">
    <button>
        <i class="fa fa-cog fa-spin"></i>Home
    </button>
    <div class="preview">
        <div class="1 home-preview" url="<?php echo base_url() ?>?requested_homepage=1">
            <div>Home 1</div>
            <img src="<?php echo base_url() ?>uploads/home_pages/home_1.jpg" alt="">
        </div>
        <div class="1 home-preview" url="<?php echo base_url() ?>?requested_homepage=2">
            <div>Home 2</div>
            <img src="<?php echo base_url() ?>uploads/home_pages/home_2.jpg" alt="">
        </div>
        <div class="1 home-preview" url="<?php echo base_url() ?>?requested_homepage=3">
            <div>Home 3</div>
            <img src="<?php echo base_url() ?>uploads/home_pages/home_3.jpg" alt="">
        </div>
    </div>
</div>
<style>
    .home-switch.active {
        right: 0px;
    }
    .home-switch {
        position: fixed;
        right: -220px;
        top: 20vh;
        z-index: 99999;
        box-shadow: 0 0 20px rgba(0,0,0,0.5);
        background: #fff;
        transition: all 0.3s;
        -webkit-transition: all 0.3s;
    }

    .home-switch .preview > div {
        height: 200px;
        width: 200px;
        overflow-y: auto;
        margin: 10px;
        border: 1px solid #ddd;
        cursor: pointer;
    }

    .home-switch .preview div img {
        width: 100%;
    }

    .home-switch button {
        position: absolute;
        background: #000000;
        border: 0;
        right: 100%;
        font-size: 18px;
        padding: 10px;
        box-shadow: -7px 0 10px rgba(0,0,0,0.3);
        border-right: 1px solid #ddd;
        color: #fff;
        font-weight: 700;
    }
    .home-switch .preview > div div {
        padding: 5px;
        font-size: 15px;
        text-align: center;
        background: #000;
        color: #fff;
        font-weight: 700;
    }
</style>
<script>
    $(document).ready(function(){
        $('.home-switch button').on('click', function(){
            if ($('.home-switch').hasClass('active')) {
                $('.home-switch').removeClass('active');
            } else{
                $('.home-switch').addClass('active');
            }
        });

        $('.home-switch').on('click', function (e) {
            e.stopPropagation();
        });

        $('.home-preview').on('click', function (e) {
            window.location.href =  $(this).attr('url');
        });

        $(document).on('click', function (e) {
            if ($('.home-switch').hasClass('active')) {
                $('.home-switch').removeClass('active');
            }
        });
    });

</script>
<?php } ?>

<!-- for demo only -->



</body>
</html>