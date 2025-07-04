import { defineStore } from 'pinia';
import http from './http';
import { Response } from '@/types';
import { useMenuStore } from './menu';
import { useAuthStore } from '@/plugins';

export interface Profile {
  account: Account;
  permissions: string[];
  role: string;
}
export interface Account {
  username: string;
  avatar: string;
  gender: number;
}

export type TokenResult = {
  token: string;
  expires: number;
};
export const useAccountStore = defineStore('account', {
  state() {
    return {
      account: {} as Account,
      permissions: [] as string[],
      role: '',
      logged: true,
    };
  },
  actions: {
    async login(username: string, password: string,google2fa_code:string) {
      return http
        .request<TokenResult, Response<TokenResult>>('/admin/login', 'post_json', { username, password ,google2fa_code})
        .then(async (response) => {
          if (response.code === 200) {
            this.logged = true;
            http.setAuthorization(`Bearer ${response.data.data.token}`, new Date().getTime() + 7 * 24 * 60 * 60 * 1000);
            await useMenuStore().getMyMenuList();
            return response.data;
          } else {
            return Promise.reject(response);
          }
        });
    },
    async logout() {
      return new Promise<boolean>((resolve) => {
        localStorage.removeItem('stepin-menu');
        http.removeAuthorization();
        useMenuStore().clearMyMenuList();
        this.logged = false;
        resolve(true);
      });
    },
    async profile() {
      return http.request<Account, Response<Profile>>('/account', 'get').then((response) => {
        if (response.code === 0) {
          const { setAuthorities } = useAuthStore();
          const { account, permissions, role } = response.data;
          this.account = account;
          this.permissions = permissions;
          this.role = role;
          setAuthorities(permissions);
          return response.data;
        } else {
          return Promise.reject(response);
        }
      });
    },
    setLogged(logged: boolean) {
      this.logged = logged;
    },
  },
});
