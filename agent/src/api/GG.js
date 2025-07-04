import { useHttp } from '@/utils/useHttp.js';

// 创建谷歌密钥
export function createGGKey(params){
    return useHttp('/web3/roles/generate_google_2fa_secret', 'GET', params)
}

// 获取绑定谷歌密钥的二维码
export function getGGQrcode(params){
    return useHttp('/web3/roles/get_google_2fa_qrcode', 'GET',params)
}

// 清除谷歌密钥
export function clearGGKey(params){
    return useHttp('/web3/roles/generate_google_2fa_secret', 'GET',params)
}