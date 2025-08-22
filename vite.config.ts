import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite'

export default defineConfig({
  plugins: [tailwindcss()],

  build: {
    outDir: 'public/dist',
    manifest: true,
    copyPublicDir: false,
    rollupOptions: {
      input: {
        globals: 'resources/css/globals.css',
        index: 'resources/js/index.js',
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
