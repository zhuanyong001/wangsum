export { LoginService } from './login.service'
import request from '@/utils/request'
export function dump_log(data: any) {
  console.log(data)
  request({
    url: `/dump_log`,
    method: 'post',
    data: data,
  })
}

export function get_notices() {
  return request({
    url: `/web3/notices`,
    method: 'get',
  })
}
