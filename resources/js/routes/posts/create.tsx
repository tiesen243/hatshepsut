import { api } from '@/api'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { useMutation, useQueryClient } from '@tanstack/react-query'
import { useNavigate } from 'react-router'
import { toast } from 'sonner'

export function Component() {
  const queryClient = useQueryClient()
  const navigate = useNavigate()

  const { mutate, isPending, error } = useMutation({
    ...api.post.create.mutationOptions(),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: api.post.all.queryKey() })
      navigate('/posts')
    },
    onError: (error) => error instanceof Error && toast.error(error.message),
  })

  return (
    <main className="container py-4">
      <h1 className="text-2xl font-bold">Create Post</h1>

      <form
        className="mt-4 grid gap-4"
        onSubmit={async (e) => {
          e.preventDefault()
          e.stopPropagation()

          const formData = new FormData(e.currentTarget)
          const title = formData.get('title') as string
          const content = formData.get('content') as string

          mutate({ title, content })
        }}
      >
        <div className="grid gap-2">
          <Label htmlFor="title">Title</Label>
          <Input id="title" name="title" disabled={isPending} required />
          {!(error instanceof Error) && error?.title && (
            <p className="text-sm text-destructive">{error.title}</p>
          )}
        </div>

        <div className="grid gap-2">
          <Label htmlFor="content">Content</Label>
          <Textarea id="content" name="content" disabled={isPending} required />
          {!(error instanceof Error) && error?.content && (
            <p className="text-sm text-destructive">{error.content}</p>
          )}
        </div>

        <Button type="submit" disabled={isPending}>
          Create Post
        </Button>
      </form>
    </main>
  )
}
