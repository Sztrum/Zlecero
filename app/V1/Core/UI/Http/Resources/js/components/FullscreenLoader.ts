export default class FullscreenLoader {
    constructor(
        private fullscreenLoaderElement: HTMLElement
    ) {
    }

    public init() {
        window.showFullscreenLoader = this.show.bind(this);
        window.hideFullscreenLoader = this.hide.bind(this);
        window.toggleFullscreenLoader = this.toggle.bind(this);
    }

    private show() {
        this.fullscreenLoaderElement.classList.remove("!hidden");
        this.fullscreenLoaderElement.style.opacity = "0";

        this.fullscreenLoaderElement.style.transition = "opacity 0.5s ease";

        setTimeout(() => {
            this.fullscreenLoaderElement.style.opacity = "1";
        }, 10);
    }

    private hide() {
        this.fullscreenLoaderElement.style.opacity = "1";

        requestAnimationFrame(() => {
            this.fullscreenLoaderElement.style.transition = "opacity 0.5s ease";
            this.fullscreenLoaderElement.style.opacity = "0";

            setTimeout(() => {
                this.fullscreenLoaderElement.classList.add("!hidden");
            }, 500);
        });
    }

    private toggle() {
        if (this.fullscreenLoaderElement.classList.contains("!hidden")) {
            this.show();
        } else {
            this.hide();
        }
    }
}
