import { Loader2Icon } from 'lucide-react'

export function HydrateFallback() {
  return (
    <main className="flex min-h-dvh flex-col items-center justify-center gap-4">
      <Loader2Icon className="animate-spin" />
    </main>
  )
}
