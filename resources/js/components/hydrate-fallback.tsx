import { Loader2Icon } from 'lucide-react'

export function HydrateFallback() {
  return (
    <main className="flex min-h-screen flex-col items-center justify-center">
      <Loader2Icon className="animate-spin" />
    </main>
  )
}
