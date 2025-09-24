import fs from 'node:fs'
import path from 'node:path'
import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite'

export default defineConfig({
  plugins: [tailwindcss()],

  build: {
    outDir: 'public/build',
    assetsDir: '.',
    manifest: true,
    copyPublicDir: false,
    modulePreload: { resolveDependencies: (dep) => [`build/${dep}`] },
    rollupOptions: { input: getInputs() },
  },
})

function getInputs() {
  const inputs: Record<string, string> = {}

  function scan(dir: string, exts: string[]) {
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

  scan('resources/js', ['.js', '.jsx', '.ts', '.tsx'])
  scan('resources/css', ['.css', '.scss', '.sass', '.less'])

  return inputs
}
