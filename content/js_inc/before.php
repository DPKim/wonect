<script>
    $(document).ready(function () {
        $.ajaxSetup({
            beforeSend: ajaxStart,
            complete: ajaxComplete
        });

        // ajax 로딩 시 처리
        function ajaxStart() {
            $('#box_loading').show();
            //
            footer_pos();
            var scroll_top = $(document).scrollTop();
            all_size(scroll_top);
        }
        function ajaxComplete() {
            $('#box_loading').hide();
            footer_pos();
        }
    });
</script>
<?php
if ($get_menu !== 'admin') {
    ?>
    <!-- Modal -->
    <div id="box_loading">
        <div class="light_room"></div>
        <div class="lb_container">
            <div class="create_title lb_box">
                <span class="dp_title">
                    <i class="fa fa-cog fa-spin fa-3x fa-fw"></i><br>
                    LOADING
                </span>
            </div>
        </div>
    </div>
    <?php
}
?>