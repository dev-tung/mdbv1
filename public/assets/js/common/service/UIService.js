import { UIState } from "../state/UIState.js";
import { UIRenderer } from "../render/UIRenderer.js";

export const UIService = {

    showLoading(text = "Đang tải...") {

        UIState.loading = true;

        UIState.loadingText = text;

        UIRenderer.renderLoading();

    },

    hideLoading() {

        UIState.loading = false;

        UIState.loadingText = "";

        UIRenderer.renderLoading();

    }

};