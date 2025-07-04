// 用于获取嵌套属性值的辅助函数
export const getNestedValue = (obj, path) => {
  // 如果 path 是字符串，则将其转为数组形式
  const pathArray = typeof path === 'string' ? path.split('.') : path;

  return pathArray.reduce((acc, key) => {
    // 如果 acc 为 null 或 undefined，直接返回 null
    if (acc === null || acc === undefined) {
      return null;
    }
    // 如果 key 是数组索引，则转换为数字
    const numericKey = Number(key);
    if (!isNaN(numericKey) && Array.isArray(acc)) {
      return acc[numericKey];
    }
    // 否则直接使用键获取值
    return acc[key];
  }, obj);
};

export function calculateTotalValue(dataArray) {
  // Check if the input is undefined, null, or not an array
  if (!Array.isArray(dataArray) || dataArray.length === 0) {
    return '0';
  }

  const totalValue = dataArray.reduce((acc, item) => {
    const amount = parseFloat(item.amount); // Convert amount to a number
    const price = parseFloat(item.currency.price); // Convert price to a number
    const itemValue = amount * price; // Calculate total value for this item

    return acc + itemValue; // Accumulate the total value
  }, 0); // Initialize the accumulator as 0

  if (totalValue < 10e-8) {
    return '0';
  }

  return totalValue.toFixed(8); // Convert the total value to a string with 8 decimal places
}
