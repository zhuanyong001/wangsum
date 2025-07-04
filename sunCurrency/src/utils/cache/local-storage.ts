/** 统一处理 localStorage */
import { CacheKey, WebStorage } from '@/common'

/* -------------------------------------------------------------------------- */
/*                                    Token                                   */
/* -------------------------------------------------------------------------- */
export const setAccessToken = (token: string) => WebStorage.setItem(CacheKey.ACCESS_TOKEN, token)
export const getAccessToken = (): string => WebStorage.getItem(CacheKey.ACCESS_TOKEN) || ''
export const removeAccessToken = () => WebStorage.removeItem(CacheKey.ACCESS_TOKEN)

/* -------------------------------------------------------------------------- */
/*                               Sidebar Status                               */
/* -------------------------------------------------------------------------- */
export const setSidebarStatus = (status: boolean) => WebStorage.setItem(CacheKey.SIDEBAR_STATUS, status)
export const getSidebarStatus = (): boolean => WebStorage.getItem(CacheKey.SIDEBAR_STATUS) || true

/* -------------------------------------------------------------------------- */
/*                               Project Config                               */
/* -------------------------------------------------------------------------- */
export const setUserProfile = (profile: User) => WebStorage.setItem(CacheKey.USER_PROFILE, profile);
export const getUserProfile = (): User => WebStorage.getItem(CacheKey.USER_PROFILE) || ({} as User);
export const removeUserProfile = () => WebStorage.removeItem(CacheKey.USER_PROFILE);
/* -------------------------------------------------------------------------- */
/*                               ShareCode Config                               */
/* -------------------------------------------------------------------------- */
export const setShareCode = (profile: string) => WebStorage.setItem(CacheKey.share_code, profile);
export const getShareCode = (): string => WebStorage.getItem(CacheKey.share_code);
export const removeShareCode = () => WebStorage.removeItem(CacheKey.share_code);