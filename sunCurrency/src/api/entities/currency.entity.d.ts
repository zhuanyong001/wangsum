interface CurrencyConversionRequest {
    to_currency_id?: number
    currency_id?: number
    amount: number | string
    from_currency_id?:number
    id?:string
    tx_id?:string|number
    order_id?:number
}

interface pageRequest {
    page:number
    size:number
    type?:string,
    my?:number,
    status?:number
}

interface pledgePost{
    mining_pool_id: number, //矿池id
    currency_id: number, //币种id
    cycle: number, //周期id
    amount:number|string, //数量
}


interface withdrawParams {
    amount: string | number
    currency_id: string | number
}

interface withdrawPost {
    amount: string | number
    id: string | number
}

interface PriveLog{
    types:Array[string]
    times:[string,string]|[]
    currency_id:number
    page:number
    size:number
}