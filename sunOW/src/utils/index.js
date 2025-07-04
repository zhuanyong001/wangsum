export function deepMerge(
    source,
    target,
    mergeArrays,
  ){
    if (!target) {
      return source;
    }
    if (!source) {
      return target;
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
            throw new Error(`Unknown merge array strategy: ${mergeArrays}`);
        }
      }
      if (isObject(targetValue) && isObject(sourceValue)) {
        return deepMerge(sourceValue, targetValue, mergeArrays);
      }
      return undefined;
    });
  }