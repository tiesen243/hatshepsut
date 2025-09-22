import * as React from 'react'

type Theme = 'dark' | 'light' | 'system'

type ThemeProviderProps = {
  children: React.ReactNode
  defaultTheme?: Theme
  storageKey?: string
}

type ThemeProviderState = {
  theme: Theme
  setTheme: (theme: Theme) => void
  toggleTheme: () => void
}

const initialState: ThemeProviderState = {
  theme: 'system',
  setTheme: () => null,
  toggleTheme: () => null,
}

const ThemeProviderContext =
  React.createContext<ThemeProviderState>(initialState)

export function ThemeProvider({
  children,
  defaultTheme = 'system',
  storageKey = 'theme',
  ...props
}: ThemeProviderProps) {
  const [theme, setTheme] = React.useState<Theme>(
    () => (localStorage.getItem(storageKey) as Theme) || defaultTheme,
  )

  const disableAnimation = React.useCallback((nonce: string | null) => {
    const css = document.createElement('style')
    if (nonce) css.setAttribute('nonce', nonce)
    css.appendChild(
      document.createTextNode(
        `*,*::before,*::after{-webkit-transition:none!important;-moz-transition:none!important;-o-transition:none!important;-ms-transition:none!important;transition:none!important}`,
      ),
    )
    document.head.appendChild(css)

    return () => {
      ;(() => window.getComputedStyle(document.body))()
      setTimeout(() => {
        document.head.removeChild(css)
      }, 1)
    }
  }, [])

  React.useEffect(() => {
    const root = window.document.documentElement
    const restoreAnimation = disableAnimation(root.getAttribute('nonce'))

    root.classList.remove('light', 'dark')

    if (theme === 'system') {
      const systemTheme = window.matchMedia('(prefers-color-scheme: dark)')
        .matches
        ? 'dark'
        : 'light'

      root.classList.add(systemTheme)
    } else {
      root.classList.add(theme)
    }

    restoreAnimation()
  }, [theme])

  const value = React.useMemo(
    () => ({
      theme,
      setTheme: (theme: Theme) => {
        const restoreAnimation = disableAnimation(
          document.documentElement.getAttribute('nonce'),
        )
        localStorage.setItem(storageKey, theme)
        setTheme(theme)
        restoreAnimation()
      },
      toggleTheme: () => {
        const restoreAnimation = disableAnimation(
          document.documentElement.getAttribute('nonce'),
        )
        const newTheme = theme === 'dark' ? 'light' : 'dark'
        localStorage.setItem(storageKey, newTheme)
        setTheme(newTheme)
        restoreAnimation()
      },
    }),
    [theme, storageKey, disableAnimation],
  )

  return (
    <ThemeProviderContext {...props} value={value}>
      {children}
    </ThemeProviderContext>
  )
}

export const useTheme = () => {
  const context = React.useContext(ThemeProviderContext)

  if (context === undefined)
    throw new Error('useTheme must be used within a ThemeProvider')

  return context
}
