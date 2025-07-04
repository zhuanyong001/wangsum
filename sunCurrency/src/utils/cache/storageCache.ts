import { isNil } from '@/utils/is';

export interface CreateStorageParams {
  prefixKey: string;
  storage: Storage;
  hasEncrypt: boolean;
  timeout?: Nullable<number>;
}

export const createStorage = ({
  prefixKey = '',
  storage = sessionStorage,
  timeout = null,
}: Partial<CreateStorageParams> = {}) => {

  const WebStorage = class WebStorage {
    private storage: Storage;
    private prefixKey?: string;

    constructor() {
      this.storage = storage;
      this.prefixKey = prefixKey;
    }

    private getKey(key: string) {
      return `${this.prefixKey}${key}`.toUpperCase();
    }

    set(key: string, value: any, expire: number | null = timeout) {
      const stringData = JSON.stringify({
        value,
        time: Date.now(),
        expire: !isNil(expire) ? new Date().getTime() + expire * 1000 : null,
      });
      this.storage.setItem(this.getKey(key), stringData);
    }

    get(key: string, def: any = null): any {
      const val = this.storage.getItem(this.getKey(key));
      if (!val) return def;

      try {
        const data = JSON.parse(val);
        const { value, expire } = data;
        if (isNil(expire) || expire >= new Date().getTime()) {
          return value;
        }
        this.remove(key);
      } catch (e) {
        return def;
      }
    }

    remove(key: string) {
      this.storage.removeItem(this.getKey(key));
    }

    clear(): void {
      this.storage.clear();
    }
  };
  return new WebStorage();
};
