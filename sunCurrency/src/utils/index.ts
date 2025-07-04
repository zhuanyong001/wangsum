import { intersectionWith, isEqual, mergeWith, unionWith } from 'lodash-es';
import { isArray, isObject } from '@/utils/is';
/** 生成指定范围的随机数 包含边界值 */
export function randomCount(min = 2, max = 10): number {
  return Math.floor(Math.random() * (max - min + 1) + min)
}

/** 延时函数 指定 ms 后再执行后续函数 */
export function sleep(delay = 1000): Promise<void> {
  return new Promise((resolve, _) => setTimeout(() => resolve(), delay))
}

/** 用 JS 获取全局 CSS 变量 */
export function getCssVariableValue(cssVariableName: string): string {
  return getComputedStyle(document.documentElement).getPropertyValue(cssVariableName)
}

/** 用 JS 设置全局 CSS 变量 */
export function setCssVariableValue(cssVariableName: string, cssVariableValue: string): void {
  document.documentElement.style.setProperty(cssVariableName, cssVariableValue)
}

/** 获取变量的真实类型 */
export function getRawType(variable: any): string {
  return Object.prototype.toString.call(variable).split(' ')[1].replace(']', '').toLowerCase()
}

/** 反转字符串 */
export function reverseString(str: string): string {
  return str.split('').reverse().join('')
}

/** 驼峰字符串转下划线字符串 */
export function humpToUnderline(str: string): string {
  return str.replace(/([A-Z])/g, '_$1').toLowerCase()
}

/** 下划线字符串转驼峰字符串  */
export function underlineToHump(str: string): string {
  return str.replace(/\_(\w)/g, (_, letter: string) => letter.toUpperCase())
}

/** 驼峰字符串转中划线字符串 */
export function humpToDash(str: string): string {
  return str.replace(/([A-Z])/g, '-$1').toLowerCase()
}

/** 中划线字符串转驼峰字符串 */
export function dashToHump(str: string): string {
  return str.replace(/\-(\w)/g, (_, letter: string) => letter.toUpperCase())
}

/** 通过名称获取 URL 查询参数 */
export function getQueryByName(name: string): string {
  const queryNameRegExp = new RegExp(`[?&]${name}=([^&]*)(?:&|$)`)
  const queryNameMatch = window.location.href.match(queryNameRegExp)
  return queryNameMatch ? decodeURIComponent(queryNameMatch[1]) : ''
}

/** 查询字符串转对象 */
export function getQueryObject(url?: string): Record<string, string> {
  url = url ? url : window.location.href
  const search = url.substring(url.lastIndexOf('?') + 1)
  const query: Record<string, string> = {}
  const list = search.split('&')
  for (const item of list) {
    if (!item.includes('=')) continue
    const [key, value] = item.split('=')
    query[key] = value
  }
  return query
}

export function deepMerge<T extends object | null | undefined, U extends object | null | undefined>(
  source: T,
  target: U,
  mergeArrays: 'union' | 'intersection' | 'concat' | 'replace' = 'replace',
): T & U {
  if (!target) {
    return source as T & U;
  }
  if (!source) {
    return target as T & U;
  }
  return mergeWith({}, source, target, (sourceValue, targetValue) => {
    if (isArray(targetValue) && isArray(sourceValue)) {
      switch (mergeArrays) {
        case 'union':
          return unionWith(sourceValue, targetValue, isEqual);
        case 'intersection':
          return intersectionWith(sourceValue, targetValue, isEqual);
        case 'concat':
          return sourceValue.concat(targetValue);
        case 'replace':
          return targetValue;
        default:
          throw new Error(`Unknown merge array strategy: ${mergeArrays as string}`);
      }
    }
    if (isObject(targetValue) && isObject(sourceValue)) {
      return deepMerge(sourceValue, targetValue, mergeArrays);
    }
    return undefined;
  });
}

export function multiplyAndFormat(num1: string, num2: string): string {
  // 将字符串转换为浮点数
  const floatNum1 = parseFloat(num1);
  const floatNum2 = parseFloat(num2);
  
  // 相乘
  const result = floatNum1 * floatNum2;
  
  // 保留两位小数，并转换为字符串
  return result.toFixed(2);
}

export function calculateTotalValue(dataArray) {
  // Check if the input is undefined, null, or not an array
  if (!Array.isArray(dataArray) || dataArray.length === 0) {
    return "0.00000000";
  }

  const totalValue = dataArray.reduce((acc, item) => {
    const amount = parseFloat(item.amount||item.loan_amount); // Convert amount to a number
    const price = parseFloat(item.currency?.price||item.loan_coin?.price); // Convert price to a number
    const itemValue = amount * price; // Calculate total value for this item

    return acc + itemValue; // Accumulate the total value
  }, 0); // Initialize the accumulator as 0

  return totalValue.toFixed(8); // Convert the total value to a string with 8 decimal places
}

export function addAndFormat(num1: string, num2: string): string {
  // 将字符串转换为浮点数
  const floatNum1 = parseFloat(num1);
  const floatNum2 = parseFloat(num2);
  
  // 相乘
  const result = floatNum1 + floatNum2;
  
  // 保留两位小数，并转换为字符串
  return result.toFixed(2);
}

// 公共方法
// 将数字转换为带千分号的字符串
export function formatNumberWithCommas(value: number | string): string {
  // 尝试将输入转换为数字
  const numberValue = typeof value === 'string' ? parseFloat(value) : value;
  
  // 检查转换后的值是否为有效数字
  if (isNaN(numberValue)) {
      throw new Error("Input is not a valid number");
  }

  // 返回带千分号的字符串格式
  return numberValue.toLocaleString('en-US');
}

export const inComeNum = (num: number|string,rate:number|string) =>{
  const numNum = parseFloat(num)
  const rateNum = parseFloat(rate)
  return (numNum * rateNum).toFixed(8)
}