import { useHttp } from '@/utils/useHttp.js';

// 获取周期列表
export function getPledgeList(){
    return useHttp('/web3/mining-pools/cycle_item/list', 'GET', {
        page: 1,
        size: 99
    })
}

// 获取币种列表
export function getCurrencyList(){
    return useHttp('/web3/currencies', 'GET', {
        page: 1,
        size: 99
    })
}
// 获取角色列表
export function getRolesList(){
    return useHttp('/web3/roles', 'GET', {
        page: 1,
        size: 99
    })
}
// 获取会员等级列表
export function getLevelList(){
    return useHttp('/web3/membership_levels/index', 'GET', {
        page: 1,
        size: 99
    })
}
// 获取语言列表
export function geti8nList(){
    return useHttp('/i18n/langlist', 'GET')
}
// 获取全部数据
export function getAllStatistics(data){
    return useHttp('/web3/statistics/all', 'POST_JSON',data)
}

// 获取提现数据
export function getWithdrawStatistics(data){
    return useHttp('/web3/statistics/withdraw', 'POST_JSON',data)
}

// 获取充值数据
export function getRechargeStatistics(data){
    return useHttp('/web3/statistics/recharge', 'POST_JSON',data)
}

// 获取用户数据
export function getUserStatistics(data){
    return useHttp('/web3/statistics/user', 'POST_JSON',data)
}