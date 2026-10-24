
<main class="container py-3">

    <!-- TITLE -->
    <h2 class="fw-bold mb-4">
        Vợt cầu lông cũ
    </h2>

    <div class="bg-white p-3 my-3">
        <section class="mt-4">
            <?php if ($videoError !== null): ?>
                <p class="alert alert-warning mb-0"><?= htmlspecialchars($videoError, ENT_QUOTES, 'UTF-8') ?></p>
            <?php elseif ($videos === []): ?>
                <p class="text-muted mb-0">Playlist hiện chưa có video công khai.</p>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-5 g-3">
                    <?php foreach ($videos as $video): ?>
                        <?php
                        $videoId = htmlspecialchars($video['id'], ENT_QUOTES, 'UTF-8');
                        $title = htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8');
                        ?>
                        <div class="col">
                            <article class="card h-100 border-0 shadow-sm rounded-0 short-video-card">
                                <button
                                    type="button"
                                    class="short-video-preview"
                                    data-video-id="<?= $videoId ?>"
                                    data-video-title="<?= $title ?>"
                                    aria-label="Phát video: <?= $title ?>">
                                    <img
                                        src="https://i.ytimg.com/vi/<?= $videoId ?>/hqdefault.jpg"
                                        alt=""
                                        loading="lazy">
                                    <span class="short-video-play" aria-hidden="true"></span>
                                </button>
                                <div class="card-body p-2">
                                    <h3 class="card-title h6 mb-0"><?= $title ?></h3>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

    </div>

</main>

<style>
    .short-video-preview {
        position: relative;
        display: block;
        width: 100%;
        aspect-ratio: 9 / 16;
        overflow: hidden;
        padding: 0;
        border: 0;
        background: #111;
        cursor: pointer;
    }

    .short-video-preview img {
        position: absolute;
        inset: 0;
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
        object-fit: cover;
        object-position: center;
        transform: scale(1.06);
    }

    .short-video-preview iframe {
        position: absolute;
        inset: 0;
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .short-video-play {
        position: absolute;
        top: 50%;
        left: 50%;
        display: grid;
        width: 48px;
        height: 48px;
        padding: 0;
        place-items: center;
        border: 0;
        border-radius: 50%;
        appearance: none;
        color: #fff !important;
        cursor: pointer;
        transform: translate(-50%, -50%);
        box-shadow: 0 2px 10px rgb(0 0 0 / 35%);
    }

    .short-video-play::before {
        width: 0;
        height: 0;
        margin-left: 4px;
        border-top: 9px solid transparent;
        border-bottom: 9px solid transparent;
        border-left: 13px solid #fff;
        content: "";
    }

    .short-video-preview:hover .short-video-play {
        background-color: #e6e6e39f !important;
    }

    .short-video-play:focus-visible {
        outline: 3px solid #fff;
        outline-offset: 2px;
    }
</style>

<script>
    document.querySelectorAll('.short-video-preview').forEach(function (preview) {
        preview.addEventListener('click', function () {
            const videoId = preview.dataset.videoId;
            const iframe = document.createElement('iframe');

            iframe.src = 'https://www.youtube.com/embed/' + encodeURIComponent(videoId) + '?autoplay=1&playsinline=1&mute=1';
            iframe.title = preview.dataset.videoTitle;
            iframe.allow = 'autoplay; accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
            iframe.referrerPolicy = 'strict-origin-when-cross-origin';
            iframe.allowFullscreen = true;

            preview.replaceChildren(iframe);
            preview.disabled = true;
        });
    });
</script>
