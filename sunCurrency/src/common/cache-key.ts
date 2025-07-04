/** 缓存键的前缀 */
const CACHE_PREFIX = `V3-ANT`

/** 缓存数据时用到的 Key */
export class CacheKey {
  static readonly ACCESS_TOKEN = `${CACHE_PREFIX}_ACCESS_TOKEN_KEY`
  static readonly SIDEBAR_STATUS = `${CACHE_PREFIX}_SIDEBAR_STATUS_KEY`
  static readonly USER_PROFILE = `${CACHE_PREFIX}_USER_PROFILE_KEY`
  static readonly share_code = `${CACHE_PREFIX}_share_code_KEY`
}
