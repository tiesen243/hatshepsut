import fs from 'node:fs'
import path from 'node:path'

import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite'
import liveReload from 'vite-plugin-live-reload'

export default defineConfig({
  plugins: [
    liveReload(['app/**/*.php', 'resources/views/**/*.tpl.php']),
    tailwindcss(),
  ],

  build: {
    outDir: 'public/assets',
    manifest: true,
    copyPublicDir: false,
    rollupOptions: {
      input: getInputs(),
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

function getInputs() {
  const inputs: Record<string, string> = {}

  // scan resources/css
  const cssDir = path.resolve(__dirname, 'resources/css')
  if (fs.existsSync(cssDir)) {
    for (const file of fs.readdirSync(cssDir)) {
      if (file.endsWith('.css')) {
        const name = path.basename(file, '.css')
        inputs[name] = path.join(cssDir, file)
      }
    }
  }

  // scan resources/js
  const jsDir = path.resolve(__dirname, 'resources/js')
  if (fs.existsSync(jsDir)) {
    for (const file of fs.readdirSync(jsDir)) {
      if (file.endsWith('.js')) {
        const name = path.basename(file, '.js')
        inputs[name] = path.join(jsDir, file)
      }
    }
  }

  return inputs
}
