import { createStorage as create, CreateStorageParams } from './storageCache';

export type Options = Partial<CreateStorageParams>;

const createOptions = (storage: Storage, options: Options = {}): Options => {
  return {
    // 没有加密相关的选项
    storage,
    ...options,
  };
};

export const createStorage = (storage: Storage = sessionStorage, options: Options = {}) => {
  return create(createOptions(storage, options));
};

export const createSessionStorage = (options: Options = {}) => {
  return createStorage(sessionStorage, { ...options });
};

export const createLocalStorage = (options: Options = {}) => {
  return createStorage(localStorage, { ...options });
};
