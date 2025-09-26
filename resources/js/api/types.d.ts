interface ApiResponse<TData = unknown, TError = Error> {
  status: number
  message: string
  data: TData
  error?: TError
}
