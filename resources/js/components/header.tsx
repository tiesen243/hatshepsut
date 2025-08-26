import { Button } from '@/components/ui/button'
import { useTheme } from '@/hooks/use-theme'
import { SunIcon, MoonIcon } from 'lucide-react'

export function Header() {
  const { theme, toggleTheme } = useTheme()

  return (
    <header className="sticky inset-0 z-50 flex h-14 items-center border-b bg-background/70 backdrop-blur-xl backdrop-saturate-150">
      <div className="container flex items-center justify-between gap-4">
        <a href="/" className="text-lg font-bold">
          Hatshepsut
        </a>

        <Button onClick={toggleTheme} variant="ghost" size="icon">
          {theme === 'light' ? <MoonIcon /> : <SunIcon />}
        </Button>
      </div>
    </header>
  )
}
