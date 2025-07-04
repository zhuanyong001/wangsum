const map = new WeakMap()
const ob = new ResizeObserver((entries)=>{
    for(const entry of entries){
        // 执行这个元素的回调函数
        const handeler = map.get(entry.target)
        if(handeler){
            const box = entry.borderBoxSize[0];
            // 传入宽高
            handeler({
                width:box.inlineSize,
                height:box.blockSize
            })
        }
    }
})

export const obSize = {
    mounted(el, binding) {
        // 监视尺寸变化
        ob.observe(el)
        map.set(el,binding.value)
    },
    unmounted(el, binding) {
        // 取消监听
        ob.unobserve(el)
    }

}