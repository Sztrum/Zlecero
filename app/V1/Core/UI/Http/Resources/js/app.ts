import "./plugins/toastify-js.js";
import FullscreenLoader from "./components/FullscreenLoader";

document.addEventListener("DOMContentLoaded", () => {
    if(document.querySelector('#hidden-fullscreen-loader')) {
        new FullscreenLoader(
            document.querySelector('#hidden-fullscreen-loader') as HTMLElement,
        ).init();
    }
});

import.meta.glob(["../images/**"]);
