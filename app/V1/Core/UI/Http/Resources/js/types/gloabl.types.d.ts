export {};

declare global {
    interface Window {
        toastSuccess: (message: string) => void;
        toastWarning: (message: string) => void;
        toastDanger: (message: string) => void;
        showFullscreenLoader: () => void;
        hideFullscreenLoader: () => void;
        toggleFullscreenLoader: () => void;
    }
}
