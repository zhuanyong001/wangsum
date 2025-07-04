namespace LoginEntity {
  /** 用户登录所需要的数据 */
  interface LoginParams {
    address: string
    signature: string
    message:string
    share_code:string
  }
}
