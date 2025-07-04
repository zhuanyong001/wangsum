import request from '@/utils/request'

// 配置列表
export function getConfig(data){
    return request({
        url: `/config/get_configs`,
        method: 'post',
        data
    });
}
