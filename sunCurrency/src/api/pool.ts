import request from '@/utils/request'

export function getPoolList(params:pageRequest){
    return request({
        url: `/web3/mining_pool/list`,
        method: 'get',
        params
    });
}

// 进行质押
export function pledgePost(data:pledgePost){
    return request({
        url: `/web3/mining_pool/create_order`,
        method: 'post',
        data,
        headers: {
            login: true
        }
    });
}

//质押列表
export function pledgeList(params:{status:string},cate='pool'){
    return request({
        url: `/web3/mining_pool/order_list/${cate}`,
        method: 'get',params
    });
}

// 赎回
export function withdrawPledge(data){
    return request({
        url: `/web3/mining_pool/withdraw`,
        method: 'post',
        data
    });
}


// 存款列表
export function depositList(params:pageRequest){
    return request({
        url: `/web3/deposit_pool/list/depoosit`,
        method: 'get',
        params
    });
}

// 存款
export function deposit(data:pledgePost){
    return request({
        url: `/web3/deposit_pool/create_order`,
        method: 'post',
        data,
        headers: {
            login: true
        }
    });
}

// 存款统计
export function depositStat(){
    return request({
        url: `/web3/deposit_pool/get_deposit_pool_stat`,
        method: 'get'
    });
}


// 全部历史订单
export function allHistoryOrder(params:pageRequest){
    return request({
        url: `/web3/all_pool/order_list`,
        method: 'get',
        params
    });
}