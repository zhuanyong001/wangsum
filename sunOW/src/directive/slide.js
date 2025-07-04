const map = new WeakMap();
const ob = new IntersectionObserver((entries) => {
    for (const entry of entries){
        if(entry.isIntersecting){
            const animation = map.get(entry.target)
            if(animation){
                animation.play()
                ob.unobserve(entry.target)
            }
        }
    }
});

export const slide = {
    mounted(el, binding) {
        const animation = el.animate([
            {
                transform:'translateY(100%)',
                opacity: 0.5,
            },
            {
                transform:'translateY(0)',
                opacity: 1,
            },
        ],{
            duration: 500,
            easing:"ease-in-out",
            fill:'forwards'
        });
        // animation.pause();
        ob.observe(el)
        map.set(el, animation)
    },
    unmounted(el, binding) {
        ob.unobserve(el)
    }
}

export const eraser = {
    mounted(el, binding) {
        const animation = el.animate([
            {
                '--p': '0%',
            },
            {
                '--p': '100%',
            },
        ],{
            duration: 5000,
            easing:"ease-in-out",
            fill:'forwards'
        });
        // animation.pause();
        ob.observe(el)
        map.set(el, animation)
    },
    unmounted(el, binding) {
        ob.unobserve(el)
    }
} 