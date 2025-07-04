import request from '@/utils/request'

export function sunExchange(data: CurrencyConversionRequest) {
  return request({
    url: `/web3/exchange`,
    method: 'post',
    data,
  })
}

export function sunRecharge(data: CurrencyConversionRequest) {
  return request({
    url: `/web3/recharge`,
    method: 'post',
    data,
  })
}

export function curList() {
  return request({
    url: `/web3/currency_list`,
    method: 'get',
  })
}

export function getAddress(params: { currency_id: number; amount?: number | string | undefined }) {
  return request({
    url: `/web3/get_deposit_address`,
    method: 'get',
    params,
  })
}

export function withdraw(data: withdrawParams) {
  return request({
    url: `/web3/withdraw`,
    method: 'post',
    data,
  })
}

export function getLog(data: PriveLog) {
  return request({
    url: `/web3/get_asset_logs`,
    method: 'post',
    data,
  })
}

export function getRealStat() {
  return request({
    url: `/web3/mining_pool/stat`,
    method: 'get',
  })
}
