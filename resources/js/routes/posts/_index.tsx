import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'

import type { Post } from '@/api/post'
import { api } from '@/api'
import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { XIcon } from 'lucide-react'
import { Link } from 'react-router'
import { toast } from 'sonner'

export function Component() {
  return (
    <main className="container py-4">
      <div className="mb-4 flex items-center justify-between">
        <h1 className="text-2xl font-bold">Posts</h1>
        <Button size="sm">
          <Link to="/posts/create">New Post</Link>
        </Button>
      </div>

      <section className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <h2 className="sr-only">Posts</h2>
        <PostList />
      </section>
    </main>
  )
}

const PostList: React.FC = () => {
  const { data, status } = useQuery(api.post.all.queryOptions())

  if (status === 'pending' || status === 'error')
    return Array.from({ length: 3 }).map((_, i) => <PostCardSkeleton key={i} />)

  return data.map((post) => <PostCard key={post.id} post={post} />)
}

const PostCard: React.FC<{ post: Post }> = ({ post }) => {
  const queryClient = useQueryClient()

  const { mutate, isPending } = useMutation({
    ...api.post.delete.mutationOptions(post.id),
    onSuccess: async () => {
      await Promise.all([
        queryClient.invalidateQueries({ queryKey: api.post.all.queryKey() }),
        queryClient.invalidateQueries({
          queryKey: api.post.byId.queryKey(post.id),
        }),
      ])
    },
    onError: (error) => toast.error(error.message),
  })

  return (
    <Card>
      <CardHeader>
        <CardTitle>{post.title}</CardTitle>
        <CardDescription>{post.createdAt.toLocaleDateString()}</CardDescription>
        <CardAction>
          <Button
            variant="outline"
            size="icon"
            disabled={isPending}
            onClick={() => mutate()}
          >
            <XIcon />
          </Button>
        </CardAction>
      </CardHeader>
      <CardContent>
        <p className="line-clamp-2">{post.content}</p>
      </CardContent>
    </Card>
  )
}

const PostCardSkeleton: React.FC = () => {
  return (
    <Card>
      <CardHeader>
        <CardTitle className="w-1/2 animate-pulse rounded-md bg-current">
          &nbsp;
        </CardTitle>
        <CardDescription className="w-1/4 animate-pulse rounded-md bg-current">
          &nbsp;
        </CardDescription>
      </CardHeader>
      <CardContent>
        <p className="w-full animate-pulse rounded-md bg-current">&nbsp;</p>
        <p className="mt-1 w-2/3 animate-pulse rounded-md bg-current">&nbsp;</p>
      </CardContent>
    </Card>
  )
}
