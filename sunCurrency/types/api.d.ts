interface invitationUser {
  id:Number
  name:string
  type:string
  level?:string
  add_time?:string
  time?:time
  reward?:string,
  invitee:invitee
}

// 定义 Coin 类型
interface Coin {
  id: number;
  name: string;
  code: string;
}

// 定义 Pool 类型
type Coin = {
  id: number;
  name: string;
  code: string;
};

type Cycle = {
  id: number;
  days: number;
  type: number;
  daily_rate: string;
};

interface Pool {
  coin: number[];
  coins: Coin[];
  cycle: number[];
  cycles: Cycle[];
  description: string;
  id: number;
  name: string;
  sort: number;
  status: number;
  sumAmount:Array
  my_pool?:Asset[]
};

interface membership{
  name:string
}
interface User {
  id: number;
  share_code: string;
  curr_level:number
  avatar: string | null;
  referrer_id: number | null;
  tron_address: string;
  balance: string;
  created_at: string; // ISO 8601 日期字符串
  updated_at: string; // ISO 8601 日期字符串
  assets:Asset[];
  membership:membership
}

interface Currency {
  id: number
  name: string
  code: string
  price: string
  change_24h: string
  contract_address:string
  icon:string
  percentage_fee:string
  fixed_fee:string
}

interface MiningPool {
  name: string;
  id: number;
  description: string;
}

interface Pledge {
  amount: string;
  coin_code: string;
  compound: number;
  created_at: string;
  currency: Currency;
  currency_id: number;
  cycle: number;
  daily_rate: string;
  expire_time: string | null;
  id: number;
  mining_pool: MiningPool;
  mining_pool_id: number;
  order_no: string;
  settlement_base: string;
  status: number;
  total_award: string;
  type: number;
  updated_at: string;
  user_id: number;
}

interface Asset {
  id: number;
  user_id: number;
  currency_id: number;
  amount: string;
  currency: Currency;
  pools_amount:string
}

/** 所有 api 接口的响应数据都应该遵守该格式 */
interface ApiResponseData<T = any> {
  code: number
  msg: string
  data: T
}

interface LoginResponse {
  token: string;
  data: User;
}

/** 列表类型的响应数据类型 */
interface TableResponseData<T = any> extends ApiResponseData {
  result: {
    total: number
    records: T
  }
}

/** 基础的分页查询参数 */
interface TableQueryParams {
  pageNo: number
  pageSize: number
}

/* 定义类型别名，简化接口返回类型的书写 */
type ApiResult<T = any> = Promise<ApiResponseData<T>>
type TableResult<T = any> = Promise<TableResponseData<T>>

interface selectObj {
  name: string|number
  value: string | number | (() => [string,string])
}

interface currencyType{
  icon:string,
  name:string,
  num:string
}
