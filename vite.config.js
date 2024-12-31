import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/core/app.css', 
                'resources/js/core/app.js',
                'resources/js/core/crud-index.js' // Adicione aqui
            ],
            refresh: true,
        }),
    ],

    /*
    build: {

        //-- para nao mostrar mensagem de warning durante o npm run build
        //-- o maximo padrao é 500kb, aumentando o limite pode evitar o warning
        
        chunkSizeWarningLimit: 1000, // Define o limite em KB (1000 KB neste caso)
        
        //-- caso queira que um arquivo que foi adicionado no app.js via import seja gerado
        //-- em arquivo separadamente

        rollupOptions: {
            output: {
                manualChunks: {
                    fontawesome: ['@fortawesome/fontawesome-free/js/all.js'],
                },
            },
        },
    },
    */
});
