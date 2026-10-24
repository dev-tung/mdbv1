
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
                                <div
                                    class="short-video-preview"
                                    data-video-id="<?= $videoId ?>"
                                    data-video-title="<?= $title ?>">
                                    <div class="short-video-player" id="youtube-player-<?= $videoId ?>"></div>
                                    <button
                                        type="button"
                                        class="short-video-poster"
                                        aria-label="Phát video: <?= $title ?>"
                                        disabled>
                                        <img
                                            src="https://i.ytimg.com/vi/<?= $videoId ?>/hqdefault.jpg"
                                            alt=""
                                            loading="lazy">
                                        <span class="short-video-play" aria-hidden="true"></span>
                                    </button>
                                    <button
                                        type="button"
                                        class="short-video-fullscreen"
                                        aria-label="Toàn màn hình: <?= $title ?>"
                                        title="Toàn màn hình"
                                        hidden>⛶</button>
                                    <span class="short-video-status" role="status" aria-live="polite"></span>
                                </div>
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
        width: 100%;
        aspect-ratio: 9 / 16;
        overflow: hidden;
        isolation: isolate;
        background: #111;
    }

    .short-video-player,
    .short-video-player iframe {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .short-video-poster {
        position: absolute;
        inset: 0;
        display: block;
        width: 100%;
        height: 100%;
        overflow: hidden;
        padding: 0;
        border: 0;
        background: #111;
        cursor: pointer;
    }

    .short-video-poster img {
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

    .short-video-poster:disabled {
        cursor: wait;
    }

    .short-video-preview.is-playing .short-video-poster {
        display: none;
    }

    .short-video-play {
        position: absolute;
        top: 50%;
        left: 50%;
        display: grid;
        width: 48px;
        height: 48px;
        place-items: center;
        border-radius: 50%;
        background: rgb(0 0 0 / 65%);
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

    .short-video-poster:hover .short-video-play,
    .short-video-poster:focus-visible .short-video-play {
        background: #69a84f;
    }

    .short-video-fullscreen {
        position: absolute;
        right: 8px;
        bottom: 8px;
        z-index: 2;
        display: grid;
        width: 40px;
        height: 40px;
        place-items: center;
        border: 0;
        border-radius: 4px;
        background: rgb(0 0 0 / 70%);
        color: #fff;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .short-video-fullscreen[hidden] {
        display: none;
    }

    .short-video-fullscreen:focus-visible {
        outline: 2px solid #fff;
        outline-offset: 2px;
    }

    .short-video-status:not(:empty) {
        position: absolute;
        z-index: 3;
        top: 8px;
        right: 8px;
        left: 8px;
        padding: 0.5rem;
        background: rgb(120 0 0 / 90%);
        color: #fff;
        font-size: 0.8rem;
    }

    .short-video-preview:fullscreen {
        width: 100vw;
        height: 100vh;
        max-width: none;
        max-height: none;
        aspect-ratio: auto;
        background: #000;
    }

    .short-video-preview:-webkit-full-screen {
        width: 100vw;
        height: 100vh;
        max-width: none;
        max-height: none;
        aspect-ratio: auto;
        background: #000;
    }

    .short-video-preview:fullscreen .short-video-player,
    .short-video-preview:fullscreen .short-video-player iframe {
        width: 100%;
        height: 100%;
    }

</style>

<script>
    (function () {
        const previews = document.querySelectorAll('.short-video-preview');
        const players = new WeakMap();
        let apiPromise;

        function loadYouTubeApi() {
            if (window.YT && window.YT.Player) {
                return Promise.resolve(window.YT);
            }

            if (!apiPromise) {
                apiPromise = new Promise(function (resolve, reject) {
                    const previousCallback = window.onYouTubeIframeAPIReady;
                    const script = document.createElement('script');

                    window.onYouTubeIframeAPIReady = function () {
                        if (typeof previousCallback === 'function') {
                            previousCallback();
                        }
                        resolve(window.YT);
                    };
                    script.src = 'https://www.youtube.com/iframe_api';
                    script.onerror = function () {
                        reject(new Error('Không tải được YouTube Player API.'));
                    };
                    document.head.appendChild(script);
                });
            }

            return apiPromise;
        }

        function initializePlayer(preview) {
            if (preview.dataset.playerState) {
                return;
            }

            preview.dataset.playerState = 'loading';
            loadYouTubeApi().then(function (YT) {
                new YT.Player(preview.querySelector('.short-video-player').id, {
                    videoId: preview.dataset.videoId,
                    playerVars: {
                        cc_load_policy: 0,
                        controls: 1,
                        fs: 1,
                        origin: window.location.origin,
                        playsinline: 1,
                        rel: 0
                    },
                    events: {
                        onReady: function (event) {
                            players.set(preview, event.target);
                            const iframe = event.target.getIframe();
                            const poster = preview.querySelector('.short-video-poster');

                            iframe.title = preview.dataset.videoTitle;
                            iframe.allow = 'autoplay; accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen';
                            iframe.allowFullscreen = true;
                            poster.disabled = false;
                            preview.dataset.playerState = 'ready';
                        },
                        onStateChange: function (event) {
                            if (event.data === YT.PlayerState.PLAYING) {
                                preview.classList.add('is-playing');
                                preview.querySelector('.short-video-fullscreen').hidden = false;
                            } else if (event.data === YT.PlayerState.ENDED) {
                                preview.classList.remove('is-playing');
                                preview.querySelector('.short-video-fullscreen').hidden = true;
                            }
                        },
                        onError: function (event) {
                            const status = preview.querySelector('.short-video-status');
                            status.textContent = 'YouTube không thể phát video này (lỗi ' + event.data + ').';
                            preview.dataset.playerState = 'error';
                            preview.querySelector('.short-video-poster').disabled = false;
                        }
                    }
                });
            }).catch(function (error) {
                console.error(error);
                preview.dataset.playerState = 'error';
                preview.querySelector('.short-video-poster').disabled = false;
                preview.querySelector('.short-video-status').textContent = error.message;
            });
        }

        previews.forEach(function (preview) {
            preview.querySelector('.short-video-poster').addEventListener('click', function () {
                const player = players.get(preview);

                if (player && preview.dataset.playerState === 'ready') {
                    preview.classList.add('is-playing');
                    preview.querySelector('.short-video-fullscreen').hidden = false;
                    player.playVideo();
                } else {
                    initializePlayer(preview);
                }
            });

            preview.querySelector('.short-video-fullscreen').addEventListener('click', function () {
                const fullscreenElement = document.fullscreenElement || document.webkitFullscreenElement;
                const exitFullscreen = document.exitFullscreen || document.webkitExitFullscreen;

                if (fullscreenElement === preview && exitFullscreen) {
                    exitFullscreen.call(document);
                    return;
                }

                const requestFullscreen = preview.requestFullscreen || preview.webkitRequestFullscreen;

                if (requestFullscreen) {
                    const result = requestFullscreen.call(preview);
                    if (result && typeof result.catch === 'function') {
                        result.catch(function (error) {
                            console.error('Không thể mở toàn màn hình:', error);
                            preview.querySelector('.short-video-status').textContent =
                                'Không thể mở toàn màn hình. Hãy dùng nút toàn màn hình trong trình phát YouTube.';
                        });
                    }
                } else {
                    const player = players.get(preview);
                    const iframe = player && player.getIframe();
                    const iframeFullscreen = iframe && (iframe.requestFullscreen || iframe.webkitRequestFullscreen);

                    if (iframeFullscreen) {
                        iframeFullscreen.call(iframe);
                    } else {
                        preview.querySelector('.short-video-status').textContent =
                            'Trình duyệt này không hỗ trợ toàn màn hình cho video nhúng.';
                    }
                }
            });
        });

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        initializePlayer(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '400px 0px' });

            previews.forEach(function (preview) {
                observer.observe(preview);
            });
        } else {
            previews.forEach(initializePlayer);
        }
    })();
</script>
