// common/render/UIRenderer.js

import { UIState } from "../state/UIState.js";

export const UIRenderer = {

    renderLoading() {

        let loading = document.getElementById("app-loading");

        if (!loading) {

            loading = document.createElement("div");

            loading.id = "app-loading";

            loading.className = `
                position-fixed
                top-0
                start-0
                w-100
                h-100
                d-none
                justify-content-center
                align-items-center
                bg-white
                bg-opacity-75
            `;

            loading.style.zIndex = "9999";

            loading.innerHTML = `
                <div class="card shadow">

                    <div class="card-body text-center p-4">

                        <div class="spinner-border text-primary mb-3"
                             role="status">
                        </div>

                        <div class="loading-text">
                            Đang tải...
                        </div>

                    </div>

                </div>
            `;

            document.body.appendChild(loading);

        }

        loading.querySelector(".loading-text").textContent =
            UIState.loadingText;

        loading.classList.toggle("d-none", !UIState.loading);

        loading.classList.toggle("d-flex", UIState.loading);

    }

};