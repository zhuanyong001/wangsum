export interface LoginForm {
  username: string;
  password: string;
}

export interface Response<T = never> {
  message: string;
  code: number;
  data: T;
}

export function isResponse(obj: any): obj is Response<any> {
  return typeof obj === 'object' && obj.message !== undefined && obj.code !== undefined;
}


export interface currency{
  id:Number,
  name:String,
  price:String
}
export interface amount{
  amount:String,
  currency_id:Number,
  currency:currency
}
export interface allDataType {
  total_register_user:Number,
  total_recharge_count:Number,
  total_recharge_user:Number,
  total_recharge_amounts:Array<amount>,
  total_withdrawal_count:Number,
  total_withdrawal_user:Number,
  total_withdrawal_amounts:Array<amount>,
  total_dingqi_amounts:Array<amount>,
  total_huoqi_amounts:Array<amount>
}