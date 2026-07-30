<div class="d-flex gap-2">


    <div class="btn-group btn-group-sm">


        <a 
            href="<?= route('/job/ducan/list') ?>"
            class="btn btn-outline-secondary <?= active_menu('/job/ducan/list') ?>"
        >
            List
        </a>


        <a 
            href="<?= route('/job/ducan/matched') ?>"
            class="btn btn-outline-secondary <?= active_menu('/job/ducan/matched') ?>"
        >
            Matched
        </a>


        <a 
            href="<?= route('/job/ducan/unmatched') ?>"
            class="btn btn-outline-secondary <?= active_menu('/job/ducan/unmatched') ?>"
        >
            Unmatched
        </a>


    </div>



    <a 
        href="<?= route('/job/ducan/crawl') ?>"
        class="btn btn-sm btn-outline-danger"
        onclick="return confirm('Bạn có muốn crawl dữ liệu mới từ Đức An?')"
    >
        Crawl
    </a>


</div>