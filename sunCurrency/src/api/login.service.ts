import request from '@/utils/request'

/** 避免 hooks 写法下的命名冲突 */
export class LoginService {
  /** 登录 */
  static login(data: LoginEntity.LoginParams): ApiResult<string> {
    return request({
      url: '/web3/login',
      method: 'post',
      data,
    });
  }
  static getUserMessage(){
    return request({
      url: '/web3/user_info',
      method: 'post'
    });
  }
}
