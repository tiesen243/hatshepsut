import { Button, buttonVariants } from '@/components/ui/button'
import { useTheme } from '@/hooks/use-theme'
import { cn } from '@/lib/utils'
import { SunIcon, MoonIcon, Loader2Icon } from 'lucide-react'
import { NavLink } from 'react-router'

export function Header() {
  const { theme, toggleTheme } = useTheme()

  return (
    <header className="sticky inset-0 z-50 flex h-14 items-center border-b bg-background/70 backdrop-blur-xl backdrop-saturate-150">
      <div className="container flex items-center justify-between gap-4">
        <a href="/" className="text-lg font-bold">
          Hatshepsut
        </a>

        <nav className="flex flex-1 items-center justify-end gap-2">
          {navs.map((nav) => (
            <NavLink
              key={nav.href}
              to={nav.href}
              className={({ isActive }) =>
                cn(
                  buttonVariants({ variant: 'link', size: 'sm' }),
                  isActive ? 'underline' : undefined,
                )
              }
            >
              {({ isPending }) => (
                <>
                  {nav.name}
                  {isPending && <Loader2Icon className="animate-spin" />}
                </>
              )}
            </NavLink>
          ))}
        </nav>

        <Button onClick={toggleTheme} variant="ghost" size="icon">
          {theme === 'light' ? <MoonIcon /> : <SunIcon />}
        </Button>
      </div>
    </header>
  )
}

const navs = [
  { name: 'Home', href: '/' },
  { name: 'About', href: '/about' },
]
