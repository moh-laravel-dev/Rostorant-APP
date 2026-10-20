import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/colors.css",
                "resources/js/app.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },

        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        hmr: {
            host: "192.168.1.137",
            // host: "192.168.8.100",
        },
        cors: {
            origin: [
                "http://192.168.1.137:8000",
                "http://192.168.8.100:8000",
                "http://localhost:8000",
            ],
        },
    },
});
