import { useNavigate } from 'react-router'
import { useMutation, useQueryClient } from '@tanstack/react-query'

import { postFilters, postOptions } from '@/api/post'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

export function Component() {
  const navigate = useNavigate()
  const queryClient = useQueryClient()
  const { mutate, isPending } = useMutation({
    ...postOptions.store(),
    onSuccess: async () => {
      await queryClient.invalidateQueries(postFilters.all())
      await navigate('/posts')
    },
  })

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault()
    e.stopPropagation()

    const formData = new FormData(e.currentTarget)
    const title = String(formData.get('title'))
    const content = String(formData.get('content'))
    mutate({ title, content })
  }

  return (
    <main className="container py-4">
      <form onSubmit={handleSubmit} className="grid gap-4">
        <div className="grid gap-2">
          <Label htmlFor="title">Title</Label>
          <Input name="title" disabled={isPending} />
        </div>

        <div className="grid gap-2">
          <Label htmlFor="content">Content</Label>
          <Input name="content" disabled={isPending} />
        </div>

        <Button type="submit" disabled={isPending}>
          {isPending ? 'Creating...' : 'Create Post'}
        </Button>
      </form>
    </main>
  )
}
