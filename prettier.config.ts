import { fileURLToPath } from 'node:url'
import type { Config } from 'prettier'
import type { PluginOptions as TailwindConfig } from 'prettier-plugin-tailwindcss'

const config = {
  /* General Prettier Config */
  semi: false,
  tabWidth: 2,
  printWidth: 80,
  singleQuote: true,
  trailingComma: 'all',

  plugins: [
    'prettier-plugin-blade',
    'prettier-plugin-tailwindcss',
  ],

  tailwindFunctions: ['cn', 'cva'],
  tailwindStylesheet: fileURLToPath(
    new URL('./resources/css/globals.css', import.meta.url),
  ),

  overrides: [{ files: ['*.tpl.php'], options: { parser: 'blade' } }],
} satisfies Config & TailwindConfig

export default config
