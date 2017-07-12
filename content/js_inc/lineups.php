<script src="<?= INC_PUBLIC ?>js/jquery.countdown.min.js"></script>
<script src="<?= INC_PUBLIC ?>js/jquery.number.min.js"></script>

<script>
    $(document).ready(function () {

        var lineups = new Lineup();

        $('.ul_game_cate > li').click(function () {
            $('.ul_game_cate > li').removeClass('checked');
            $(this).addClass('checked');
            //
            var this_cate = $(this).attr('data-index');
            //
            lineups.setCategory(this_cate);
            postAjax(this_cate, 0);
            naviClick(this_cate);
            moveClick(this_cate);
            //
            event.stopPropagation();
        });

        function Lineup() {
            var category = 0;
            this.getCategory = function () {
                return category;
            };
            this.setCategory = function (cate) {
                return category = cate;
            };

            postAjax(this.getCategory(), 0);
            naviClick(this.getCategory());
            moveClick(this.getCategory());
        }

        function postAjax(index, page) {
            var data = {
                'category': index,
                'page': page
            };
            console.log(data);
            $.post('ajax/lineup_list.php', data, function (data) {
                $('#body_page').html(data);
            });
        }

        function naviClick(this_cate) {
            $(document).on('click', '.paging', function () {
                var page = $(this).attr('data-num-index') - 1;
                postAjax(this_cate, page);
            });
        }

        function moveClick(this_cate) {
            $(document).on('click', '.page_move', function () {
                var page = $(this).attr('data-num-next') - 1;
                postAjax(this_cate, page);
            });
        }
    });
</script>