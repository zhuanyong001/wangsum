import { getLang } from '@/api/lang'

const symbolToStringTag = Symbol('Module');
export async function createModuleObject(type:string) {
    // 异步获取JSON字符串
    const jsonObject = await getLang(type);

    // 定义模块对象
    const moduleObject:Recordable<Recordable> = {
        [symbolToStringTag]: 'Module',
        default: {
            message: {
                ...jsonObject
            },
        },
        // get default() {
        //     return this.default;
        // },
        // set default(value) {
        //     this._default = value;
        // }
    };

    return Promise.resolve(moduleObject);
}
