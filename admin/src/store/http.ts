import { AxiosRequestConfig, AxiosResponse } from 'axios';
import createHttp from '@/utils/axiosHttp';
import { isResponse } from '@/types';
import NProgress from 'nprogress';
import { useAccountStore } from '@/store';
import { useRouter } from 'vue-router';

const http = createHttp({
  timeout: 10000,
  baseURL: 'adminapi',
  withCredentials: true,
  xsrfCookieName: 'Authorization',
  xsrfHeaderName: 'Authorization',
});

const isAxiosResponse = (obj: any): obj is AxiosResponse => {
  return typeof obj === 'object' && obj.status && obj.statusText && obj.headers && obj.config;
};

// progress 进度条 -- 开启
http.interceptors.request.use((req: AxiosRequestConfig) => {
  if (!NProgress.isStarted()) {
    NProgress.start();
  }
  return req;
});

// 解析响应结果
http.interceptors.response.use(
  (rep: AxiosResponse<String>) => {
    const { data } = rep;
    console.log('====>>data', data);
    if (isResponse(data)) {
      return data.code === 200 ? data : Promise.reject(data);
    }
    return Promise.reject({ message: rep.statusText, code: rep.status, data });
  },
  (error) => {
    if (error.response) {
      console.log('====>>error.response', error.response);
      if (error.response.status === 401) {
        useAccountStore()
          .logout()
          .then(() => useRouter().push('/login'));
      }
      return Promise.reject({
        message: error?.response?.data?.msg || '系统错误',
        code: error.response.status,
        data: error.response.data,
      });
    }
    return Promise.reject(error);
  }
);

// progress 进度条 -- 关闭
http.interceptors.response.use(
  (rep) => {
    if (NProgress.isStarted()) {
      NProgress.done();
    }
    return rep;
  },
  (error) => {
    if (NProgress.isStarted()) {
      NProgress.done();
    }
    return error;
  }
);

export default http;
