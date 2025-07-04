import request from '@/utils/request'

export function getLang(key:string){
    return request({
        url: `/config/get_lang_msg/${key}`,
        method: 'get'
    });
}

export function getLangList(){
    return request({
        url: `/config/get_lang_list`,
        method: 'get'
    });
}