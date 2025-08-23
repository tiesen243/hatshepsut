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

  server: {
    proxy: {
      '^/$': {
        target: 'http://localhost:8000',
        changeOrigin: true,
        secure: false,
      },
    },
  },

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

  const scan = (dir: string, exts: string[]) => {
    const absDir = path.resolve(__dirname, dir)
    if (!fs.existsSync(absDir)) return

    for (const file of fs.readdirSync(absDir)) {
      const ext = path.extname(file)
      if (exts.includes(ext)) {
        const name = path.basename(file, ext)
        inputs[name] = path.join(absDir, file)
      }
    }
  }

  scan('resources/css', ['.css'])
  scan('resources/js', ['.js', '.jsx', '.ts', '.tsx'])

  return inputs
}
