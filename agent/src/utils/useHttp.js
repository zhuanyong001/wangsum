import http from '@/store/http';

import { message } from 'ant-design-vue';

export const useHttp = async (url, method, data) => {
  let res = await http.request(url, method, data);
  console.log('====>>res', res);
  if (res.code === 200) {
    return res.data;
  } else {
    console.log('====>>res', res);
    message.error(res.message, 2);
    return Promise.reject(res);
  }
};

export const usePost = async (url, data) => {
  let res = await http.request(url, 'POST_JSON', data);
  if (res?.code === 200) {
    return res.data;
  } else {
    console.log('====>>res', res);
    message.error(res.message, 2);
    return Promise.reject(res);
  }
};

export const useGet = async (url, data) => {
  let res = await http.request(url, 'GET', data);
  if (res?.code === 200) {
    return res.data;
  } else {
    message.error(res.message, 2);
    return Promise.reject(res);
  }
};
export const getApiToken = () => {
  let btoken = http.getAuthorization();
  if (btoken) {
    return btoken.split(' ')[1];
  }

  return '';
};
