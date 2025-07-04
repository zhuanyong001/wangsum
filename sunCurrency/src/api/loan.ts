import request from '@/utils/request'

// 借贷列表
export function loanList(params:pageRequest){
    return request({
        url: `/web3/loan_pool/list`,
        method: 'get',
        params
    });
}

// 借贷
export function loan(data:pledgePost){
    return request({
        url: `/web3/loan_pool/create_order`,
        method: 'post',
        data,
        headers: {
            login: true
        }
    });
}

// 借贷统计
export function loanStat() {
    return request({
        url: `/web3/loan_pool/get_loan_pool_stat`,
        method: 'get'
    });
}

// 借贷历史
export function loanHistory(params:pageRequest) {
    return request({
        url: `/web3/loan_pool/order_list`,
        method: 'get',
        params
    });
}

// 还款
export function repay(data) {
    return request({
        url: `/web3/loan_pool/settlement`,
        method: 'post',
        data
    });
}

// 借贷预览
export function previewLoanOrder(data){
    return request({
        url: `/web3/loan_pool/preview_order`,
        method: 'post',
        data,
        headers: {
            login: true
        }
    });
}