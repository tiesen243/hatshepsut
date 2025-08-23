import fs from 'node:fs'
import path from 'node:path'

import type { Plugin } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite'
import liveReload from 'vite-plugin-live-reload'

export default defineConfig({
  plugins: [
    liveReload(['app/**/*.php', 'resources/views/**/*.tpl.php']),
    tailwindcss(),
    cleanPublicFolder(),
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
    outDir: 'public',
    manifest: true,
    copyPublicDir: false,
    emptyOutDir: false,
    rollupOptions: {
      input: getInputs(),
      output: {
        assetFileNames(chunkInfo) {
          const fileName = chunkInfo.originalFileNames.at(0) ?? ''
          if (fileName.endsWith('.css')) return 'assets/css/[name].css'
          return 'assets/[name].[extname]'
        },
        entryFileNames: 'assets/js/[name].js',
        chunkFileNames: 'assets/[name]-[hash].js',
      },
    },
  },
})

function cleanPublicFolder(): Plugin {
  return {
    name: 'clean-public-folder',
    apply: 'build',
    buildStart() {
      const publicDir = path.resolve(__dirname, 'public')
      const excludeDirs = [
        path.join('assets', 'images'),
        path.join('assets', 'fonts'),
      ].map((f) => path.resolve(publicDir, f))

      const targets = [
        path.resolve(publicDir, 'assets'),
        path.resolve(publicDir, '.vite'),
      ]

      for (const target of targets) {
        if (fs.existsSync(target)) {
          if (excludeDirs.some((ex) => target === ex)) continue

          if (path.basename(target) === 'assets') {
            for (const entry of fs.readdirSync(target)) {
              const entryPath = path.join(target, entry)
              if (!excludeDirs.includes(entryPath))
                fs.rmSync(entryPath, { recursive: true, force: true })
            }
          } else fs.rmSync(target, { recursive: true, force: true })
        }
      }
    },
  }
}

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
