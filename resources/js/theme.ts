import { createIcons, Sun, Moon } from 'lucide'
createIcons({
  icons: {
    Sun,
    Moon,
  },
})

let resolvedTheme
;(function () {
  const savedTheme = localStorage.getItem('theme')

  if (savedTheme) {
    resolvedTheme = savedTheme
  } else {
    resolvedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches
      ? 'dark'
      : 'light'
    localStorage.setItem('theme', resolvedTheme)
  }
  document.documentElement.classList.add(resolvedTheme)
  document.documentElement.classList.remove(
    resolvedTheme === 'dark' ? 'light' : 'dark',
  )
})()

const themeToggle = document.getElementById('theme-toggle')

if (themeToggle) {
  themeToggle.setAttribute('data-theme', resolvedTheme)
  themeToggle.addEventListener('click', () => {
    const restoreAnimation = disableAnimation(themeToggle.getAttribute('nonce'))

    document.documentElement.classList.toggle('dark')
    document.documentElement.classList.toggle('light')

    const currentTheme = document.documentElement.classList.contains('dark')
      ? 'dark'
      : 'light'
    localStorage.setItem('theme', currentTheme)
    themeToggle.setAttribute('data-theme', currentTheme)

    restoreAnimation()
  })
}

function disableAnimation(nonce: string | null) {
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
}
