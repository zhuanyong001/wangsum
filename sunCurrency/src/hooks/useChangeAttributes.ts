// useChangeAttributes.ts
import { computed } from 'vue';

export function useChangeAttributes(change: string | number,digit = 2) {
  // 将输入值转换为数字类型，并处理可能的 NaN 情况
  const numericChange = parseFloat(change as string);
  // const isValidNumber = !isNaN(numericChange);
  console.log('数字',numericChange)
  const color = () => (numericChange < 0 ? '#EB3938' : '#1FBB85');
  const sign = () => (numericChange < 0 ? '-' : '+');

  const replaceFu = (str: string, beilv:number = 1): string => {
    // let modifiedStr = str.replace('-', '');
    
    let num = Math.abs(parseFloat(str));
    // if (isNaN(num)) {
    //     return str; // 如果转换失败，返回原始字符串
    // }
    return (num).toFixed(digit);
  }
  return {
    color,
    sign,
    replaceFu
  };
}
