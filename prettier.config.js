import { fileURLToPath } from 'node:url'

/** @typedef {import("prettier").Config} PrettierConfig */
/** @typedef {import("prettier-plugin-tailwindcss").PluginOptions} TailwindConfig */
/** @typedef {import("@ianvs/prettier-plugin-sort-imports").PluginConfig} SortImportsConfig */

/** @type { PrettierConfig | SortImportsConfig | TailwindConfig } */
const config = {
  /* General Prettier Config */
  semi: false,
  tabWidth: 2,
  printWidth: 80,
  singleQuote: true,
  trailingComma: 'all',

  plugins: [
    '@prettier/plugin-php',
    'prettier-plugin-blade',
    'prettier-plugin-tailwindcss',
  ],

  tailwindFunctions: ['cn', 'cva'],
  tailwindAttributes: ['className', 'tw'],
  tailwindStylesheet: fileURLToPath(
    new URL('./resources/css/globals.css', import.meta.url),
  ),

  overrides: [{ files: '*.tpl.php', options: { parser: 'blade' } }],
}

export default config
