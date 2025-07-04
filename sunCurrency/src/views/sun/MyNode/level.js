export const useLevel = () => {
  const userStore = useUser()
  const user = computed(() => userStore.user)
  const levelArr = [
    { level: 1, name: 'V0', icon: 'V0' },
    { level: 2, name: 'V1', icon: 'V1' },
    { level: 3, name: 'V2', icon: 'V2' },
    { level: 4, name: 'V3', icon: 'V3' },
    { level: 5, name: 'V4', icon: 'V4' },
    { level: 6, name: 'V5', icon: 'V5' },
    { level: 7, name: 'V6', icon: 'V6' },
    { level: 8, name: 'V7', icon: 'V7' },
    { level: 9, name: 'V8', icon: 'V8' },
  ]

  function getLevel(level) {
    return levelArr.find((item) => item.level == level)
  }
  return {
    levelArr,
    getLevel,
  }
}
