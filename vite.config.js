import { defineConfig, loadEnv, normalizePath } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";
import Inspect from 'vite-plugin-inspect';
import 'dotenv/config';
import { viteStaticCopy } from 'vite-plugin-static-copy'

const publicPath = path.resolve(__dirname, "public");

export default ({ mode }) => {
    process.env = {...process.env, ...loadEnv(mode, process.cwd())};

    return defineConfig({
        server: {
            hmr: {
                host: process.env.VITE_DEV_DOMAIN,
            },
            host: process.env.VITE_DEV_DOMAIN
        },
        build: {
            outDir: path.join(publicPath, "vite"),
        },
        plugins: [
            Inspect(),
            laravel({
                hotFile: path.join(publicPath, "hot-vite"),
                buildDirectory: "vite",
                input: [
                    "app/V1/Core/UI/Http/Resources/scss/style.scss",
                    "app/V1/Core/UI/Http/Resources/scss/plugins/_toastify.scss",

                    "app/V1/Core/UI/Http/Resources/js/app.ts",
                    "app/V1/Core/UI/Http/Resources/js/plugins/toastify-js.js",
                ],
                refresh: true,
            })
        ],
    })
}
