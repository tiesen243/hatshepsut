import { postFilters, postOptions, type Post } from '@/api/post'
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
  CardAction,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Trash2Icon } from 'lucide-react'

export function Component() {
  const { data } = useQuery(postOptions.all())

  return (
    <main className="container py-4">
      <h1 className="mb-4 text-2xl font-bold">Posts</h1>
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {data?.map((post) => (
          <PostCard key={post.id} post={post} />
        ))}
      </div>
    </main>
  )
}

function PostCard({ post }: { post: Post }) {
  const queryClient = useQueryClient()
  const { mutate, isPending } = useMutation({
    ...postOptions.delete(post.id),
    onSuccess: async () => {
      await queryClient.invalidateQueries(postFilters.all())
    },
  })

  return (
    <Card className="transition-shadow hover:shadow-lg">
      <CardHeader>
        <CardTitle>{post.title}</CardTitle>
        <CardDescription className="text-sm text-muted-foreground">
          {new Date(post.createdAt).toLocaleDateString()}
        </CardDescription>
        <CardAction>
          <Button
            variant="outline"
            size="icon"
            onClick={() => mutate()}
            disabled={isPending}
          >
            <span className="sr-only">Delete Post</span>
            <Trash2Icon />
          </Button>
        </CardAction>
      </CardHeader>
      <CardContent>
        <p className="text-sm text-muted-foreground">
          {post.content.length > 100
            ? `${post.content.slice(0, 100)}...`
            : post.content}
        </p>
      </CardContent>
    </Card>
  )
}
