<script>
    $(document).ready(function () {
        var $event_box = $('.event_box')
        $event_box.css('cursor', 'pointer');
        $event_box.click(function () {
            var index = $(this).attr('data-index');
            location.replace('index.php?menu=events&sub=' + index);
        });
        $('.btn_goBack').click(function(){
            location.replace('index.php?menu=events');
        });
    });
</script>