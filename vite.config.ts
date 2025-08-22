import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite'
import liveReload from 'vite-plugin-live-reload'

export default defineConfig({
  plugins: [
    liveReload(['app/**/*.php', 'resources/views/**/*.tpl.php']),
    tailwindcss(),
  ],

  build: {
    outDir: 'public/dist',
    manifest: true,
    copyPublicDir: false,
    rollupOptions: {
      input: {
        globals: 'resources/css/globals.css',
        theme: 'resources/js/theme.js',
      },
      output: {
        assetFileNames(chunkInfo) {
          const fileName = chunkInfo.originalFileNames.at(0) ?? ''
          if (fileName.endsWith('.css')) return 'css/[name].css'
          return 'assets/[name].[extname]'
        },
        entryFileNames: 'js/[name].js',
      },
    },
  },
})
