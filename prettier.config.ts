import type { Config } from 'prettier'
import type { PluginOptions } from 'prettier-plugin-tailwindcss'

const config = {
  /* General Prettier Config */
  semi: false,
  tabWidth: 2,
  printWidth: 80,
  singleQuote: true,
  trailingComma: 'all',

  plugins: ['prettier-plugin-blade', 'prettier-plugin-tailwindcss'],

  tailwindFunctions: ['cn', 'cva'],
  overrides: [{ files: ['*.tpl.php'], options: { parser: 'blade' } }],
} satisfies Config & PluginOptions

export default config
