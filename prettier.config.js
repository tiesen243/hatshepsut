/** @typedef {import("prettier").Config} PrettierConfig */
/** @typedef {import("prettier-plugin-tailwindcss").PluginOptions} TailwindConfig */
/** @typedef {import("@ianvs/prettier-plugin-sort-imports").PluginConfig} SortImportsConfig */

import { fileURLToPath } from 'node:url'

/** @type {PrettierConfig & TailwindConfig & SortImportsConfig} */
const config = {
  /* General Prettier Config */
  semi: false,
  tabWidth: 2,
  printWidth: 80,
  singleQuote: true,
  trailingComma: 'all',

  plugins: [
    '@ianvs/prettier-plugin-sort-imports',
    'prettier-plugin-blade',
    'prettier-plugin-tailwindcss',
  ],

  tailwindFunctions: ['cn', 'cva'],
  tailwindStylesheet: fileURLToPath(
    new URL('./resources/css/globals.css', import.meta.url),
  ),

  importOrder: [
    '<TYPES>',
    '^(react/(.*)$)|^(react$)|^(react-native(.*)$)',
    '^(react-router/(.*)$)|^(react-router$)',
    '<THIRD_PARTY_MODULES>',
    '',
    '<TYPES>^(@/(.*)$)',
    '<TYPES>^[.|..]',
    '^@/',
    '^[../]',
    '^[./]',
  ],
  importOrderParserPlugins: ['typescript', 'jsx', 'decorators-legacy'],
  importOrderTypeScriptVersion: '4.4.0',

  overrides: [{ files: ['*.tpl.php'], options: { parser: 'blade' } }],
} 

export default config
