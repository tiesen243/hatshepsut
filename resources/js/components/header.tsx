import { Link, NavLink } from 'react-router'
import { MoonIcon, SunIcon } from 'lucide-react'

import { Button } from '@/components/ui/button'
import { useTheme } from '@/hooks/use-theme'

export function Header() {
  const { theme, toggleTheme } = useTheme()

  return (
    <header className="inline-flex h-14 items-center border-b bg-background/70 backdrop-blur-xl">
      <div className="container inline-flex items-center justify-between gap-6">
        <Link to="/" className="text-lg font-bold">
          Hatshepsut
        </Link>

        <nav className="inline-flex flex-1 items-center justify-end gap-4 [&_a]:text-muted-foreground [&_a]:hover:text-foreground [&_a]:aria-[current='page']:text-foreground">
          <NavLink to="/">Home</NavLink>
          <NavLink to="/about">About</NavLink>
          <NavLink to="/posts">Posts</NavLink>
        </nav>

        <Button
          variant="outline"
          size="icon"
          onClick={toggleTheme}
          aria-label="Toggle Theme"
        >
          {theme === 'dark' ? <MoonIcon /> : <SunIcon />}
        </Button>
      </div>
    </header>
  )
}
